<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProfitsExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $profits;

    public function __construct($profits)
    {
        $this->profits = $profits;
    }

    public function view(): View
    {
        return view('admin.exports.profits_excel', [
            'profits' => $this->profits
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}