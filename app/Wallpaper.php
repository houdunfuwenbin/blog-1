<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallpaper extends Model
{
    protected $table = 'wallpapers';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'view', 'heart', 'download', 'url', 'title', 'content', 'calendar', 'location', 'calendar'
    ];
}
