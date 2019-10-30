<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use QCod\ImageUp\HasImageUploads;

class PlacePhoto extends Model
{
    use HasImageUploads;

    protected $fillable = [
        'order',
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
}
