<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $orders = Order::query()
            ->with('items')
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($search, fn ($q) => $q->where(fn ($q) => $q
                ->where('order_number', 'like', "%{$search}%")
                ->orWhere('customer_name', 'like', "%{$search}%")
                ->orWhere('customer_phone', 'like', "%{$search}%")))
            // Order yang sudah upload bukti transfer diprioritaskan tampil duluan -
            // itu yang paling butuh ditinjau admin.
            ->orderByRaw('payment_proof_uploaded_at IS NULL')
            ->orderByDesc('payment_proof_uploaded_at')
            ->orderByDesc('created_at')
            ->paginate(25)
            ->withQueryString();

        return view('tenant.orders.index', [
            'orders' => $orders,
            'status' => $status,
            'search' => $search,
            'statusCounts' => Order::query()->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
        ]);
    }

    public function show(Order $order): View
    {
        $order->load('items');

        return view('tenant.orders.show', ['order' => $order]);
    }

    public function markPaid(Order $order): RedirectResponse
    {
        if ($order->status !== 'pending_payment') {
            return back()->with('error', 'Order ini sudah diproses.');
        }

        $order->update(['status' => 'paid', 'paid_at' => now()]);

        return back()->with('status', "Order {$order->order_number} ditandai lunas.");
    }
}
