<?php

// app/Models/Project.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['name', 'location', 'description'];

    public function tasks()
    {
        return $this->hasMany(ProjectTask::class);
    }

    public function getProgressPercentageAttribute()
    {
        $total = $this->tasks()->count();
        $done = $this->tasks()->where('is_done', true)->count();

        return $total > 0 ? round(($done / $total) * 100) : 0;
    }
}
