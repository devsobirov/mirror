<?php

namespace App\Models\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    public $table = 'services';
    protected $guarded = [];
    public const IMAGE_PATH = 'assets/images/services';
    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id', 'id');
    }

    public function scopeSearch(Builder $query, ?string $search = '')
    {
        $query->when(!empty($search), fn($q) => $q->where('name', 'like', '%' . mb_strtolower(trim($search)) . '%'));
    }

    public function scopeByCategory(Builder $query, ?int $category_id = null)
    {
        $query->when(!empty($category_id), fn($q) => $q->where('category_id', $category_id));
    }

    public function getFullTitleAttribute(): string
    {
        return ($this->category?->title ?? 'Xar-xil') . ' / ' . $this->name;
    }

    public function getImageUrlAttribute(): string
    {
        return asset($this->image ?: 'assets/images/no-image.jpg');
    }
}
