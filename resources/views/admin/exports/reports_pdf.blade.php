<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Báo Cáo Vi Phạm - 2HAND</title>
    <style>
        /* BẮT BUỘC DÙNG FONT DEJAVU SANS ĐỂ KHÔNG LỖI TIẾNG VIỆT */
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #dc2626;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #dc2626; /* Màu đỏ cho cảnh báo vi phạm */
            margin: 0 0 5px 0;
            text-transform: uppercase;
            font-size: 20px;
        }
        .header p {
            margin: 0;
            color: #666;
            font-style: italic;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f8f9fa;
            color: #333;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .badge {
            padding: 3px 6px;
            font-weight: bold;
            font-size: 10px;
            border-radius: 4px;
        }
        .status-pending { color: #d97706; }
        .status-resolved { color: #059669; }
        .status-dismissed { color: #6b7280; }
        
        .product-name { font-weight: bold; color: #111; margin-bottom: 3px;}
        .meta-info { font-size: 10px; color: #777; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Danh Sách Báo Cáo Vi Phạm</h1>
        <p>
            @if(!empty($fromDate) && !empty($toDate))
                Từ ngày: {{ \Carbon\Carbon::parse($fromDate)->format('d/m/Y') }} - Đến ngày: {{ \Carbon\Carbon::parse($toDate)->format('d/m/Y') }}
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
                <th width="18%">Người báo cáo</th>
                <th width="22%">Sản phẩm bị báo cáo</th>
                <th width="20%">Lý do vi phạm</th>
                <th width="15%">Chi tiết</th>
                <th class="text-center" width="10%">Ngày gửi</th>
                <th class="text-center" width="10%">Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $index => $report)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    
                    {{-- 1. Người báo cáo --}}
                    <td>
                        <div class="product-name">{{ $report->user->name ?? 'Người dùng ẩn' }}</div>
                        <div class="meta-info">Email: {{ $report->user->email ?? 'N/A' }}</div>
                    </td>
                    
                    {{-- 2. Sản phẩm --}}
                    <td>
                        @if($report->product)
                            <div class="product-name">{{ $report->product->title }}</div>
                            <div class="meta-info">Mã SP: SP{{ str_pad($report->product->id, 5, '0', STR_PAD_LEFT) }}</div>
                            <div class="meta-info">Người bán: {{ $report->product->user->name ?? 'Ẩn danh' }}</div>
                        @else
                            <span style="color: #999; font-style: italic;">Sản phẩm đã bị xóa</span>
                        @endif
                    </td>
                    
                    {{-- 3. Lý do --}}
                    <td style="color: #dc2626; font-weight: bold; font-size: 11px;">
                        {{ $report->reason }}
                    </td>
                    
                    {{-- 4. Chi tiết bổ sung --}}
                    <td>
                        {{ $report->details ?? '-' }}
                    </td>
                    
                    {{-- 5. Ngày tạo --}}
                    <td class="text-center">
                        {{ $report->created_at->format('d/m/Y') }}<br>
                        <span class="meta-info">{{ $report->created_at->format('H:i') }}</span>
                    </td>
                    
                    {{-- 6. Trạng thái --}}
                    <td class="text-center">
                        @if($report->status == 'pending')
                            <span class="status-pending">Chờ xử lý</span>
                        @elseif($report->status == 'resolved')
                            <span class="status-resolved">Đã khóa SP</span>
                        @elseif($report->status == 'dismissed')
                            <span class="status-dismissed">Đã bỏ qua</span>
                        @else
                            {{ $report->status }}
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 30px;">
                        Hiện tại không có dữ liệu báo cáo nào trong khoảng thời gian này.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: right; font-size: 12px; border-top: 1px solid #ddd; padding-top: 10px;">
        <p><strong>Người xuất báo cáo:</strong> {{ auth()->user()->name ?? 'Admin' }}</p>
        <p><em>Hệ thống Quản trị 2HAND MARKET</em></p>
    </div>

</body>
</html>