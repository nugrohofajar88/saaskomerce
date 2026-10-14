<div>
    <label class="block text-sm font-medium mb-1">Nama Bank</label>
    <input type="text" name="bank_name" value="{{ old('bank_name', $account->bank_name ?? '') }}" required class="w-full rounded-lg border px-3 py-2">
</div>
<div>
    <label class="block text-sm font-medium mb-1">Nomor Rekening</label>
    <input type="text" name="account_number" value="{{ old('account_number', $account->account_number ?? '') }}" required class="w-full rounded-lg border px-3 py-2">
</div>
<div>
    <label class="block text-sm font-medium mb-1">Atas Nama</label>
    <input type="text" name="account_holder" value="{{ old('account_holder', $account->account_holder ?? '') }}" required class="w-full rounded-lg border px-3 py-2">
</div>
<div>
    <label class="block text-sm font-medium mb-1">Catatan</label>
    <input type="text" name="note" value="{{ old('note', $account->note ?? '') }}" class="w-full rounded-lg border px-3 py-2">
</div>
<label class="flex items-center gap-2 text-sm">
    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $account->is_active ?? true))>
    Aktif
</label>
