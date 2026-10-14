<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\PaymentAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentAccountController extends Controller
{
    public function index(): View
    {
        return view('tenant.payment-accounts.index', [
            'accounts' => PaymentAccount::orderBy('sort_order')->get(),
        ]);
    }

    public function create(): View
    {
        return view('tenant.payment-accounts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        PaymentAccount::create($this->validated($request));

        return redirect()->route('tenant.payment-accounts.index')->with('status', 'Rekening ditambahkan.');
    }

    public function edit(PaymentAccount $paymentAccount): View
    {
        return view('tenant.payment-accounts.edit', ['account' => $paymentAccount]);
    }

    public function update(Request $request, PaymentAccount $paymentAccount): RedirectResponse
    {
        $paymentAccount->update($this->validated($request));

        return redirect()->route('tenant.payment-accounts.index')->with('status', 'Rekening diperbarui.');
    }

    public function destroy(PaymentAccount $paymentAccount): RedirectResponse
    {
        $paymentAccount->delete();

        return redirect()->route('tenant.payment-accounts.index')->with('status', 'Rekening dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'bank_name' => ['required', 'string', 'max:100'],
            'account_number' => ['required', 'string', 'max:50'],
            'account_holder' => ['required', 'string', 'max:100'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        return $data;
    }
}
