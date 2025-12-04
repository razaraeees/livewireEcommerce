<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'favicon',
        'footer_logo',
        'website_logo',
        'admin_logo',
    ];


}
