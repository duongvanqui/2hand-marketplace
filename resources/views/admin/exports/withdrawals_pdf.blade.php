<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Báo Cáo Giải Ngân - 2HAND</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #333; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #2563eb; padding-bottom: 15px; }
        .header h1 { color: #2563eb; margin: 0 0 5px 0; text-transform: uppercase; font-size: 20px; }
        .header p { margin: 0; color: #666; font-style: italic; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px 10px; text-align: left; vertical-align: top; }
        th { background-color: #f8f9fa; color: #333; font-weight: bold; text-transform: uppercase; font-size: 11px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .status-pending { color: #d97706; font-weight: bold; }
        .status-approved { color: #059669; font-weight: bold; }
        .status-rejected { color: #dc2626; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Báo Cáo Yêu Cầu Rút Tiền (Giải Ngân)</h1>
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
                <th width="20%">Người Yêu Cầu</th>
                <th width="35%">Thông Tin Ngân Hàng</th>
                <th class="text-center" width="15%">Cập Nhật Cuối</th>
                <th class="text-center" width="10%">Trạng Thái</th>
                <th class="text-right" width="15%">Số Tiền</th>
            </tr>
        </thead>
        <tbody>
            @php $totalOut = 0; @endphp
            @forelse($withdrawals as $index => $w)
                @if($w->status == 'approved') @php $totalOut += $w->amount; @endphp @endif
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong style="color:#111;">{{ $w->user->name ?? 'N/A' }}</strong><br>
                        <span style="font-size:10px; color:#777;">{{ $w->user->email ?? '' }}</span>
                    </td>
                    <td style="white-space: pre-line;">{{ $w->bank_info }}</td>
                    <td class="text-center">{{ $w->updated_at->format('d/m/Y') }}<br><span style="font-size:10px; color:#777;">{{ $w->updated_at->format('H:i') }}</span></td>
                    <td class="text-center">
                        @if($w->status == 'approved') <span class="status-approved">Đã duyệt</span>
                        @elseif($w->status == 'rejected') <span class="status-rejected">Từ chối</span>
                        @else <span class="status-pending">Chờ xử lý</span>
                        @endif
                    </td>
                    <td class="text-right" style="font-weight:bold; color: {{ $w->status == 'approved' ? '#059669' : ($w->status == 'rejected' ? '#dc2626' : '#d97706') }};">
                        {{ number_format($w->amount) }}đ
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center" style="padding: 20px;">Không có dữ liệu yêu cầu rút tiền.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right" style="font-weight: bold; background-color: #f8f9fa;">TỔNG TIỀN ĐÃ DUYỆT CHI:</td>
                <td class="text-right" style="font-weight: bold; color: #059669; font-size: 14px; background-color: #ecfdf5;">{{ number_format($totalOut) }}đ</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>