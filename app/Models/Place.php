<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'description',
        'mark',
    ];

    public function comments()
    {
        return $this->hasMany(PlaceComment::class);
    }
}
