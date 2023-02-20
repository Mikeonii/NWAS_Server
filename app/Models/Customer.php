<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    protected $fillable = ['customer_name', 'customer_contact_no',
    'other_contact_platform', 'customer_municipality', 
    'customer_barangay', 'customer_purok', 'where_did_you_find_us'];
    use HasFactory;
    use SoftDeletes;

    public function units(){
        return $this->hasMany(Unit::class);
    }
    public function problems(){
        return $this->hasMany(Problem::class);
    }

    public function payments(){
        return $this->hasMany(Payment::class);
    }

    public function invoices(){
        return $this->hasMany(Invoice::class);
    }
}
