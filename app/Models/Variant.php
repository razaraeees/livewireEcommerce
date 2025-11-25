<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'status',
    ];

    public function variantValues()
    {
        return $this->hasMany(VariantValue::class, 'variants_id');
    }
    

}
