<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoopRequest extends Model
{
    protected $fillable = [
        'user_id',
        'coop_name',
        'member_count',
        'status',
        'note',
    ];
}
