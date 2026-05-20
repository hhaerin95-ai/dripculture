@extends('admin.layouts.app')
@section('title', $product->product_name)
@section('page_title', 'Products / Detail')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $product->product_name }}</h1>
        <p class="page-subtitle">{{ $product->category?->category_name }}</p>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('admin.products.edit', $product) }}" class="btn-accent">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('admin.products.index') }}" class="btn-outline-dim">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row g-4">
    {{-- Images --}}
    <div class="col-12 col-lg-4">
        <div class="card-dark mb-4">
            @php $primary = $product->images->firstWhere('is_primary', 1) ?? $product->images->first(); @endphp
            @if($primary)
                <img src="{{ asset('storage/'.$primary->image_url) }}" style="width:100%;aspect-ratio:1;object-fit:cover;" alt="">
            @else
                <div style="width:100%;aspect-ratio:1;background:var(--bg-elevated);display:flex;align-items:center;justify-content:center;color:var(--text-muted);">
                    <i class="bi bi-image" style="font-size:48px;"></i>
                </div>
            @endif
            @if($product->images->count() > 1)
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:4px;padding:4px;">
                @foreach($product->images as $img)
                <img src="{{ asset('storage/'.$img->image_url) }}"
                     style="width:100%;aspect-ratio:1;object-fit:cover;border-radius:2px;opacity:{{ $img->is_primary ? 1 : 0.6 }};cursor:pointer;"
                     onclick="document.querySelector('.card-dark img').src='{{ asset('storage/'.$img->image_url) }}'">
                @endforeach
            </div>
            @endif
        </div>
    </div>

    {{-- Info --}}
    <div class="col-12 col-lg-8">
        <div class="card-dark mb-4">
            <div class="card-header-dark">Product Details</div>
            <div class="card-body-dark">
                <div class="row g-3">
                    <div class="col-6">
                        <div style="font-size:11px;color:var(--text-muted);margin-bottom:4px;">Base Price</div>
                        <div class="mono" style="font-size:20px;font-weight:700;color:var(--accent);">RM {{ number_format($product->base_price, 2) }}</div>
                    </div>
                    <div class="col-6">
                        <div style="font-size:11px;color:var(--text-muted);margin-bottom:4px;">Status</div>
                        <span class="badge-status badge-{{ strtolower($product->status) }}">{{ $product->status }}</span>
                    </div>
                    @if($product->description)
                    <div class="col-12">
                        <div style="font-size:11px;color:var(--text-muted);margin-bottom:4px;">Description</div>
                        <div style="font-size:13.5px;line-height:1.7;color:var(--text-secondary);">{{ $product->description }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Variants table --}}
        <div class="card-dark">
            <div class="card-header-dark" style="display:flex;justify-content:space-between;align-items:center;">
                <span>Variants ({{ $product->variants->count() }})</span>
                <span style="font-size:12px;color:var(--text-muted);">
                    Total stock: <span class="mono" style="color:var(--text-primary);">{{ $product->variants->sum('stock_qty') }}</span>
                </span>
            </div>
            <table class="table-dark-custom">
                <thead>
                    <tr>
                        <th>Size</th><th>Colour</th><th>SKU</th><th>+Price</th><th>Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($product->variants as $v)
                    <tr>
                        <td>{{ $v->size }}</td>
                        <td>{{ $v->colour }}</td>
                        <td class="mono" style="font-size:11px;color:var(--text-muted);">{{ $v->sku_code }}</td>
                        <td class="mono">{{ $v->additional_price > 0 ? '+RM '.number_format($v->additional_price,2) : '—' }}</td>
                        <td>
                            <span class="mono" style="font-size:16px;font-weight:700;color:{{ $v->stock_qty == 0 ? 'var(--danger)' : ($v->stock_qty <=5 ? 'var(--warning)' : 'var(--success)') }}">
                                {{ $v->stock_qty }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
