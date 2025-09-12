<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrokerAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'platform',
        'server',
        'login',
        'encrypted_password',
        'label',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}




