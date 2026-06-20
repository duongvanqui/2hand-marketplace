<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WithdrawalsExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $withdrawals;

    public function __construct($withdrawals)
    {
        $this->withdrawals = $withdrawals;
    }

    public function view(): View
    {
        return view('admin.exports.withdrawals_excel', [
            'withdrawals' => $this->withdrawals
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}