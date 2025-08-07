<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = ['employee_id', 'pay_date', 'basic_salary', 'allowance', 'notes'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    protected $appends = ['total'];

    public function getTotalAttribute()
    {
        $basic = $this->employee?->salary ?? 0;
        $allowance = $this->allowance ?? 0;

        return $basic + $allowance;
    }
}
