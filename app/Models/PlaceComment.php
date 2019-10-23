<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlaceComment extends Model
{
    protected $fillable = [
        'text',
    ];

    public function place()
    {
        return $this->belongsTo(Place::class);
    }
}
