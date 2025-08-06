<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectTask extends Model
{
    protected $fillable = ['project_id', 'task_name', 'is_done'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
