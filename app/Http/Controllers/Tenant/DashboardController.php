<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('tenant.dashboard', [
            'productCount' => Product::count(),
            'pendingOrderCount' => Order::where('status', 'pending_payment')->count(),
            'paidOrderCount' => Order::where('status', 'paid')->count(),
            'pendingVerificationTotal' => (int) Order::where('status', 'pending_payment')
                ->whereNotNull('payment_proof_path')->sum('total'),
            'totalOmset' => (int) Order::where('status', 'paid')->sum('total'),
            'recentOrders' => Order::orderByDesc('created_at')->limit(5)->get(),
        ]);
    }
}
