<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id','unit_type','unit_brand',
        'unit_model','serial_no','date_received'
    ];
}
