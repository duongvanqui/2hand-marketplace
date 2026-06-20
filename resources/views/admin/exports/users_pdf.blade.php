<!DOCTYPE html>
<html lang="vi">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Danh sách Người dùng</title>
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
        <h2>BÁO CÁO DANH SÁCH NGƯỜI DÙNG</h2>
        
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
                <th width="5%">STT</th>
                <th width="25%">Họ tên</th>
                <th width="25%">Email</th>
                <th width="15%">Ngày đăng ký</th>
                <th width="15%">Vai trò</th>
                <th width="15%">Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $index => $user)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td style="text-decoration: underline;">{{ $user->email }}</td>
                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                <td>{{ $user->role === 'admin' ? 'Admin' : 'User' }}</td>
                <td>{{ $user->status == 1 ? 'Hoạt động' : 'Bị khóa' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @php
        $total = $users->count();
        $active = $users->where('status', 1)->count();
        $banned = $users->where('status', 0)->count();
    @endphp

    <div class="summary-container">
        <h3 style="margin-bottom: 10px;">Tổng kết dữ liệu</h3>
        <div class="summary-box">
            Tổng người dùng trong kỳ: <b>{{ number_format($total, 0, ',', '.') }}</b><br>
            Đang hoạt động: <b>{{ number_format($active, 0, ',', '.') }}</b><br>
            Bị khóa: <b>{{ number_format($banned, 0, ',', '.') }}</b>
        </div>
    </div>
</body>
</html>