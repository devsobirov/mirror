<?php

namespace App\Models;

use App\Traits\HasPhoneNumbers;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasPhoneNumbers;

    public $table = 'clients';
    public $timestamps = false;

    protected $guarded = false;
}
