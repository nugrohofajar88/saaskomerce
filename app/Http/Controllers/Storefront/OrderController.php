<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function show(Order $order): View
    {
        abort_unless($order->user_id === Auth::guard('customer')->id(), 404);

        return view('storefront.order.show', [
            'order' => $order->load('items'),
            'paymentAccounts' => PaymentAccount::active()->get(),
        ]);
    }

    public function uploadProof(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->user_id === Auth::guard('customer')->id(), 404);

        if ($order->status !== 'pending_payment') {
            return back()->with('error', 'Order ini sudah diproses.');
        }

        $validated = $request->validate([
            'proof' => ['required', 'image', 'max:4096'],
        ]);

        $path = $validated['proof']->store('tenants/'.tenant()->id.'/payment-proofs', 'public');

        $order->update([
            'payment_proof_path' => $path,
            'payment_proof_uploaded_at' => now(),
        ]);

        return back()->with('status', 'Bukti bayar terkirim, admin toko akan meninjau pesananmu.');
    }
}
