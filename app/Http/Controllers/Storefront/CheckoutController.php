<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Support\Cart;
use App\Support\OrderCreationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function show(Cart $cart): View|RedirectResponse
    {
        if ($cart->isEmpty()) {
            return redirect()->route('storefront.cart')->with('error', 'Keranjang masih kosong.');
        }

        return view('storefront.checkout.show', [
            'items' => $cart->hydrated(),
            'total' => $cart->total(),
            'customer' => Auth::guard('customer')->user(),
        ]);
    }

    public function store(Request $request, Cart $cart, OrderCreationService $orders): RedirectResponse
    {
        if ($cart->isEmpty()) {
            return redirect()->route('storefront.cart')->with('error', 'Keranjang masih kosong.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:32'],
            'address' => ['nullable', 'string', 'max:1000'],
        ]);

        $order = $orders->createFromCart($cart, Auth::guard('customer')->user(), $validated);

        $cart->clear();

        return redirect()->route('storefront.order.show', $order)->with('status', 'Pesanan dibuat! Silakan transfer & upload bukti bayar.');
    }
}
