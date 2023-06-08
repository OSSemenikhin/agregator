<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class City extends Model
{
    public const START_CITY = 1;

    protected $fillable = ['name'];
    public $timestamps = false;

    public function scopeGetAll(Builder $query, array $order = ['by' => 'name', 'sort'=>'desc'])
    {
        return $query->orderBy($order['by'], $order['sort']);
    }

    public function scopeGetById(Builder $query, int|string $id): City|Builder
    {
        if ($id) return $query->where('id', +$id)->first();
        return $query;
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Region::class);
    }

    public function municipals(): HasMany
    {
        return $this->HasMany(\App\Models\Municipal::class);
    }

    public function shops(): HasMany
    {
        return $this->hasMany(\App\Models\Shop::class);
    }

    public function areas(): HasMany
    {
        return $this->hasMany(\App\Models\Area::class);
    }

    public function subways(): HasMany
    {
        return $this->hasMany(\App\Models\Subway::class);
    }
}


