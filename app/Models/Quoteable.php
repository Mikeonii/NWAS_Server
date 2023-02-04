<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quoteable extends Model
{
    use HasFactory;
    protected $fillable = ["invoice_id","quantity","amount"];

    public function quoteable(){
        return $this->morphTo();
    }
    public function quoteables(){
        return $this->belongsTo(Invoice::class);
    }
}
