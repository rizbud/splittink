<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_id',
        'participant_id',
        'paid_amount',
        'paid_amount_in_base_currency',
        'unpaid_amount',
        'unpaid_amount_in_base_currency',
    ];

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }
}
