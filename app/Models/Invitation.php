<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'invited_email',
        'invitation_token',
        'inviter_email',
        'token_was_used',
        'date_token_was_used',
        'invitation_expiration_date'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'invitation_token', 'invitation_token');
    }
}
