<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

        // ...

    /**
     * Get the formatted created_at attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('F j, Y');
    }

    /**
     * Get the formatted updated_at attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('F j, Y');
    }
}
