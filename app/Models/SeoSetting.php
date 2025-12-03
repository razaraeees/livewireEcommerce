<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoSetting extends Model
{
    protected $fillable = [
        'page_name',
        'page_url',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'meta_author',
        'meta_robots',
        'meta_image',
        'og_title',
        'og_description',
        'og_type',
        'og_url',
    ];


    
}
