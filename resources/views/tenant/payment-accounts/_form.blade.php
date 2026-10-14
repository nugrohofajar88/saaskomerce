<div class="flex flex-col gap-1.5">
    <label class="text-label-lg text-on-surface">Nama Bank</label>
    <input type="text" name="bank_name" value="{{ old('bank_name', $account->bank_name ?? '') }}" required placeholder="mis. BCA"
           class="w-full h-11 px-3.5 rounded-lg bg-surface-container-lowest text-on-surface text-body-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-primary">
</div>
<div class="flex flex-col gap-1.5">
    <label class="text-label-lg text-on-surface">Nomor Rekening</label>
    <input type="text" name="account_number" value="{{ old('account_number', $account->account_number ?? '') }}" required
           class="w-full h-11 px-3.5 rounded-lg bg-surface-container-lowest text-on-surface text-body-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-primary">
</div>
<div class="flex flex-col gap-1.5">
    <label class="text-label-lg text-on-surface">Atas Nama</label>
    <input type="text" name="account_holder" value="{{ old('account_holder', $account->account_holder ?? '') }}" required
           class="w-full h-11 px-3.5 rounded-lg bg-surface-container-lowest text-on-surface text-body-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-primary">
</div>
<div class="flex flex-col gap-1.5">
    <label class="text-label-lg text-on-surface">Catatan</label>
    <input type="text" name="note" value="{{ old('note', $account->note ?? '') }}" placeholder="Opsional"
           class="w-full h-11 px-3.5 rounded-lg bg-surface-container-lowest text-on-surface text-body-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-primary">
</div>
<label class="flex items-center gap-2.5 p-3 rounded-xl bg-surface-container-low cursor-pointer">
    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $account->is_active ?? true)) class="w-4 h-4 rounded text-primary accent-primary cursor-pointer">
    <span class="text-label-lg text-on-surface">Aktif (tampil ke pembeli)</span>
</label>
