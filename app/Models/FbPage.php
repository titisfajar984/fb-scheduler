<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FbPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'fb_account_id', 'page_id', 'page_name', 'page_access_token'
    ];

    public function account()
    {
        return $this->belongsTo(FbAccount::class, 'fb_account_id');
    }

    public function scheduledPosts()
    {
        return $this->hasMany(ScheduledPost::class);
    }
}
