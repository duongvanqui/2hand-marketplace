<!DOCTYPE html>
<html lang="vi">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Báo cáo Sản phẩm</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; }
        .header { text-align: center; margin-bottom: 25px; }
        .header h2 { margin: 0; padding: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 5px 0 0 0; color: #6b7280; font-size: 12px; }
        .report-period { margin-top: 8px; font-size: 14px; font-weight: bold; color: #111827; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border-bottom: 1px solid #e5e7eb; padding: 10px 5px; text-align: left; }
        th { font-weight: bold; background: #f3f4f6; border-bottom: 2px solid #d1d5db; }
        .summary-container { margin-top: 30px; page-break-inside: avoid; }
        .summary-box { background-color: #f9fafb; padding: 15px; border-radius: 8px; border: 1px solid #e5e7eb; line-height: 1.6; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>BÁO CÁO DANH SÁCH SẢN PHẨM</h2>
        
        {{-- NƠI HIỂN THỊ TỪ NGÀY ĐẾN NGÀY --}}
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
                <th width="5%">ID</th>
                <th width="30%">Tên sản phẩm</th>
                <th width="15%">Danh mục</th>
                <th width="15%">Người bán</th>
                <th width="15%">Giá</th>
                <th width="10%">Trạng thái</th>
                <th width="10%">Ngày đăng</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->title }}</td>
                <td>{{ $product->category->name ?? 'N/A' }}</td>
                <td>{{ $product->user->name ?? 'N/A' }}</td>
                <td>{{ number_format($product->price) }}đ</td>
                <td>
                    @if($product->status === 'approved') Đang bán
                    @elseif($product->status === 'pending') Chờ duyệt
                    @elseif($product->status === 'rejected') Bị từ chối
                    @elseif($product->status === 'sold') Đã bán
                    @elseif($product->status === 'hidden') Đã ẩn
                    @else {{ strtoupper($product->status) }}
                    @endif
                </td>
                <td>{{ $product->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @php
        $total = $products->count();
        $approved = $products->where('status', 'approved')->count();
        $pending = $products->where('status', 'pending')->count();
        $rejected = $products->where('status', 'rejected')->count();
    @endphp

    <div class="summary-container">
        <h3 style="margin-bottom: 10px;">Tổng kết dữ liệu</h3>
        <div class="summary-box">
            Tổng sản phẩm trong kỳ báo cáo: <b>{{ number_format($total) }}</b><br>
            Số lượng đang bán (Approved): <b>{{ number_format($approved) }}</b><br>
            Số lượng chờ duyệt (Pending): <b>{{ number_format($pending) }}</b><br>
            Số lượng vi phạm (Rejected): <b>{{ number_format($rejected) }}</b>
        </div>
    </div>
</body>
</html>