<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $from = $request->string('from')->toString() ?: now()->startOfMonth()->toDateString();
        $to = $request->string('to')->toString() ?: now()->toDateString();

        $baseQuery = Order::query()
            ->whereBetween('ordered_at', [$from . ' 00:00:00', $to . ' 23:59:59']);

        $summary = (clone $baseQuery)
            ->selectRaw('COUNT(*) as orders_count')
            ->selectRaw("SUM(CASE WHEN status = 'completed' THEN total ELSE 0 END) as completed_revenue")
            ->selectRaw("SUM(CASE WHEN status IN ('pending', 'preparing', 'ready') THEN 1 ELSE 0 END) as active_orders")
            ->first();

        $bestSellers = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('drinks', 'drinks.id', '=', 'order_items.drink_id')
            ->join('categories', 'categories.id', '=', 'drinks.category_id')
            ->whereBetween('orders.ordered_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->select(
                'drinks.name as drink_name',
                'categories.name as category_name'
            )
            ->selectRaw('SUM(order_items.quantity) as cups_sold')
            ->selectRaw('SUM(order_items.line_total) as total_sales')
            ->selectRaw("
                SUM(CASE WHEN order_items.size = 'Jumbo' THEN order_items.quantity ELSE 0 END) as jumbo_cups
            ")
            ->groupBy('drinks.name', 'categories.name')
            ->orderByDesc('total_sales')
            ->limit(10)
            ->get();

        $recentOrders = (clone $baseQuery)->latest('ordered_at')->take(8)->get();

        return view('reports.index', compact('summary', 'bestSellers', 'recentOrders', 'from', 'to'));
    }

    public function pdf(Request $request)
    {
        $view = $this->index($request);
        $data = $view->getData();

        return Pdf::loadView('reports.pdf', $data)
            ->setPaper('a4', 'portrait')
            ->download('milktea-shop-report.pdf');
    }
}
