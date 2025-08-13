<?php

namespace App\Action\App\Dashboard;

use App\Models\Income;
use App\Models\Loan;
use App\Models\Project;
use App\Models\Purchasing;

class GetAction
{
    public function execute(): array
    {
        // Statistik umum
        $totalIncome = Income::sum('amount');
        $totalPurchasing = Purchasing::sum('total_price');
        $loans = Loan::all();

        $totalLoan = $loans->sum(fn ($loan) => $loan->total_debt);
        $totalMonthlyInstallment = $loans->sum(fn ($loan) => $loan->monthly_installment);

        $projects = Project::with('tasks')->get();
        $totalProjects = $projects->count();
        $completedProjects = $projects->where('progress_percentage', 100)->count();
        $overdueProjects = $projects->where('is_overdue', true)->count();

        return [
            'stats' => [
                'total_income' => $totalIncome,
                'total_purchasing' => $totalPurchasing,
                'total_loan' => $totalLoan,
                'total_monthly_installment' => $totalMonthlyInstallment,
                'total_projects' => $totalProjects,
                'completed_projects' => $completedProjects,
                'overdue_projects' => $overdueProjects,
            ],
            'projects' => $projects,
        ];
    }
}
