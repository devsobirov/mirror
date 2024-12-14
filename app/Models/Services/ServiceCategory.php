<?php

namespace App\Models\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceCategory extends Model
{
    public $table = 'service_categories';
    public $timestamps = false;
    protected $guarded = [];

    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'category_id');
    }
}
