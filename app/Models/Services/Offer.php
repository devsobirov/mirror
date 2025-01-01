<?php

namespace App\Models\Services;

use App\Models\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Offer extends Model
{
    protected $guarded = [];

    public function offerItems(): HasMany
    {
        return $this->hasMany(OfferItem::class, 'offer_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
