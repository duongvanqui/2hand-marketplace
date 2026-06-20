<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $users;

    public function __construct($users)
    {
        $this->users = $users;
    }

    public function collection()
    {
        return $this->users;
    }

    // Định nghĩa hàng tiêu đề
    public function headings(): array
    {
        return ['ID', 'Họ và Tên', 'Email', 'Số điện thoại', 'Vai trò', 'Trạng thái', 'Ngày đăng ký'];
    }

    // Định dạng từng dòng dữ liệu xuất ra
    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->phone ?? 'Chưa cập nhật',
            $user->role === 'admin' ? 'Quản trị viên' : 'Người dùng',
            $user->status == 1 ? 'Hoạt động' : 'Bị khóa',
            $user->created_at->format('d/m/Y H:i'),
        ];
    }

    // Thêm CSS (Style) cho file Excel
    public function styles(Worksheet $sheet)
    {
        return [
            // In đậm dòng đầu tiên (Tiêu đề cột)
            1 => ['font' => ['bold' => true]],
        ];
    }
}