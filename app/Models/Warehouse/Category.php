<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function goods(): HasMany
    {
        return $this->hasMany(Good::class);
    }
}
