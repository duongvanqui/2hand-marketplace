<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable = ['user_id', 'amount', 'bank_info', 'status'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}