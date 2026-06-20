<table>
    <thead>
        <tr>
            <th>STT</th>
            <th>Mã Lệnh</th>
            <th>Người Yêu Cầu</th>
            <th>Email</th>
            <th>Số Tiền (VNĐ)</th>
            <th>Thông Tin Ngân Hàng</th>
            <th>Trạng Thái</th>
            <th>Ngày Cập Nhật</th>
        </tr>
    </thead>
    <tbody>
        @foreach($withdrawals as $index => $w)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>RT{{ str_pad($w->id, 5, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $w->user->name ?? 'Người dùng ẩn' }}</td>
                <td>{{ $w->user->email ?? '' }}</td>
                <td>{{ $w->amount }}</td>
                <td>{{ $w->bank_info }}</td>
                <td>
                    @if($w->status == 'approved') Đã duyệt chi
                    @elseif($w->status == 'rejected') Bị từ chối
                    @else Chờ xử lý
                    @endif
                </td>
                <td>{{ $w->updated_at->format('d/m/Y H:i') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>