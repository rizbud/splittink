<?php

namespace App\Services;

use App\Models\Group;
use DebugBar\DebugBar;

class SettlementService
{
    public static function getTotalUnpaidAmount($groupId)
    {
        // SELECT
        //     p.id, p.name, p.settled_amount, g.id group_id, ROUND(sum(bp.unpaid_amount_in_base_currency) - p.settled_amount, c.decimal_digits) total_unpaid
        // FROM
        //     groups g
        // JOIN
        //    currencies c ON c.id = g.currency_id
        // JOIN
        //     participants p ON p.group_id = g.id
        // JOIN
        //     bill_participants bp ON bp.participant_id = p.id
        // WHERE
        //     g.id = $groupId
        // GROUP
        //     BY p.id
        $totalUnpaidAmount = Group::where('group_id', $groupId)
            ->join('currencies', 'currencies.id', '=', 'groups.currency_id')
            ->join('participants', 'participants.group_id', '=', 'groups.id')
            ->join('bill_participants', 'bill_participants.participant_id', '=', 'participants.id')
            ->select('participants.id', 'participants.name', 'participants.settled_amount', 'groups.id as group_id', 'currencies.decimal_digits')
            ->selectRaw('ROUND(SUM(unpaid_amount_in_base_currency) - settled_amount, decimal_digits) as total_unpaid')
            ->groupBy('participants.id')
            ->get();

        return $totalUnpaidAmount->map(function ($participant) {
            $participant->total_unpaid = round($participant->total_unpaid - $participant->total_paid, 2);
            return $participant;
        });
    }

    public static function getSettlements($groupId)
    {
        $totalUnpaidAmount = self::getTotalUnpaidAmount($groupId);
        if ($totalUnpaidAmount->count() == 0) {
            return [];
        }

        $decimalDigits = $totalUnpaidAmount->first()->decimal_digits;
        $totalUnpaid = $totalUnpaidAmount->sum('total_unpaid');
        $participantCount = $totalUnpaidAmount->count();
        // check if there are no participants
        if ($participantCount == 0) {
            return [];
        }
        $averageUnpaid = $totalUnpaid / $participantCount;

        $toPay = [];
        $toReceive = [];

        foreach ($totalUnpaidAmount as $participant) {
            $balance = round($participant->total_unpaid - $averageUnpaid, $decimalDigits);

            if ($balance > 0) {
                // Participant owes money (needs to pay)
                $toPay[] = [
                    'id' => $participant->id,
                    'name' => $participant->name,
                    'amount' => $balance
                ];
            } elseif ($balance < 0) {
                $toReceive[] = [
                    'id' => $participant->id,
                    'name' => $participant->name,
                    'amount' => abs($balance)
                ];
            }
        }

        $settlements = [];
        foreach ($toPay as &$payer) {
            foreach ($toReceive as &$receiver) {
                if ($payer['amount'] == 0 || $receiver['amount'] == 0) {
                    continue;
                }

                $paymentAmount = min($payer['amount'], $receiver['amount']);

                $settlements[] = [
                    'from' => [
                        'id' => $payer['id'],
                        'name' => $payer['name']
                    ],
                    'to' => [
                        'id' => $receiver['id'],
                        'name' => $receiver['name']
                    ],
                    'amount' => $paymentAmount
                ];

                $payer['amount'] -= $paymentAmount;
                $receiver['amount'] -= $paymentAmount;
            }
        }

        return $settlements;
    }
}
