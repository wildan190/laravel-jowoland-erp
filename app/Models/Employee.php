<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'division_id', 'position', 'salary'];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }
}
