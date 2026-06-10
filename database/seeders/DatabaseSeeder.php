<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tạo tài khoản của bạn để sau này đăng nhập test hệ thống
        $user = User::create([
            'name' => 'Dương Văn Quí',
            'email' => 'qui@tdu.edu.vn',
            'password' => Hash::make('12345678'),
        ]);

        // Tạo thêm tài khoản đối tác/người dùng khác để test luồng nhắn tin thương lượng
        $user2 = User::create([
            'name' => 'Anh Hưng',
            'email' => 'hung@gmail.com',
            'password' => Hash::make('12345678'),
        ]);

        // 2. Mảng danh mục hàng hóa cũ phổ biến trên chợ sinh viên
        $categories = [
            ['name' => 'Điện thoại & Máy tính bảng', 'slug' => 'dien-thoai-may-tinh-bang'],
            ['name' => 'Laptop & Máy tính bộ', 'slug' => 'laptop-may-tinh-bo'],
            ['name' => 'Thời trang & Quần áo', 'slug' => 'thoi-trang-quan-ao'],
            ['name' => 'Đồ gia dụng, Điện lạnh', 'slug' => 'do-gia-dung-dien-lanh'],
            ['name' => 'Sách & Truyện tranh', 'slug' => 'sach-truyen-tranh'],
        ];

        foreach ($categories as $cat) {
            $category = Category::create($cat);

            // 3. Mỗi danh mục tự động tạo ra 3 sản phẩm ngẫu nhiên để lấp đầy giao diện
            for ($i = 1; $i <= 3; $i++) {
                Product::create([
                    'user_id' => $user2->id, // Treo đồ dưới tên người bán khác để bạn có thể bấm "Đặt gạch" thử
                    'category_id' => $category->id,
                    'title' => 'Thanh lý ' . $category->name . ' còn tốt đời ' . rand(2021, 2025),
                    'slug' => Str::slug('Thanh lý ' . $category->name) . '-' . uniqid(),
                    'description' => 'Món đồ này dùng rất giữ gìn, hoạt động hoàn hảo không một lỗi nhỏ. Do mình chuẩn bị chuyển trọ hoặc nâng cấp nên cần nhượng lại nhanh gọn cho anh em sinh viên Tây Đô có thiện chí. Fix nhẹ xăng xe!',
                    'original_price' => rand(1000000, 12000000),
                    'price' => rand(150000, 4500000),
                    'condition_pct' => rand(80, 95), 
                    'location' => 'Cái Răng, Cần Thơ',
                    'status' => 'approved', // Phê duyệt luôn để hiện thẳng lên trang chủ
                    'view_count' => rand(5, 200),
                ]);
            }
        }
    }
}