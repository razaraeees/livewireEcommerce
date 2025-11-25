<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariantValue extends Model
{
    
    
    protected $fillable = [
        'variants_id',
        'value',
        'slug',
        'status',
    ];

    public function option()
    {
        return $this->belongsTo(VariantValue::class, 'variants_id');
    }


}
