<?php

namespace App\Exports;

use App\Models\Warehouse\Warehouse;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BalanceReportExport implements FromView, ShouldAutoSize
{
    public function __construct(
        private Collection $report,
        private Collection $warehouses,
        private ?Warehouse $warehouse = null
    ){
        //
    }

    public function view(): View
    {
        return view('exports.balance-report', [
            'report' => $this->report,
            'warehouses' => $this->warehouses,
            'warehouse' => $this->warehouse
        ]);
    }
}
