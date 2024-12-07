<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExpenseReportExport implements FromView, ShouldAutoSize
{
    public function __construct(
        private Collection $report,
    ){
        //
    }

    public function view(): \Illuminate\Contracts\View\View
    {
        return view('exports.expense-report', [
            'report' => $this->report
        ]);
    }
}
