<?php

namespace App\Models\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfferItem extends Model
{
    public $timestamps = false;
    protected $guarded = [];
    protected $with = ['subItems'];

    public function subItems()
    {
        return $this->hasMany(self::class, 'parent_item_id');
    }

    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class, 'offer_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id')->with('category');
    }

    public function getMemoAttribute()
    {
        $unit = $this->service?->unit ?? '';
        $dm = $this->dimension_amount ?: 1;

        $memo = "$dm $unit";
        if ($this->x || $this->y) {
            $x = $this->x ?: '?';
            $y = $this->y ?: '?';

            $memo = "$x sm x $y sm = $memo";
        }

        return $memo;
    }
}
