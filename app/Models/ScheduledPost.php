<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'fb_page_id', 'user_id', 'scheduled_time', 'caption', 
        'image_url', 'video_url', 'link_url', 'status', 
        'posted_at', 'error_message'
    ];

    protected $casts = [
        'scheduled_time' => 'datetime',
        'posted_at' => 'datetime',
    ];

    public function page()
    {
        return $this->belongsTo(FbPage::class, 'fb_page_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
