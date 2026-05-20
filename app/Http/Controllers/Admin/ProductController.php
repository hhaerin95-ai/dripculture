<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Image;
use App\Models\StockLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /** List all products with search and filter. */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images' => fn($q) => $q->where('is_primary', 1)])
            ->withCount('variants');

        if ($request->filled('search')) {
            $query->where('product_name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products   = $query->latest('created_at')->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /** Show single product details. */
    public function show(Product $product)
    {
        $product->load(['category', 'variants', 'images']);
        return view('admin.products.show', compact('product'));
    }

    /** Create product form. */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /** Store a new product. */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name'  => ['required', 'string', 'max:150'],
            'category_id'   => ['required', 'exists:categories,category_id'],
            'description'   => ['nullable', 'string'],
            'base_price'    => ['required', 'numeric', 'min:0'],
            'status'        => ['required', 'in:Active,Inactive'],
            // Variants (at least one required)
            'variants'               => ['required', 'array', 'min:1'],
            'variants.*.size'        => ['required', 'string', 'max:20'],
            'variants.*.colour'      => ['required', 'string', 'max:50'],
            'variants.*.sku_code'    => ['required', 'string', 'max:50', 'distinct'],
            'variants.*.stock_qty'   => ['required', 'integer', 'min:0'],
            'variants.*.additional_price' => ['nullable', 'numeric', 'min:0'],
            // Images
            'images'    => ['nullable', 'array'],
            'images.*'  => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'primary_image' => ['nullable', 'integer'],
        ]);

        DB::transaction(function () use ($validated, $request) {
            $product = Product::create([
                'category_id'  => $validated['category_id'],
                'product_name' => $validated['product_name'],
                'description'  => $validated['description'] ?? null,
                'base_price'   => $validated['base_price'],
                'status'       => $validated['status'],
                'created_at'   => now(),
            ]);

            // Store variants
            foreach ($validated['variants'] as $variantData) {
                $product->variants()->create([
                    'size'             => $variantData['size'],
                    'colour'           => $variantData['colour'],
                    'sku_code'         => $variantData['sku_code'],
                    'stock_qty'        => $variantData['stock_qty'],
                    'additional_price' => $variantData['additional_price'] ?? 0,
                ]);
            }

            // Store images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $imageFile) {
                    $path = $imageFile->store('products', 'public');
                    $product->images()->create([
                        'image_url'  => $path,
                        'is_primary' => ($index === (int)($request->primary_image ?? 0)) ? 1 : 0,
                    ]);
                }
            }
        });

        return redirect()->route('admin.products.index')
                         ->with('success', 'Product created successfully!');
    }

    /** Edit product form. */
    public function edit(Product $product)
    {
        $product->load(['category', 'variants', 'images']);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /** Update product. */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'product_name' => ['required', 'string', 'max:150'],
            'category_id'  => ['required', 'exists:categories,category_id'],
            'description'  => ['nullable', 'string'],
            'base_price'   => ['required', 'numeric', 'min:0'],
            'status'       => ['required', 'in:Active,Inactive'],
            'new_images'   => ['nullable', 'array'],
            'new_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        DB::transaction(function () use ($validated, $request, $product) {
            $product->update([
                'category_id'  => $validated['category_id'],
                'product_name' => $validated['product_name'],
                'description'  => $validated['description'] ?? null,
                'base_price'   => $validated['base_price'],
                'status'       => $validated['status'],
            ]);

            // Add new images
            if ($request->hasFile('new_images')) {
                foreach ($request->file('new_images') as $imageFile) {
                    $path = $imageFile->store('products', 'public');
                    $product->images()->create([
                        'image_url'  => $path,
                        'is_primary' => 0,
                    ]);
                }
            }

            // Delete selected images
            if ($request->filled('delete_images')) {
                $toDelete = $product->images()->whereIn('image_id', $request->delete_images)->get();
                foreach ($toDelete as $img) {
                    Storage::disk('public')->delete($img->image_url);
                    $img->delete();
                }
            }

            // Set primary image
            if ($request->filled('primary_image')) {
                $product->images()->update(['is_primary' => 0]);
                $product->images()->where('image_id', $request->primary_image)->update(['is_primary' => 1]);
            }
        });

        return redirect()->route('admin.products.show', $product)
                         ->with('success', 'Product updated successfully!');
    }

    /** Toggle product active/inactive status (soft delete approach). */
    public function toggleStatus(Product $product)
    {
        $product->update([
            'status' => $product->status === 'Active' ? 'Inactive' : 'Active',
        ]);

        return back()->with('success', 'Product status updated.');
    }

    /** Hard delete product (only if no orders). */
    public function destroy(Product $product)
    {
        // Check for existing orders referencing this product's variants
        $hasOrders = $product->variants()
            ->whereHas('orderItems')
            ->exists();

        if ($hasOrders) {
            return back()->with('error', 'Cannot delete: this product has associated orders. Set it to Inactive instead.');
        }

        DB::transaction(function () use ($product) {
            // Delete images from storage
            foreach ($product->images as $img) {
                Storage::disk('public')->delete($img->image_url);
            }
            $product->images()->delete();
            $product->variants()->delete();
            $product->delete();
        });

        return redirect()->route('admin.products.index')
                         ->with('success', 'Product deleted successfully.');
    }

    // ── Variant sub-actions ────────────────────────────────────────────

    public function storeVariant(Request $request, Product $product)
    {
        $validated = $request->validate([
            'size'             => ['required', 'string', 'max:20'],
            'colour'           => ['required', 'string', 'max:50'],
            'sku_code'         => ['required', 'string', 'max:50', 'unique:variants,sku_code'],
            'stock_qty'        => ['required', 'integer', 'min:0'],
            'additional_price' => ['nullable', 'numeric', 'min:0'],
        ]);

        $product->variants()->create($validated);

        return back()->with('success', 'Variant added successfully.');
    }

    public function updateVariant(Request $request, Product $product, Variant $variant)
    {
        $validated = $request->validate([
            'size'             => ['required', 'string', 'max:20'],
            'colour'           => ['required', 'string', 'max:50'],
            'sku_code'         => ['required', 'string', 'max:50', "unique:variants,sku_code,{$variant->variant_id},variant_id"],
            'stock_qty'        => ['required', 'integer', 'min:0'],
            'additional_price' => ['nullable', 'numeric', 'min:0'],
        ]);

        $variant->update($validated);

        return back()->with('success', 'Variant updated successfully.');
    }

    public function destroyVariant(Product $product, Variant $variant)
    {
        if ($variant->orderItems()->exists()) {
            return back()->with('error', 'Cannot delete variant: it has associated orders.');
        }

        $variant->delete();

        return back()->with('success', 'Variant deleted.');
    }
}
