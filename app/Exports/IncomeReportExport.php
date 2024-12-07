<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class IncomeReportExport implements FromView, ShouldAutoSize
{
    public function __construct(
        private Collection $report,
    ){
        //
    }

    public function view(): View
    {
        return view('exports.income-report', [
            'report' => $this->report,
        ]);
    }
}
