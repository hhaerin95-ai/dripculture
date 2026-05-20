@extends('admin.layouts.app')
@section('title', 'Products')
@section('page_title', 'Products')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Products</h1>
        <p class="page-subtitle">{{ $products->total() }} products in catalogue</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn-accent">
        <i class="bi bi-plus"></i> Add Product
    </a>
</div>

{{-- Filters --}}
<div class="card-dark mb-4">
    <div class="card-body-dark">
        <form method="GET" action="{{ route('admin.products.index') }}" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
            <div style="flex:1;min-width:200px;">
                <label class="form-label-dark">Search</label>
                <input name="search" type="text" class="form-control-dark" placeholder="Product name..." value="{{ request('search') }}">
            </div>
            <div style="min-width:160px;">
                <label class="form-label-dark">Category</label>
                <select name="category" class="form-select-dark">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->category_id }}" {{ request('category') == $cat->category_id ? 'selected' : '' }}>
                            {{ $cat->category_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="min-width:130px;">
                <label class="form-label-dark">Status</label>
                <select name="status" class="form-select-dark">
                    <option value="">All Status</option>
                    <option value="Active"   {{ request('status') === 'Active'   ? 'selected' : '' }}>Active</option>
                    <option value="Inactive" {{ request('status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div style="display:flex;gap:8px;align-items:flex-end;padding-bottom:1px;">
                <button type="submit" class="btn-accent"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('admin.products.index') }}" class="btn-outline-dim">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Products grid --}}
@if($products->count())
<div class="row g-3 mb-4">
    @foreach($products as $product)
    @php
        $thumb = $product->images->first();
        $totalStock = $product->variants->sum('stock_qty') ?? 0;
    @endphp
    <div class="col-6 col-md-4 col-lg-3">
        <div class="card-dark" style="height:100%;">
            {{-- Thumbnail --}}
            <div style="aspect-ratio:1;background:#1a1a1a;overflow:hidden;position:relative;">
                @if($thumb)
                    <img src="{{ asset('storage/' . $thumb->image_url) }}"
                         style="width:100%;height:100%;object-fit:cover;"
                         alt="{{ $product->product_name }}">
                @else
                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:var(--text-muted);">
                        <i class="bi bi-image" style="font-size:32px;"></i>
                    </div>
                @endif
                <span class="badge-status badge-{{ strtolower($product->status) }}"
                      style="position:absolute;top:8px;right:8px;font-size:10px;">
                    {{ $product->status }}
                </span>
            </div>

            <div style="padding:14px;">
                <div style="font-size:11px;color:var(--text-muted);margin-bottom:4px;">{{ $product->category->category_name ?? 'N/A' }}</div>
                <div style="font-weight:600;font-size:14px;color:var(--text-primary);margin-bottom:8px;">
                    {{ Str::limit($product->product_name, 30) }}
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
                    <span class="mono" style="color:var(--accent);">RM {{ number_format($product->base_price, 2) }}</span>
                    <span style="font-size:11px;color:{{ $totalStock <= 5 ? 'var(--danger)' : 'var(--text-muted)' }};">
                        {{ $totalStock }} units
                    </span>
                </div>
                <div style="display:flex;gap:6px;">
                    <a href="{{ route('admin.products.show', $product) }}" class="btn-outline-dim" style="flex:1;justify-content:center;font-size:11px;padding:6px 8px;">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn-outline-dim" style="flex:1;justify-content:center;font-size:11px;padding:6px 8px;">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.products.toggle-status', $product) }}" style="flex:1;">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn-outline-dim" style="width:100%;justify-content:center;font-size:11px;padding:6px 8px;"
                                title="{{ $product->status === 'Active' ? 'Deactivate' : 'Activate' }}">
                            <i class="bi bi-{{ $product->status === 'Active' ? 'pause' : 'play' }}"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Pagination --}}
<div class="d-flex justify-content-center">
    {{ $products->links() }}
</div>

@else
<div class="card-dark">
    <div class="empty-state">
        <i class="bi bi-box-seam"></i>
        <p>No products found. <a href="{{ route('admin.products.create') }}" style="color:var(--accent);">Add your first product</a></p>
    </div>
</div>
@endif
@endsection
