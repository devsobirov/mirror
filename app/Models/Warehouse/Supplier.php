<?php

namespace App\Models\Warehouse;

use App\Traits\HasPhoneNumbers;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasPhoneNumbers;

    public $timestamps = false;
    protected $guarded = [];

    public const ACTIVE = 1;
    public const INACTIVE = 0;
}
