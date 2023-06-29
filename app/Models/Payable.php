<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payable extends Model
{
    use HasFactory;
    protected $fillable = ["invoice_id","quantity","amount"];

    public function payable(){
        return $this->morphTo();
    }
    public function payables(){
        return $this->belongsTo(Invoice::class);
    }
    public function invoice(){
        return $this->belongsTo(Invoice::class);
    }
    public function item()
    {
        return $this->belongsTo(Item::class, 'payable_id');
    }
    public function service()
    {
        return $this->belongsTo(Service::class, 'payable_id');
    }
}
