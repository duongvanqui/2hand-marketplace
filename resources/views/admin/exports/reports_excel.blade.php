<table>
    <thead>
        <tr>
            <th>STT</th>
            <th>Ngày Báo Cáo</th>
            <th>Tên Người Báo Cáo</th>
            <th>Mã Sản Phẩm</th>
            <th>Tên Sản Phẩm</th>
            <th>Lý Do Vi Phạm</th>
            <th>Chi Tiết Thêm</th>
            <th>Trạng Thái</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reports as $index => $report)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $report->user->name ?? 'Người dùng ẩn' }}</td>
                <td>
                    @if($report->product)
                        SP{{ str_pad($report->product->id, 5, '0', STR_PAD_LEFT) }}
                    @else
                        Đã xóa
                    @endif
                </td>
                <td>{{ $report->product->title ?? 'Sản phẩm đã bị xóa khỏi hệ thống' }}</td>
                <td>{{ $report->reason }}</td>
                <td>{{ $report->details ?? 'Không có' }}</td>
                <td>
                    @if($report->status == 'pending') Chờ xử lý
                    @elseif($report->status == 'resolved') Đã khóa SP
                    @elseif($report->status == 'dismissed') Đã bỏ qua
                    @else {{ $report->status }}
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>