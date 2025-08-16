<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanbanTask extends Model
{
    use HasFactory;

    protected $fillable = ['kanban_board_id', 'title', 'description', 'status']; // status: todo, doing, done

    public function board()
    {
        return $this->belongsTo(KanbanBoard::class);
    }
}
