<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlaceField extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'key',
        'value',
    ];

    public function place()
    {
        return $this->belongsTo(Place::class);
    }
}
