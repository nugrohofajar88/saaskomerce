<div>
    <label class="block text-sm font-medium mb-1">Nama</label>
    <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required class="w-full rounded-lg border px-3 py-2">
</div>
<div>
    <label class="block text-sm font-medium mb-1">Urutan</label>
    <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}" class="w-full rounded-lg border px-3 py-2">
</div>
