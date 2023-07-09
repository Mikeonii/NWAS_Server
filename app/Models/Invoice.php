<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
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

       // automatically formats the updated_at and created_at columns in invoice model. 
       public function getCreatedAtAttribute($value)
       {
           return Carbon::parse($value)->format('F j, Y');
       }
   
   
       public function getUpdatedAtAttribute($value)
       {
           return Carbon::parse($value)->format('F j, Y');
       }
}
