<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageContent extends Model
{
    
    protected $fillable = [
        'type',
        'title',
        'slug',
        'content',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'status' => 'boolean',
    ];
}
