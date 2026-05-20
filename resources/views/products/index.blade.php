@extends('layouts.app')
@php $pageTitle = 'Shop' @endphp
@section('content')

<div class="page-header">
    <div class="container">
        <h1>Shop</h1>
        <div class="breadcrumb"><a href="{{ route('home') }}">Home</a> <span>/</span> Shop</div>
    </div>
</div>

<section class="section">
    <div class="container">
        <form method="GET" action="{{ route('products.index') }}">
            <div class="filter-bar">
                <div class="search-bar">
                    <input type="text" name="q" class="form-control" placeholder="🔍 Search products..." value="{{ request('q') }}" style="max-width:280px;">
                </div>
                <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
                    <a href="{{ route('products.index') }}" class="filter-btn {{ !request('cat') ? 'active' : '' }}">All</a>
                    @foreach ($categories as $c)
                        <a href="{{ route('products.index', ['cat' => $c->category_id, 'q' => request('q'), 'sort' => request('sort')]) }}"
                           class="filter-btn {{ request('cat') == $c->category_id ? 'active' : '' }}">
                            {{ $c->category_name }}
                        </a>
                    @endforeach
                </div>
                <select name="sort" class="form-control" style="width:auto;" onchange="this.form.submit()">
                    <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest</option>
                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low → High</option>
                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High → Low</option>
                </select>
                @if(request('cat')) <input type="hidden" name="cat" value="{{ request('cat') }}"> @endif
                <button type="submit" class="btn btn-primary btn-sm">Search</button>
            </div>
        </form>

        <p style="color:var(--grey);font-size:0.85rem;margin-bottom:24px;">
            Showing <strong style="color:var(--accent)">{{ $products->count() }}</strong> product(s)
            @if(request('q')) for "<strong style="color:var(--white)">{{ request('q') }}</strong>" @endif
        </p>

        @if ($products->isNotEmpty())
            <div class="products-grid">
                @foreach ($products as $p)
                    @php $totalStock = $p->variants->sum('stock_qty'); @endphp
                    <div class="product-card">
                        <div class="product-img">
                            @php $img = $p->images->first(); @endphp
                            @if($img)
                                <img src="{{ asset('storage/' . $img->image_url) }}"
                                     alt="{{ $p->product_name }}"
                                     style="width:100%;height:100%;object-fit:cover;">
                            @else
                                <div class="product-img-placeholder">👕</div>
                            @endif
                            @if ($p->isLowStock() && $totalStock > 0)
                                <span class="product-badge" style="background:var(--accent-2);top:auto;bottom:12px;">Low Stock</span>
                            @endif
                            @if ($totalStock == 0)
                                <span class="product-badge" style="background:#555;top:auto;bottom:12px;">Sold Out</span>
                            @endif
                        </div>
                        <div class="product-info">
                            <div class="product-cat">{{ $p->category->category_name }}</div>
                            <div class="product-name">{{ $p->product_name }}</div>
                            <div class="product-footer">
                                <div class="product-price">RM <span>{{ number_format($p->base_price, 2) }}</span></div>
                                @if ($totalStock > 0)
                                    <a href="{{ route('products.show', $p->product_id) }}" class="quick-add">View →</a>
                                @else
                                    <span style="color:var(--grey);font-size:0.72rem;font-weight:700;text-transform:uppercase;">Sold Out</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align:center;padding:80px 0;">
                <div style="font-size:4rem;margin-bottom:16px;">🔍</div>
                <h3 style="color:var(--white);margin-bottom:8px;">No Products Found</h3>
                <p>Try adjusting your search or browse all categories.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary" style="margin-top:24px;">View All Products</a>
            </div>
        @endif
    </div>
</section>
@endsection