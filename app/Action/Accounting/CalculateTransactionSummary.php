<?php

namespace App\Action\Accounting;

use App\Models\Transaction;

class CalculateTransactionSummary
{
    public function execute(): array
    {
        $income = Transaction::where('type', 'income')->sum('amount');
        $expense = Transaction::where('type', 'expense')->sum('amount');

        return [
            'income' => $income,
            'expense' => $expense,
            'balance' => $income - $expense
        ];
    }
}
