<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Report;
use App\Models\Withdrawal; // BỔ SUNG MODEL NÀY
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

// Khai báo các class Export
use App\Exports\UsersExport;
use App\Exports\ProductsExport;
use App\Exports\OrdersExport;
use App\Exports\ReportsExport;
use App\Exports\ProfitsExport;      // Thêm class cho Báo cáo Lợi nhuận
use App\Exports\WithdrawalsExport;  // Thêm class cho Báo cáo Rút tiền

class ExportController extends Controller
{
    public function export(Request $request, $type)
    {
        $format = $request->input('format', 'excel');

        switch ($type) {
            case 'users':
                return $this->exportUsers($format, $request);
            case 'products':
                return $this->exportProducts($format, $request);
            case 'orders':
                return $this->exportOrders($format, $request);
            case 'reports':
                return $this->exportReports($format, $request);
            case 'wallet': // ĐÃ BỔ SUNG CASE WALLET
                return $this->exportWallet($format, $request);
            default:
                abort(404, 'Loại dữ liệu xuất không hợp lệ!');
        }
    }

    /**
     * 1. XUẤT NGƯỜI DÙNG
     */
    private function exportUsers($format, $request)
    {
        // ... (Code cũ giữ nguyên)
        $query = User::query();
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }
        if ($request->has('export_status') && $request->export_status !== 'all') {
            $query->where('status', $request->export_status);
        }
        if (!empty($fromDate)) {
            $query->whereDate('created_at', '>=', Carbon::parse($fromDate));
        }
        if (!empty($toDate)) {
            $query->whereDate('created_at', '<=', Carbon::parse($toDate));
        }

        $users = $query->latest()->get();

        if ($format === 'excel') {
            return Excel::download(new UsersExport($users), 'danh_sach_nguoi_dung_' . date('Ymd_His') . '.xlsx');
        } elseif ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.exports.users_pdf', compact('users', 'fromDate', 'toDate'));
            return $pdf->download('danh_sach_nguoi_dung_' . date('Ymd_His') . '.pdf');
        }
        return back()->with('error', 'Định dạng không được hỗ trợ.');
    }

    /**
     * 2. XUẤT SẢN PHẨM
     */
    private function exportProducts($format, $request)
    {
        // ... (Code cũ giữ nguyên)
        $query = Product::with(['user', 'category']);
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        if ($request->has('export_status') && $request->export_status !== 'all') {
            if (in_array($request->export_status, ['hidden', 'locked', '0'])) {
                 $query->whereIn('status', ['locked', 'hidden', '0']);
            } else {
                 $query->where('status', $request->export_status);
            }
        }

        if (!empty($fromDate)) {
            $query->whereDate('created_at', '>=', Carbon::parse($fromDate));
        }
        if (!empty($toDate)) {
            $query->whereDate('created_at', '<=', Carbon::parse($toDate));
        }

        $products = $query->latest()->get();

        if ($format === 'excel') {
            return Excel::download(new ProductsExport($products), 'bao_cao_san_pham_' . date('Ymd_His') . '.xlsx');
        } elseif ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.exports.products_pdf', compact('products', 'fromDate', 'toDate'));
            return $pdf->download('bao_cao_san_pham_' . date('Ymd_His') . '.pdf');
        }
        return back()->with('error', 'Định dạng xuất file không được hỗ trợ.');
    }

    /**
     * 3. XUẤT GIAO DỊCH (ORDERS)
     */
    private function exportOrders($format, $request)
    {
        // ... (Code cũ giữ nguyên)
        $query = Order::with(['product', 'seller']);
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        if ($request->has('export_status') && $request->export_status !== 'all') {
            if ($request->export_status === 'completed') {
                $query->where('status', 'completed');
            } elseif ($request->export_status === 'rejected' || $request->export_status === 'cancelled') {
                $query->whereIn('status', ['cancelled', 'failed', 'refunded', 'rejected']);
            }
        }

        if (!empty($fromDate)) {
            $query->whereDate('created_at', '>=', Carbon::parse($fromDate));
        }
        if (!empty($toDate)) {
            $query->whereDate('created_at', '<=', Carbon::parse($toDate));
        }

        $orders = $query->latest()->get();

        if ($format === 'excel') {
            return Excel::download(new OrdersExport($orders), 'bao_cao_giao_dich_' . date('Ymd_His') . '.xlsx');
        } elseif ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.exports.orders_pdf', compact('orders', 'fromDate', 'toDate'));
            return $pdf->download('bao_cao_giao_dich_' . date('Ymd_His') . '.pdf');
        }
        return back()->with('error', 'Định dạng xuất file không được hỗ trợ.');
    }

    /**
     * 4. XUẤT BÁO CÁO VI PHẠM (REPORTS)
     */
    private function exportReports($format, $request)
    {
        // ... (Code cũ giữ nguyên)
        $query = Report::with(['user', 'product']);
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        if ($request->has('export_status') && $request->export_status !== 'all') {
            $query->where('status', $request->export_status);
        }

        if (!empty($fromDate)) {
            $query->whereDate('created_at', '>=', Carbon::parse($fromDate));
        }
        if (!empty($toDate)) {
            $query->whereDate('created_at', '<=', Carbon::parse($toDate));
        }

        $reports = $query->latest()->get();

        if ($format === 'excel') {
            return Excel::download(new ReportsExport($reports), 'danh_sach_vi_pham_' . date('Ymd_His') . '.xlsx');
        } elseif ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.exports.reports_pdf', compact('reports', 'fromDate', 'toDate'));
            return $pdf->download('danh_sach_vi_pham_' . date('Ymd_His') . '.pdf');
        }
        return back()->with('error', 'Định dạng xuất file không được hỗ trợ.');
    }

    /**
     * 5. XUẤT TÀI CHÍNH (VÍ & LỢI NHUẬN) - MỚI BỔ SUNG
     */
    private function exportWallet($format, $request)
    {
        // Lấy loại báo cáo từ Form (Lợi nhuận sàn HOẶC Yêu cầu rút tiền)
        $reportType = $request->input('report_type', 'profits'); 
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        // LUỒNG A: XUẤT BÁO CÁO LỢI NHUẬN (Từ phí giao dịch)
        if ($reportType === 'profits') {
            $query = Order::with(['product', 'seller'])->where('status', 'completed')->where('fee_amount', '>', 0);
            
            // Lọc thời gian dựa trên lúc hoàn thành đơn (updated_at)
            if (!empty($fromDate)) {
                $query->whereDate('updated_at', '>=', Carbon::parse($fromDate));
            }
            if (!empty($toDate)) {
                $query->whereDate('updated_at', '<=', Carbon::parse($toDate));
            }

            $profits = $query->latest('updated_at')->get();

            if ($format === 'excel') {
                return Excel::download(new ProfitsExport($profits), 'bao_cao_loi_nhuan_san_' . date('Ymd_His') . '.xlsx');
            } elseif ($format === 'pdf') {
                $pdf = Pdf::loadView('admin.exports.profits_pdf', compact('profits', 'fromDate', 'toDate'));
                return $pdf->download('bao_cao_loi_nhuan_san_' . date('Ymd_His') . '.pdf');
            }
        } 
        // LUỒNG B: XUẤT BÁO CÁO RÚT TIỀN (Giải ngân)
        elseif ($reportType === 'withdrawals') {
            $query = Withdrawal::with('user');
            
            if (!empty($fromDate)) {
                $query->whereDate('created_at', '>=', Carbon::parse($fromDate));
            }
            if (!empty($toDate)) {
                $query->whereDate('created_at', '<=', Carbon::parse($toDate));
            }

            $withdrawals = $query->latest()->get();

            if ($format === 'excel') {
                return Excel::download(new WithdrawalsExport($withdrawals), 'bao_cao_giai_ngan_' . date('Ymd_His') . '.xlsx');
            } elseif ($format === 'pdf') {
                $pdf = Pdf::loadView('admin.exports.withdrawals_pdf', compact('withdrawals', 'fromDate', 'toDate'));
                return $pdf->download('bao_cao_giai_ngan_' . date('Ymd_His') . '.pdf');
            }
        }

        return back()->with('error', 'Loại dữ liệu xuất tài chính không hợp lệ!');
    }
}