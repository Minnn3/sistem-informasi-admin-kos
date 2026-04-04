<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $guarded = ['id'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function billings()
    {
        return $this->hasMany(Billing::class);
    }
}
