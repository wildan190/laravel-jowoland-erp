<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MindNode extends Model
{
    use HasFactory;

    protected $fillable = ['mind_map_id', 'parent_id', 'title', 'content'];

    public function mindMap()
    {
        return $this->belongsTo(MindMap::class);
    }

    public function parent()
    {
        return $this->belongsTo(MindNode::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MindNode::class, 'parent_id');
    }
}
