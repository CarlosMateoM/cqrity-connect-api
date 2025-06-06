<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessLog extends Model
{
    protected $fillable = [
        'user_id',
        'method',
        'image',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
