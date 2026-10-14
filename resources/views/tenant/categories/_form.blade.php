<div class="flex flex-col gap-1.5">
    <label class="text-label-lg text-on-surface">Nama</label>
    <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required
           class="w-full h-11 px-3.5 rounded-lg bg-surface-container-lowest text-on-surface text-body-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-primary">
</div>
<div class="flex flex-col gap-1.5">
    <label class="text-label-lg text-on-surface">Urutan</label>
    <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}"
           class="w-full h-11 px-3.5 rounded-lg bg-surface-container-lowest text-on-surface text-body-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-primary">
</div>
