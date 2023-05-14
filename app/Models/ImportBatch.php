<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportBatch extends Model
{
    use HasFactory;

    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }
    public function items(){
        return $this->hasMany(Item::class);
    }

}
