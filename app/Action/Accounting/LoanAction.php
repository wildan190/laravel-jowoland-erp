<?php

namespace App\Action\Accounting;

use App\Models\Loan;

class LoanAction
{
    public function create(array $data): Loan
    {
        return Loan::create($data);
    }

    public function update(Loan $loan, array $data): Loan
    {
        $loan->update($data);

        return $loan;
    }

    public function delete(Loan $loan): bool
    {
        return $loan->delete();
    }
}
