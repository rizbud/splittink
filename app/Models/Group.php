<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'slug', 'currency_id'];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }
}
