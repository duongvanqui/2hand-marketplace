<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; color: #333; }
        .header { text-align: center; margin-bottom: 30px; }
        .title { font-size: 24px; font-weight: bold; color: #2563eb; }
        .stats-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .stats-table td { padding: 15px; border: 1px solid #eee; width: 33.33%; }
        .label { font-size: 10px; color: #888; text-transform: uppercase; }
        .value { font-size: 18px; font-weight: bold; display: block; margin-top: 5px; }
        h2 { font-size: 16px; border-left: 4px solid #2563eb; padding-left: 10px; margin-top: 30px; }
        table.details { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.details th, table.details td { text-align: left; padding: 10px; border-bottom: 1px solid #eee; font-size: 12px; }
        table.details th { background: #f9fafb; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">BÁO CÁO TỔNG QUAN HỆ THỐNG 2HAND</div>
        <p>Thời gian báo cáo: {{ $days }} ngày qua | Ngày xuất: {{ date('d/m/Y H:i') }}</p>
    </div>

    <h2>1. Chỉ số quan trọng</h2>
    <table class="stats-table">
        <tr>
            <td><span class="label">Tổng doanh thu</span><span class="value">{{ number_format($totalRevenue) }}đ</span></td>
            <td><span class="label">Tổng đơn hàng</span><span class="value">{{ number_format($totalOrders) }}</span></td>
            <td><span class="label">Tổng người dùng</span><span class="value">{{ number_format($totalUsers) }}</span></td>
        </tr>
        <tr>
            <td><span class="label">Sản phẩm hiện có</span><span class="value">{{ number_format($totalProducts) }}</span></td>
            <td><span class="label">SP Chờ duyệt</span><span class="value">{{ number_format($pendingProducts) }}</span></td>
            <td><span class="label">Vi phạm mới</span><span class="value">{{ number_format($unresolvedReports) }}</span></td>
        </tr>
    </table>

    <h2>2. Hoạt động gần đây</h2>
    <table class="details">
        <thead>
            <tr>
                <th>Mã/Tên</th>
                <th>Người liên quan</th>
                <th>Giá trị/Lý do</th>
                <th>Thời gian</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentOrders as $o)
            <tr>
                <td>Đơn hàng #{{ $o->id }}</td>
                <td>{{ $o->buyer->name }}</td>
                <td>{{ number_format($o->total_amount) }}đ</td>
                <td>{{ $o->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
            @foreach($recentReports as $r)
            <tr>
                <td>Báo cáo vi phạm</td>
                <td>{{ $r->user->name }}</td>
                <td>{{ Str::limit($r->reason, 30) }}</td>
                <td>{{ $r->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 50px; font-size: 10px; text-align: center; color: #aaa;">
        Báo cáo này được tạo tự động từ hệ thống quản trị 2HAND MARKET.
    </div>
</body>
</html>