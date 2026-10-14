<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(): View
    {
        return view('storefront.catalog.index', [
            'categories' => Category::orderBy('sort_order')->get(),
            'products' => Product::available()->with(['variants', 'images'])->orderBy('sort_order')->get(),
        ]);
    }

    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        $product->load(['variants' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order'), 'images', 'category']);

        $relatedProducts = Product::available()
            ->with(['variants', 'images'])
            ->when($product->category_id, fn ($q) => $q->where('category_id', $product->category_id))
            ->where('id', '!=', $product->id)
            ->orderBy('sort_order')
            ->limit(4)
            ->get();

        return view('storefront.catalog.show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
