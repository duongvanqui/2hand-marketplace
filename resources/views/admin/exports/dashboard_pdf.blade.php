<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Báo Cáo Tổng Quan - 2HAND MARKET</title>
    <style>
        /* Typography & Căn lề chuẩn */
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #334155; margin: 0; padding: 10px 20px; line-height: 1.6; }
        table { width: 100%; border-collapse: collapse; }
        
        /* HEADER: Gọn gàng, sang trọng */
        .header { margin-bottom: 40px; border-bottom: 2px solid #e2e8f0; padding-bottom: 15px; }
        .header td { vertical-align: middle; }
        .logo { font-size: 26px; font-weight: 900; color: #2563eb; letter-spacing: 1px; margin-bottom: 5px; }
        .doc-title { font-size: 18px; font-weight: bold; color: #0f172a; text-transform: uppercase; text-align: right; }
        .meta-info { font-size: 11px; color: #64748b; text-align: right; margin-top: 5px; }

        /* SECTION TITLE: Tách biệt nội dung */
        .section-title { font-size: 13px; font-weight: bold; color: #1e293b; margin: 30px 0 15px 0; text-transform: uppercase; padding-bottom: 5px; border-bottom: 2px solid #3b82f6; display: inline-block; }

        /* KHỐI HIGHLIGHT (CARDS): Dùng nền thay vì viền */
        .cards-wrapper { width: 100%; margin-bottom: 20px; table-layout: fixed; }
        .cards-wrapper td { padding: 0 10px; }
        .card { background-color: #f8fafc; padding: 20px 15px; border-radius: 8px; text-align: center; }
        .card-title { font-size: 10px; color: #64748b; text-transform: uppercase; font-weight: bold; margin-bottom: 10px; }
        .card-value { font-size: 24px; font-weight: 900; color: #0f172a; }
        
        /* Màu sắc số liệu */
        .text-blue { color: #2563eb; }
        .text-green { color: #10b981; }
        .text-red { color: #ef4444; }
        .text-purple { color: #8b5cf6; }

        /* BẢNG DỮ LIỆU: Hiện đại, Zebra-striping (Sọc ngựa vằn) */
        .data-table { margin-bottom: 30px; }
        .data-table th, .data-table td { padding: 12px; text-align: left; font-size: 11px; border-bottom: 1px solid #e2e8f0; }
        .data-table th { background-color: #f1f5f9; color: #475569; font-weight: bold; text-transform: uppercase; border-top: 1px solid #e2e8f0; }
        .data-table tbody tr:nth-child(even) { background-color: #f8fafc; }
        
        .text-center { text-align: center !important; }
        .text-right { text-align: right !important; }
        
        .total-row td { font-weight: bold; color: #0f172a; background-color: #f1f5f9; border-top: 2px solid #cbd5e1; border-bottom: 2px solid #cbd5e1; }

        /* Badge trạng thái */
        .badge { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 9px; font-weight: bold; }
        .badge-red { background-color: #fee2e2; color: #b91c1c; }
        .badge-green { background-color: #d1fae5; color: #047857; }

        /* FOOTER */
        .footer { margin-top: 50px; text-align: center; font-size: 10px; color: #94a3b8; border-top: 1px dashed #cbd5e1; padding-top: 15px; }
    </style>
</head>
<body>

    <table class="header">
        <tr>
            <td style="width: 50%;">
                <div class="logo">2HAND MARKET</div>
                <div style="font-size: 11px; color: #64748b;">Nền tảng Giao dịch Đồ cũ An toàn</div>
            </td>
            <td style="width: 50%;">
                <div class="doc-title">Báo Cáo Tổng Quan</div>
                <div class="meta-info"><b>Kỳ báo cáo:</b> {{ $days }} ngày gần nhất</div>
                <div class="meta-info"><b>Ngày trích xuất:</b> {{ \Carbon\Carbon::now()->format('d/m/Y - H:i') }}</div>
                <div class="meta-info"><b>Nhân sự:</b> {{ Auth::user()->name ?? 'Admin' }}</div>
            </td>
        </tr>
    </table>

    <div class="section-title">1. Hiệu Quả Kinh Doanh</div>
    <table class="cards-wrapper">
        <tr>
            <td>
                <div class="card">
                    <div class="card-title">Doanh Thu Phí Sàn</div>
                    <div class="card-value text-blue">{{ number_format($totalRevenue) }}đ</div>
                </div>
            </td>
            <td>
                <div class="card">
                    <div class="card-title">Tổng Giao Dịch</div>
                    <div class="card-value text-green">{{ number_format($totalOrders) }}</div>
                </div>
            </td>
            <td>
                <div class="card">
                    <div class="card-title">Tổng Người Dùng</div>
                    <div class="card-value text-purple">{{ number_format($totalUsers) }}</div>
                </div>
            </td>
        </tr>
    </table>

    <div class="section-title">2. Tình Trạng Vận Hành</div>
    <table class="cards-wrapper">
        <tr>
            <td>
                <div class="card">
                    <div class="card-title">Sản Phẩm Đang Mở</div>
                    <div class="card-value">{{ number_format($totalProducts) }}</div>
                </div>
            </td>
            <td>
                <div class="card">
                    <div class="card-title">Sản Phẩm Chờ Duyệt</div>
                    <div class="card-value text-blue">{{ number_format($pendingProducts) }}</div>
                </div>
            </td>
            <td>
                <div class="card">
                    <div class="card-title">Vi Phạm Chờ Xử Lý</div>
                    <div class="card-value text-red">{{ number_format($unresolvedReports) }}</div>
                </div>
            </td>
        </tr>
    </table>

    <div class="section-title">3. Lưu Lượng Chi Tiết ({{ $days }} ngày qua)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 25%;">Thời gian (Ngày/Tháng)</th>
                <th class="text-center" style="width: 25%;">Sản phẩm mới</th>
                <th class="text-center" style="width: 25%;">Đơn hàng thành công</th>
                <th class="text-right" style="width: 25%;">Doanh thu sàn</th>
            </tr>
        </thead>
        <tbody>
            @foreach($chartLabels as $index => $label)
                @if($ordersData[$index] > 0 || $newUsersData[$index] > 0 || $revenueData[$index] > 0)
                <tr>
                    <td class="text-center font-bold">{{ $label }}</td>
                    <td class="text-center">+{{ number_format($newUsersData[$index]) }}</td>
                    <td class="text-center">{{ number_format($ordersData[$index]) }}</td>
                    <td class="text-right text-blue">{{ number_format($revenueData[$index]) }} đ</td>
                </tr>
                @endif
            @endforeach
            
            <tr class="total-row">
                <td class="text-center">TỔNG TRONG KỲ</td>
                <td class="text-center">+{{ number_format($chartTotalProducts) }}</td>
                <td class="text-center">{{ number_format($chartTotalOrders) }}</td>
                <td class="text-right">{{ number_format($chartTotalRevenue) }} đ</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title" style="border-color: #ef4444; color: #ef4444;">4. Vi Phạm Cần Xử Lý Gấp</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 15%;">Mã SP</th>
                <th style="width: 30%;">Người bị báo cáo</th>
                <th style="width: 40%;">Lý do vi phạm</th>
                <th class="text-center" style="width: 15%;">Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentReports as $report)
            <tr>
                <td class="font-bold">SP{{ str_pad($report->product_id, 5, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $report->product->user->name ?? 'Đã xóa tài khoản' }}</td>
                <td>{{ Str::limit($report->reason, 50) }}</td>
                <td class="text-center"><span class="badge badge-red">CHỜ XỬ LÝ</span></td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center" style="padding: 20px; color: #10b981; font-weight: bold;">
                    Tuyệt vời! Không có báo cáo vi phạm nào đang tồn đọng.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Báo cáo nội bộ trích xuất tự động từ Hệ thống Quản trị 2HAND MARKET.<br>
        Vui lòng không lưu hành tài liệu này cho bên thứ ba.
    </div>

</body>
</html>