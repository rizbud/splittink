<?php

namespace App\Services;

use App\Models\Group;

class SettlementService
{
    public static function getTotalUnpaidAmount($groupId)
    {
        // SELECT
        //     p.id, p.name, g.id group_id, sum(bp.unpaid_amount_in_base_currency) total_unpaid
        // FROM
        //     groups g
        // JOIN
        //     participants p ON p.group_id = g.id
        // JOIN
        //     bill_participants bp ON bp.participant_id = p.id
        // WHERE
        //     g.id = $groupId
        // GROUP
        //     BY p.id
        $totalUnpaidAmount = Group::where('group_id', $groupId)
            ->join('participants', 'participants.group_id', '=', 'groups.id')
            ->join('bill_participants', 'bill_participants.participant_id', '=', 'participants.id')
            ->select('participants.id', 'participants.name', 'groups.id as group_id')
            ->selectRaw('SUM(unpaid_amount_in_base_currency) as total_unpaid')
            ->groupBy('participants.id')
            ->get();

        return $totalUnpaidAmount;
    }

    public static function getSettlements($groupId)
    {
        $totalUnpaidAmount = self::getTotalUnpaidAmount($groupId);
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
            $balance = $participant->total_unpaid - $averageUnpaid;

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
