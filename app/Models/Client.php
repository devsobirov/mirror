<?php

namespace App\Models;

use App\Models\Finance\Debt;
use App\Models\Finance\Payment;
use App\Traits\HasPhoneNumbers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasPhoneNumbers;

    public $table = 'clients';
    public $timestamps = false;
    protected $guarded = false;

    public function debts(): HasMany
    {
        return $this->hasMany(Debt::class, 'client_id', 'id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'client_id', 'id');
    }

    public function withRating(Builder $query)
    {
        $query->withAvg('debts as rating', 'rating');
    }

    public function getRatingAttribute($value)
    {
        if (is_numeric($value)) {
            $value = round($value, 2);
        }
        
        return $value;
    }

    public function scopeSearch(Builder $query, $search = '') 
    {
        $search = trim($search);
        $query->when($search, fn($q) => $q->where('name', 'like', "%$search%")->orWhere('phone', 'like', "%$search%")->orWhere('phone2', 'like', "%$search%"));
    }
}
