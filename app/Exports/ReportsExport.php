<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportsExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $reports;

    // Nhận dữ liệu từ ExportController truyền sang
    public function __construct($reports)
    {
        $this->reports = $reports;
    }

    // Gắn giao diện HTML vào để render ra file Excel
    public function view(): View
    {
        return view('admin.exports.reports_excel', [
            'reports' => $this->reports
        ]);
    }

    // Định dạng in đậm cho dòng tiêu đề (Dòng số 1)
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}