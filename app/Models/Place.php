<?php

namespace App\Models;

use App\Scopes\CreatedAtScope;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Place extends Model implements HasMedia
{
    use HasMediaTrait;

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
        'likes_count',
        'dislikes_count',
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

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('preview')
            ->width(400)
            ->performOnCollections('photos')
        ;
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CreatedAtScope());
    }
}
