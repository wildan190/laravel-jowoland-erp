<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdsPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'objective',
        'audience',
        'budget',
        'start_date',
        'end_date',
        'platform',
        'notes',
        'created_by',
    ];
}
