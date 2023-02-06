<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    public function items(){
        return $this->hasMany(Item::class);
    }
    public function services(){
        return $this->hasMany(Service::class);
    }
}
