<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'title',
        'url',
        'password',
        'user_id',
    ];

    /*
    * Function to get the user who own the accounts
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
