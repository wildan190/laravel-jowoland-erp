<?php

// app/Models/Project.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['name', 'location', 'description', 'start_date', 'end_date'];

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

    // app/Models/Project.php
    protected $appends = ['is_overdue'];

    public function getIsOverdueAttribute()
    {
        if (! $this->end_date) {
            return false;
        }

        return now()->gt(\Carbon\Carbon::parse($this->end_date)) && $this->progress_percentage < 100;
    }
}
