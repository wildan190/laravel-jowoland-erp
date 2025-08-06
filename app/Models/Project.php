<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = ['name', 'location', 'description'];

    public function progresses(): HasMany
    {
        return $this->hasMany(ProjectProgress::class);
    }
}
