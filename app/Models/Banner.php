<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'image',
        'banner_video',
        'banner_video_status',
        'type',
        'description',
        'link',
        'tagline',
        'title',
        'alt',
        'status',
        'mob_banner_image',
    ];


}
