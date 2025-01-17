<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaypalSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'client_secret',
        'mode',
        'currency',
        'payment_action',
        'notify_url',
        'locale',
        'validate_ssl',
        'status',
    ];
    protected $casts = [
        'status' => 'boolean',
        'validate_ssl' => 'boolean',
    ];
}
