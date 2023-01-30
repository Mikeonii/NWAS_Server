<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    public function warranty(){
        return $this->morphOne(Warranty::class,'warrantyable');
    }
    public function payment(){
        return $this->morphMany(Payment::class,'paymentable');
    }
}
