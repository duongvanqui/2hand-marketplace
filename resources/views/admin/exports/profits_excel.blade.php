<table>
    <thead>
        <tr>
            <th>STT</th>
            <th>Mã Đơn Hàng</th>
            <th>Tên Sản Phẩm</th>
            <th>Người Bán</th>
            <th>Phí Thu Được (VNĐ)</th>
            <th>Ngày Hoàn Thành</th>
        </tr>
    </thead>
    <tbody>
        @foreach($profits as $index => $profit)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>DH{{ str_pad($profit->id, 5, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $profit->product->title ?? 'Sản phẩm đã bị xóa' }}</td>
                <td>{{ $profit->seller->name ?? 'Ẩn danh' }}</td>
                <td>{{ $profit->fee_amount }}</td>
                <td>{{ $profit->updated_at->format('d/m/Y H:i') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>