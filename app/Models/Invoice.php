<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    
    public function payment(){
        return $this->hasMany(Payment::class);
    }
    public function payables(){
        return $this->hasMany(Payable::class);
    }
    public function quoteables(){
        return $this->hasMany(Quoteable::class);
    }
    public function customer(){
        return $this->belongsTo(Customer::class);
    }
}
