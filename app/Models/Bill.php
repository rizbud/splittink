<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'currency_id',
        'amount',
        'amount_in_base_currency',
        'group_id',
        'splitting_method',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function participants()
{
    return $this->belongsToMany(Participant::class, 'bill_participants')
                ->withPivot('paid_amount', 'paid_amount_in_base_currency')
                ->withTimestamps();
}
}
