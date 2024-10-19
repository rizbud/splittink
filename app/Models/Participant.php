<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'group_id'];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function bills()
{
    return $this->belongsToMany(Bill::class, 'bill_participants')
                ->withPivot('paid_amount', 'paid_amount_in_base_currency')
                ->withTimestamps();
}
}
