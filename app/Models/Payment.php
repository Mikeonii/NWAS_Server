<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = ["customer_id","total_amount","payment_method","discount","payment_date"];

    public function paymentable(){
        return $this->morphTo();
    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }
}
