<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Group;
use App\Models\Participant;
use App\Services\SettlementService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    public function show(Request $request, $slug)
    {
        $group = Group::where('slug', $slug)
            ->select('id', 'name', 'description', 'slug', 'currency_id', 'created_at')
            ->with([
                'currency' => function ($query) {
                    $query->select('id', 'name', 'code', 'symbol', 'decimal_digits');
                },
                'participants' => function ($query) {
                    $query->select('id', 'group_id', 'name');
                },
                'bills' => function ($query) {
                    $query->select('id', 'group_id', 'name', 'currency_id', 'amount', 'amount_in_base_currency', 'created_at')
                        ->with([
                            'currency' => function ($query) {
                                $query->select('id', 'code', 'symbol', 'decimal_digits');
                            }
                        ]);
                }
            ])
            ->selectRaw('(SELECT sum(amount_in_base_currency) FROM bills WHERE group_id = groups.id) as total_amount_in_base_currency')
            ->firstOrFail();

        $settlements = SettlementService::getSettlements($group->id);
        $group->total_amount_in_base_currency = round($group->total_amount_in_base_currency ?? 0, $group->currency->decimal_digits);

        // If the request url is edit, return the edit view
        if ($request->is('groups/' . $slug . '/edit')) {
            $currencies = Currency::all('id', 'name', 'code', 'symbol');

            return Inertia::render('Group/Edit', [
                'group' => $group,
                'currencies' => $currencies
            ]);
        }

        return Inertia::render('Group/Show', [
            'group' => $group,
            'settlements' => $settlements
        ]);
    }

    public function new()
    {
        $currencies = Currency::all('id', 'name', 'code', 'symbol');
        return Inertia::render('Group/New', [
            'currencies' => $currencies
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'description' => 'nullable|max:255',
            'currency_id' => 'required',
            'participants' => 'required|array|min:2',
            'participants.*.name' => 'required|string|max:100',
        ]);

        $name = $request->input('name');

        $slug = substr(Str::slug($name), 0, 10) . '-' . substr(md5($name . uniqid()), 0, 6);

        $group = new Group([
            'name' => $name,
            'description' => $request->input('description'),
            'slug' => $slug,
            'currency_id' => $request->input('currency_id'),
        ]);
        $group->save();

        $participants = [];
        foreach ($request->input('participants') as $participant) {
            $participants[] = new Participant([
                'name' => $participant['name'],
            ]);
        }

        $group->participants()->saveMany($participants);

        return $group;
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:100',
            'description' => 'nullable|max:255',
            'currency_id' => 'required',
            'participants' => 'required|array|min:2',
            'participants.*.name' => 'required|string|max:100',
        ]);

        $group = Group::findOrFail($id);

        $group->name = $request->input('name');
        $group->description = $request->input('description');
        $group->currency_id = $request->input('currency_id');
        $group->save();

        $participantIds = [];
        $participantsToCreate = [];
        foreach ($request->input('participants') as $participant) {
            if (isset($participant['id']) && !empty($participant['id'])) {
                $participantIds[] = $participant['id'];

                Participant::where('id', $participant['id'])->update([
                    'name' => $participant['name'],
                ]);
            } else {
                // Prepare participant data for creation
                $participantsToCreate[] = new Participant([
                    'name' => $participant['name'],
                ]);
            }
        }

        if (!empty($participantIds)) {
            Participant::where('group_id', $group->id)
                ->whereNotIn('id', $participantIds)
                ->delete();
        }

        if (!empty($participantsToCreate)) {
            $group->participants()->saveMany($participantsToCreate);
        }

        return $group;
    }
}
