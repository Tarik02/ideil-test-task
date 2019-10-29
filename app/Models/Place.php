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

    public function fields()
    {
        return $this->hasMany(PlaceField::class);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class)->using(PlaceLike::class);
    }
}
