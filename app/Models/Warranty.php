<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warranty extends Model
{
    use HasFactory;

    // protected $guarded = [];
    protected $fillable =['warranty_count','warranty_duration'];
    
    public function warrantyable(){
        return $this->morphTo();
    }
}
