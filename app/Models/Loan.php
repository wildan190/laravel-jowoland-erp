<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'vendor',
        'principal',
        'interest_rate',
        'installments',
        'start_date',
        'end_date',
        'due_date',
        'description',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'due_date' => 'date',
        'interest_rate' => 'float',
        'principal' => 'float',
    ];

    // Total hutang termasuk bunga
    public function getTotalDebtAttribute()
    {
        return $this->principal + ($this->principal * $this->interest_rate / 100);
    }

    // Cicilan per bulan
    public function getMonthlyInstallmentAttribute()
    {
        if ($this->installments > 0) {
            return $this->total_debt / $this->installments;
        }

        return 0;
    }

    // Set start_date dan otomatis hitung end_date
    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = $value;

        if (! empty($value) && ! empty($this->installments)) {
            $start = Carbon::parse($value);
            $this->attributes['end_date'] = $start->copy()->addMonths($this->installments)->subDay();
        }
    }
}
