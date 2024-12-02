<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;
    protected $table = "contact_messages";
    protected $guarded = [];

    const STATUS_SEEN = 1;
    const STATUS_UNSEEN = 0;
}
