<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMediaPost extends Model
{
    use HasFactory;

    protected $fillable = ['platform', 'content', 'scheduled_at', 'generated_content'];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];
}
