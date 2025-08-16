<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingStrategy extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'generated_content'];
}
