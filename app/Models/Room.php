<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $guarded = ['id'];

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }

    public function billings()
    {
        return $this->hasMany(Billing::class);
    }
}
