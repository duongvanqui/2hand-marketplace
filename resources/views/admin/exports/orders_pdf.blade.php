<!DOCTYPE html>
<html lang="vi">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Báo cáo Giao dịch</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1f2937; }
        .header { text-align: center; margin-bottom: 25px; }
        .header h2 { margin: 0; padding: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 5px 0 0 0; color: #6b7280; font-size: 12px; }
        .report-period { margin-top: 8px; font-size: 14px; font-weight: bold; color: #111827; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border-bottom: 1px solid #e5e7eb; padding: 8px 4px; text-align: left; }
        th { font-weight: bold; background: #f3f4f6; border-bottom: 2px solid #d1d5db; }
        .summary-container { margin-top: 30px; page-break-inside: avoid; }
        .summary-box { background-color: #f9fafb; padding: 15px; border-radius: 8px; border: 1px solid #e5e7eb; line-height: 1.6; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>BÁO CÁO DANH SÁCH GIAO DỊCH</h2>
        
        @if(!empty($fromDate) && !empty($toDate))
            <div class="report-period">
                Kỳ báo cáo: Từ ngày {{ \Carbon\Carbon::parse($fromDate)->format('d/m/Y') }} đến ngày {{ \Carbon\Carbon::parse($toDate)->format('d/m/Y') }}
            </div>
        @else
            <div class="report-period">
                Kỳ báo cáo: Tất cả thời gian
            </div>
        @endif
        
        <p>Thời gian trích xuất: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th width="10%">Mã Đơn</th>
                <th width="25%">Sản phẩm</th>
                <th width="15%">Người bán</th>
                <th width="15%">Người mua</th>
                <th width="12%">Tổng tiền</th>
                <th width="10%">Phí sàn</th>
                <th width="13%">Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            @php $totalFee = 0; @endphp
            @foreach($orders as $order)
                @php
                    $isFailed = in_array($order->status, ['cancelled', 'failed', 'refunded']);
                    $fee = $isFailed ? 0 : $order->fee_amount;
                    $totalFee += $fee;
                @endphp
            <tr>
                <td style="font-weight: bold;">#2H{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $order->product->title ?? 'Sản phẩm đã xóa' }}</td>
                <td>{{ $order->seller->name ?? 'Không rõ' }}</td>
                <td>{{ $order->receiver_name }}</td>
                <td style="font-weight: bold; color: #ef4444;">{{ number_format($order->total_amount) }}đ</td>
                <td style="font-weight: bold; color: #10b981;">{{ number_format($fee) }}đ</td>
                <td>
                    @if($order->status === 'completed') Hoàn tất
                    @elseif($isFailed) Đã hủy
                    @elseif(in_array($order->status, ['pending_shipping', 'paid_escrow'])) Chờ đóng gói
                    @elseif($order->status === 'shipped') Đang giao
                    @else {{ strtoupper($order->status) }}
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @php
        $totalOrders = $orders->count();
        $completed = $orders->where('status', 'completed')->count();
        $cancelled = $orders->whereIn('status', ['cancelled', 'failed', 'refunded'])->count();
    @endphp

    <div class="summary-container">
        <h3 style="margin-bottom: 10px;">Tổng kết doanh thu kỳ báo cáo</h3>
        <div class="summary-box">
            Tổng số giao dịch: <b>{{ number_format($totalOrders) }}</b> đơn<br>
            Số đơn hoàn tất thành công: <b style="color: #10b981;">{{ number_format($completed) }}</b> đơn<br>
            Số đơn thất bại/hủy (Không tính phí): <b style="color: #ef4444;">{{ number_format($cancelled) }}</b> đơn<br>
            <hr style="border: 0; border-top: 1px solid #d1d5db; margin: 10px 0;">
            <b style="font-size: 14px;">TỔNG DOANH THU PHÍ SÀN: <span style="color: #10b981;">{{ number_format($totalFee) }} VNĐ</span></b>
        </div>
    </div>
</body>
</html>