@extends('admin.layouts.app')
@section('title', 'Edit Product')
@section('page_title', 'Products / Edit')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Edit Product</h1>
        <p class="page-subtitle">{{ $product->product_name }}</p>
    </div>
    <a href="{{ route('admin.products.show', $product) }}" class="btn-outline-dim">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

@if($errors->any())
<div class="alert-dark alert-error-dark">
    <i class="bi bi-exclamation-circle-fill"></i>
    <div>
        @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
    </div>
</div>
@endif

<form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
@csrf @method('PUT')

<div class="row g-4">
    <div class="col-12 col-lg-8">

        {{-- Basic info --}}
        <div class="card-dark mb-4">
            <div class="card-header-dark">Product Information</div>
            <div class="card-body-dark">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label-dark">Product Name *</label>
                        <input type="text" name="product_name" class="form-control-dark" required
                               value="{{ old('product_name', $product->product_name) }}">
                    </div>
                    <div class="col-6">
                        <label class="form-label-dark">Category *</label>
                        <select name="category_id" class="form-select-dark" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->category_id }}" {{ $product->category_id == $cat->category_id ? 'selected' : '' }}>
                                    {{ $cat->category_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label-dark">Base Price (RM) *</label>
                        <input type="number" name="base_price" class="form-control-dark" required step="0.01"
                               value="{{ old('base_price', $product->base_price) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label-dark">Description</label>
                        <textarea name="description" class="form-control-dark" rows="4">{{ old('description', $product->description) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label-dark">Status *</label>
                        <div style="display:flex;gap:16px;margin-top:4px;">
                            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                                <input type="radio" name="status" value="Active" {{ $product->status === 'Active' ? 'checked' : '' }}>
                                <span class="badge-status badge-active">Active</span>
                            </label>
                            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                                <input type="radio" name="status" value="Inactive" {{ $product->status === 'Inactive' ? 'checked' : '' }}>
                                <span class="badge-status badge-inactive">Inactive</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Existing images --}}
        @if($product->images->count())
        <div class="card-dark mb-4">
            <div class="card-header-dark">Current Images</div>
            <div class="card-body-dark">
                <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;">
                    @foreach($product->images as $img)
                    <div style="position:relative;">
                        <img src="{{ asset('storage/'.$img->image_url) }}"
                             style="width:100%;aspect-ratio:1;object-fit:cover;border-radius:4px;border:1px solid var(--border);">
                        @if($img->is_primary)
                            <span style="position:absolute;top:4px;left:4px;background:var(--accent);color:#000;font-size:9px;padding:2px 6px;border-radius:2px;font-family:var(--font-display);">PRIMARY</span>
                        @endif
                        <label style="position:absolute;bottom:4px;left:4px;display:flex;align-items:center;gap:4px;background:rgba(0,0,0,.7);color:#fff;font-size:10px;padding:3px 6px;border-radius:2px;cursor:pointer;">
                            <input type="checkbox" name="delete_images[]" value="{{ $img->image_id }}"> Delete
                        </label>
                    </div>
                    @endforeach
                </div>
                <div style="margin-top:12px;">
                    <label class="form-label-dark">Set Primary Image (Image ID)</label>
                    <input type="number" name="primary_image" class="form-control-dark" placeholder="Enter image ID to set as primary">
                </div>
            </div>
        </div>
        @endif

        {{-- Add new images --}}
        <div class="card-dark mb-4">
            <div class="card-header-dark">Add New Images</div>
            <div class="card-body-dark">
                <input type="file" name="new_images[]" multiple accept="image/*" class="form-control-dark">
            </div>
        </div>

    </div>

    <div class="col-12 col-lg-4">
        <div class="card-dark mb-4">
            <div class="card-header-dark">Variants</div>
            <div class="card-body-dark" style="padding:0;">
                @foreach($product->variants as $variant)
                <div style="padding:12px 16px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                    <div>
                        <div style="font-size:13px;font-weight:500;">{{ $variant->size }} / {{ $variant->colour }}</div>
                        <div class="mono" style="font-size:11px;color:var(--text-muted);">{{ $variant->sku_code }}</div>
                    </div>
                    <div style="text-align:right;">
                        <div class="mono" style="font-size:16px;font-weight:700;color:{{ $variant->stock_qty <= 5 ? 'var(--warning)' : 'var(--text-primary)' }}">{{ $variant->stock_qty }}</div>
                        <div style="font-size:10px;color:var(--text-muted);">units</div>
                    </div>
                </div>
                @endforeach
                <div style="padding:12px 16px;">
                    <a href="{{ route('admin.inventory.index') }}?search={{ urlencode($product->product_name) }}" class="btn-outline-dim" style="width:100%;justify-content:center;font-size:11px;">
                        <i class="bi bi-stack"></i> Manage Stock
                    </a>
                </div>
            </div>
        </div>

        <div class="card-dark">
            <div class="card-body-dark">
                <button type="submit" class="btn-accent" style="width:100%;justify-content:center;padding:13px;">
                    <i class="bi bi-check2"></i> Save Changes
                </button>
                <a href="{{ route('admin.products.show', $product) }}" class="btn-outline-dim" style="width:100%;justify-content:center;padding:12px;margin-top:8px;">
                    Cancel
                </a>
                <hr class="divider">
                <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                      onsubmit="return confirm('Permanently delete this product? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-danger-dim" style="width:100%;justify-content:center;padding:10px;">
                        <i class="bi bi-trash"></i> Delete Product
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
</form>
@endsection
