<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FbAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'facebook_id', 'name', 'access_token', 'token_expired_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pages()
    {
        return $this->hasMany(FbPage::class);
    }
}
