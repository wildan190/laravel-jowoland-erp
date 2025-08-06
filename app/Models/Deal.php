<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_id',
        'pipeline_stage_id',
        'title',
        'value',
        'status',
        'notes',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function stage()
    {
        return $this->belongsTo(PipelineStage::class, 'pipeline_stage_id');
    }
}
