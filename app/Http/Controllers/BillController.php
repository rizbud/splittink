<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillParticipant;
use App\Models\Currency;
use App\Models\Group;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BillController extends Controller
{
    public function show(Request $request, $slug, $billId)
    {
        $group = Group::where('slug', $slug)
            ->select('id', 'slug', 'name', 'currency_id', 'created_at')
            ->with(['currency', 'participants' => function ($query) {
                $query->select('id', 'group_id', 'name');
            }])
            ->firstOrFail();

        // Get the bill with the given id and group slug
        $bill = Bill::where('id', $billId)
            ->with('currency')
            ->firstOrFail();

        $bill_participants = BillParticipant::where('bill_id', $billId)
            ->with(['participant' => function ($query) {
                $query->select('id', 'bill_id', 'name');
            }])
            ->get();

        if ($request->is('groups/' . $slug . '/bills/' . $billId . '/edit')) {
            $currencies = Currency::all('id', 'name', 'code', 'symbol', 'exchange_rate');
            return Inertia::render('Bill/Edit', [
                'bill' => $bill,
                'bill_participants' => $bill_participants,
                'group' => $group,
                'currencies' => $currencies,
            ]);
        }

        return Inertia::render('Bill/Show', [
            'bill' => $bill,
            'bill_participants' => $bill_participants,
            'group' => $group,
        ]);
    }

    public function new($slug)
    {
        $group = Group::where('slug', $slug)
            ->select('id', 'slug', 'name', 'currency_id')
            ->with(['currency', 'participants' => function ($query) {
                $query->select('id', 'group_id', 'name');
            }])
            ->firstOrFail();

        $currencies = Currency::all();

        return Inertia::render('Bill/New', [
            'group' => $group,
            'currencies' => $currencies,
        ]);
    }

    public function create(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'amount' => 'required|numeric|min:0.01|max:2147483647',
            'currency_id' => 'required|exists:currencies,id',
            'participants' => 'required|array',
            'participants.*.participant_id' => 'required|numeric|exists:participants,id',
            'participants.*.paid_amount' => 'required|numeric',
        ]);

        $group = Group::with([
            'currency',
            'participants' => function ($query) {
                $query->select('id', 'group_id', 'name');
            }
        ])->findOrFail($id, ['id', 'slug', 'name', 'currency_id']);

        $totalPaidAmount = collect($request->participants)->sum('paid_amount');
        if ($totalPaidAmount != $request->amount) {
            return response()->json([
                'message' => 'The total paid amount must be equal to the bill amount.',
            ], 422);
        }

        $baseCurrency = $group->currency;
        $baseRate = $baseCurrency->exchange_rate;
        $selectedCurrency = Currency::find($request->currency_id);
        $selectedRate = $selectedCurrency->exchange_rate;
        $rate = (1 / $selectedRate) * $baseRate;
        $amount = $request->amount;
        $amountInBaseCurrency = round($amount * $rate, $baseCurrency->decimal_digits);

        $bill = $group->bills()->create([
            'name' => $request->name,
            'currency_id' => $request->currency_id,
            'amount' => round($amount, $selectedCurrency->decimal_digits),
            'amount_in_base_currency' => $amountInBaseCurrency,
            'splitting_method' => 'equally',
        ]);

        $amountPerParticipant = round($amount / count($request->participants), $selectedCurrency->decimal_digits);

        $participants = [];
        foreach ($request->participants as $participant) {
            $participantId = $participant['participant_id'];
            $paidAmount = round($participant['paid_amount'], $selectedCurrency->decimal_digits);
            $paidAmountInBaseCurrency = round($participant['paid_amount'] * $rate, $baseCurrency->decimal_digits);
            $unpaidAmount = round($amountPerParticipant - $paidAmount, $selectedCurrency->decimal_digits);
            $unpaidAmountInBaseCurrency = round($unpaidAmount * $rate, $baseCurrency->decimal_digits);

            $participants[$participantId] = [
                'paid_amount' => $paidAmount,
                'paid_amount_in_base_currency' => $paidAmountInBaseCurrency,
                'unpaid_amount' => $unpaidAmount,
                'unpaid_amount_in_base_currency' => $unpaidAmountInBaseCurrency,
            ];
        }

        // Attach participants with additional pivot data
        $bill->participants()->attach($participants);

        return $bill;
    }

    public function update(Request $request, $id, $billId)
    {
        $request->validate([
            'name' => 'required|string',
            'amount' => 'required|numeric|min:0.01|max:2147483647',
            'currency_id' => 'required|exists:currencies,id',
            'participants' => 'required|array',
            'participants.*.participant_id' => 'required|numeric|exists:participants,id',
            'participants.*.paid_amount' => 'required|numeric',
        ]);

        $group = Group::with([
            'currency',
            'participants' => function ($query) {
                $query->select('id', 'group_id', 'name');
            }
        ])->findOrFail($id, ['id', 'slug', 'name', 'currency_id']);

        $totalPaidAmount = collect($request->participants)->sum('paid_amount');
        if ($totalPaidAmount != $request->amount) {
            return response()->json([
                'message' => 'The total paid amount must be equal to the bill amount.',
            ], 422);
        }

        $baseCurrency = $group->currency;
        $baseRate = $baseCurrency->exchange_rate;
        $selectedCurrency = Currency::find($request->currency_id);
        $selectedRate = $selectedCurrency->exchange_rate;
        $rate = (1 / $selectedRate) * $baseRate;
        $amount = $request->amount;
        $amountInBaseCurrency = round($amount * $rate, $baseCurrency->decimal_digits);

        $bill = Bill::findOrFail($billId);

        $bill->update([
            'name' => $request->name,
            'currency_id' => $request->currency_id,
            'amount' => round($amount, $selectedCurrency->decimal_digits),
            'amount_in_base_currency' => $amountInBaseCurrency,
        ]);

        $amountPerParticipant = round($amount / count($request->participants), $selectedCurrency->decimal_digits);

        $participants = [];
        foreach ($request->participants as $participant) {
            $participantId = $participant['participant_id'];
            $paidAmount = round($participant['paid_amount'], $selectedCurrency->decimal_digits);
            $paidAmountInBaseCurrency = round($participant['paid_amount'] * $rate, $baseCurrency->decimal_digits);
            $unpaidAmount = round($amountPerParticipant - $paidAmount, $selectedCurrency->decimal_digits);
            $unpaidAmountInBaseCurrency = round($unpaidAmount * $rate, $baseCurrency->decimal_digits);

            $participants[$participantId] = [
                'paid_amount' => $paidAmount,
                'paid_amount_in_base_currency' => $paidAmountInBaseCurrency,
                'unpaid_amount' => $unpaidAmount,
                'unpaid_amount_in_base_currency' => $unpaidAmountInBaseCurrency,
            ];
        }

        $bill->participants()->sync($participants);

        return $bill;
    }

    public function delete($slug, $billId)
    {
        $bill = Group::where('slug', $slug)
            ->firstOrFail()
            ->bills()
            ->findOrFail($billId);
        $bill->participants()->detach();
        $bill->delete();


        return response(null, 204);
    }
}
