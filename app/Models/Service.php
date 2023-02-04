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
    public function payable(){
        return $this->morphMany(Payable::class,'payable');
    }
    public function quoteable(){
        return $this->morphMany(Quoteable::class,'quoteable');
    }

}
