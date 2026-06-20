<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrdersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function collection()
    {
        return $this->orders;
    }

    public function headings(): array
    {
        return [
            'Mã Đơn', 
            'Sản phẩm', 
            'Người bán', 
            'Người mua', 
            'Tổng tiền (VNĐ)', 
            'Phí sàn (VNĐ)', 
            'Trạng thái', 
            'Ngày tạo'
        ];
    }

    public function map($order): array
    {
        // Chuyển đổi trạng thái sang Tiếng Việt
        $status = $order->status;
        if ($status === 'completed') $statusText = 'Hoàn tất';
        elseif (in_array($status, ['cancelled', 'failed', 'refunded'])) $statusText = 'Đã hủy/Thất bại';
        elseif (in_array($status, ['pending_shipping', 'paid_escrow'])) $statusText = 'Chờ đóng gói';
        elseif ($status === 'shipped') $statusText = 'Đang giao';
        else $statusText = strtoupper($status);

        // Đơn thất bại thì phí sàn = 0
        $fee = in_array($order->status, ['cancelled', 'failed', 'refunded']) ? 0 : $order->fee_amount;

        return [
            '#2H' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
            $order->product->title ?? 'Sản phẩm đã xóa',
            $order->seller->name ?? 'Không rõ',
            $order->receiver_name,
            $order->total_amount,
            $fee,
            $statusText,
            $order->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [ 1 => ['font' => ['bold' => true]] ];
    }
}