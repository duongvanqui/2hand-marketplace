<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Báo Cáo Lợi Nhuận Sàn - 2HAND</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #333; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #059669; padding-bottom: 15px; }
        .header h1 { color: #059669; margin: 0 0 5px 0; text-transform: uppercase; font-size: 20px; }
        .header p { margin: 0; color: #666; font-style: italic; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px 10px; text-align: left; vertical-align: middle; }
        th { background-color: #f8f9fa; color: #333; font-weight: bold; text-transform: uppercase; font-size: 11px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .money { color: #059669; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Báo Cáo Doanh Thu Phí Sàn</h1>
        <p>
            @if(!empty($fromDate) && !empty($toDate))
                Từ: {{ \Carbon\Carbon::parse($fromDate)->format('d/m/Y') }} - Đến: {{ \Carbon\Carbon::parse($toDate)->format('d/m/Y') }}
            @else
                Dữ liệu: Tất cả thời gian
            @endif
        </p>
        <p>Ngày trích xuất: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="5%">STT</th>
                <th width="15%">Mã Đơn</th>
                <th width="35%">Sản Phẩm</th>
                <th width="15%">Người Bán</th>
                <th class="text-center" width="15%">Hoàn Thành</th>
                <th class="text-right" width="15%">Phí Thu Được</th>
            </tr>
        </thead>
        <tbody>
            @php $totalFee = 0; @endphp
            @forelse($profits as $index => $profit)
                @php $totalFee += $profit->fee_amount; @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>DH{{ str_pad($profit->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td><strong style="color:#111;">{{ $profit->product->title ?? 'Sản phẩm đã xóa' }}</strong></td>
                    <td>{{ $profit->seller->name ?? 'N/A' }}</td>
                    <td class="text-center">{{ $profit->updated_at->format('d/m/Y') }}<br><span style="font-size:10px; color:#777;">{{ $profit->updated_at->format('H:i') }}</span></td>
                    <td class="text-right money">+{{ number_format($profit->fee_amount) }}đ</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center" style="padding: 20px;">Không có dữ liệu lợi nhuận trong khoảng thời gian này.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right" style="font-weight: bold; background-color: #f8f9fa;">TỔNG CỘNG LỢI NHUẬN:</td>
                <td class="text-right" style="font-weight: bold; color: #059669; font-size: 14px; background-color: #ecfdf5;">+{{ number_format($totalFee) }}đ</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>