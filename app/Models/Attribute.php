<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'status',
    ];

    public function attributeValue(){

        return $this->hasMany(AttributeValue::class, 'attribute_id');
    }
}
