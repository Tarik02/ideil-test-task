<?php

namespace App\Models;

use App\Scopes\VisibleScope;
use App\Scopes\WeightScope;
use Illuminate\Database\Eloquent\Model;
use QCod\ImageUp\HasImageUploads;

class PlacePhoto extends Model
{
    use HasImageUploads;

    protected $fillable = [
        'weight',
        'visible',
    ];

    protected $visible = [
        'original',
        'preview',
    ];

    protected $appends = [
        'preview',
        'original',
    ];

    protected static $imageFields = [
        'preview' => [
            'height' => 200,
        ],
        'original',
    ];

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function getPreviewAttribute()
    {
        return $this->imageUrl('preview');
    }

    public function getOriginalAttribute()
    {
        return $this->imageUrl('original');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new WeightScope());
        static::addGlobalScope(new VisibleScope());

        static::deleted(function (PlacePhoto $photo) {
            dump($photo->preview);
            dump($photo->original);
//            $photo->deleteImage($photo->preview);
//            $photo->deleteImage($photo->original);
        });
    }
}
