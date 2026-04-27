<?php

namespace App\Http\Controllers\Admin;

use App\Exports\FinancialReportExport;
use App\Exports\OrdersExport;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class OrderExportController extends Controller
{
    // Admin order export page
    public function index()
    {
        $customers = User::where('role', 'customer')->orderBy('name')->get();
        return view('orders.export', compact('customers'));
    }

    // Admin filtered order export
    public function export(Request $request)
    {
        $filters = $request->only(['status', 'date_from', 'date_to', 'user_id']);
        $format  = $request->input('format', 'xlsx');
        $filename = 'orders_export_' . now()->format('Y-m-d_His') . '.' . $format;

        return Excel::download(new OrdersExport($filters), $filename);
    }

    // Customer personal order invoice PDF
    public function invoice(Order $order)
    {
        // Customers can only download their own orders
        if (!auth()->user()->isAdmin() && $order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['user', 'orderItems.book']);
        $pdf = Pdf::loadView('exports.order-invoice', compact('order'));

        return $pdf->download("invoice_order_{$order->id}.pdf");
    }

    // Financial report export
    public function financialReport(Request $request)
    {
        $filters  = $request->only(['date_from', 'date_to']);
        $filename = 'financial_report_' . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(new FinancialReportExport($filters), $filename);
    }

    // Customer personal order history export
    public function myOrders()
    {
        $filters   = ['user_id' => auth()->id()];
        $filename  = 'my_orders_' . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(new OrdersExport($filters), $filename);
    }
}
