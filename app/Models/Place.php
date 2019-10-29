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

    protected $visible = [
        'id',
        'slug',
        'name',
        'description',
        'mark',
        'comments',
        'fields',
        'photos',
        'defaultPhoto',
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

    public function photos()
    {
        return $this->hasMany(PlacePhoto::class)->where('visible', true);
    }

    public function defaultPhoto()
    {
        return $this->hasOne(PlacePhoto::class, 'id', 'default_photo_id');
    }
}
