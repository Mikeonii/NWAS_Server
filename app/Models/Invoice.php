<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Invoice extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    public function payments(){
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
