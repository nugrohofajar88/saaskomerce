<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Support\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function show(Cart $cart): View
    {
        return view('storefront.cart.show', [
            'items' => $cart->hydrated(),
            'total' => $cart->total(),
        ]);
    }

    public function store(Request $request, Cart $cart): RedirectResponse
    {
        $validated = $request->validate([
            'variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'qty' => ['required', 'integer', 'min:1'],
        ]);

        $cart->add($validated['variant_id'], $validated['qty']);

        return back()->with('status', 'Ditambahkan ke keranjang.');
    }

    public function update(Request $request, int $variant, Cart $cart): RedirectResponse
    {
        $validated = $request->validate(['qty' => ['required', 'integer', 'min:0']]);

        $cart->updateQty($variant, $validated['qty']);

        return back()->with('status', 'Keranjang diperbarui.');
    }

    public function destroy(int $variant, Cart $cart): RedirectResponse
    {
        $cart->remove($variant);

        return back()->with('status', 'Item dihapus dari keranjang.');
    }
}
