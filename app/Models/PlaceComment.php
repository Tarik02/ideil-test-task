<?php

namespace App\Models;

use App\Scopes\CreatedAtScope;
use App\Scopes\VisibleScope;
use Illuminate\Database\Eloquent\Model;

class PlaceComment extends Model
{
    protected $fillable = [
        'text',
    ];

    protected $visible = [
        'id',
        'text',
        'created_at',
        'updated_at',
        'author',
    ];

    protected $with = [
        'author',
    ];

    protected $casts = [
        'visible' => 'bool',
    ];

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CreatedAtScope());
        static::addGlobalScope(new VisibleScope());
    }
}
