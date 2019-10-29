<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use QCod\ImageUp\HasImageUploads;

class PlacePhoto extends Model
{
    use HasImageUploads;

    protected $fillable = [
        'preview',
        'original',
        'order',
        'visible',
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
}
