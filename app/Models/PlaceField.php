<?php

namespace App\Models;

use App\Scopes\WeightScope;
use Illuminate\Database\Eloquent\Model;

class PlaceField extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'key',
        'value',
        'weight',
    ];

    protected $visible = [
        'key',
        'value',
    ];

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new WeightScope());
    }
}
