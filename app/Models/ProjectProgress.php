<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectProgress extends Model
{
    protected $fillable = ['project_id', 'stage', 'percentage', 'note'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
