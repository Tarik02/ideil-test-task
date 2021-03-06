<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlaceLike extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'value',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function isLike()
    {
        return $this->value > 0;
    }

    public function isDislike()
    {
        return $this->value < 0;
    }
}
