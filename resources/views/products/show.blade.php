@extends('layouts.app')
@php $pageTitle = $product->product_name @endphp
@section('content')

<div class="page-header">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a> <span>/</span>
            <a href="{{ route('products.index') }}">Shop</a> <span>/</span>
            <a href="{{ route('products.index', ['cat' => $product->category_id]) }}">{{ $product->category->category_name }}</a> <span>/</span>
            {{ $product->product_name }}
        </div>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="product-detail-grid">
            <!-- Image -->
            <div>
                <div style="background:var(--darker);border:1px solid var(--border);border-radius:4px;aspect-ratio:1/1;overflow:hidden;display:flex;align-items:center;justify-content:center;">
                    @php $primaryImg = $product->images->firstWhere('is_primary', 1) ?? $product->images->first(); @endphp
                    @if($primaryImg)
                        <img src="{{ asset('storage/' . $primaryImg->image_url) }}"
                             alt="{{ $product->product_name }}"
                             style="width:100%;height:100%;object-fit:cover;">
                    @else
                        <div style="font-size:6rem;">👕</div>
                    @endif
                </div>
                <div style="display:flex;gap:8px;margin-top:12px;">
                    @forelse($product->images as $img)
                        <div style="flex:1;aspect-ratio:1/1;background:var(--darker);border:1px solid var(--border);border-radius:4px;overflow:hidden;cursor:pointer;">
                            <img src="{{ asset('storage/' . $img->image_url) }}"
                                 style="width:100%;height:100%;object-fit:cover;">
                        </div>
                    @empty
                        @for($i = 0; $i < 4; $i++)
                            <div style="flex:1;aspect-ratio:1/1;background:var(--darker);border:1px solid var(--border);border-radius:4px;"></div>
                        @endfor
                    @endforelse
                </div>
            </div>

            <!-- Info -->
            <div>
                <div style="font-size:0.72rem;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:var(--accent);margin-bottom:12px;">{{ $product->category->category_name }}</div>
                <h1 style="font-size:2rem;color:var(--white);margin-bottom:8px;text-transform:uppercase;letter-spacing:1px;">{{ $product->product_name }}</h1>
                <div class="detail-price">RM {{ number_format($product->base_price, 2) }}</div>
                <p style="color:var(--grey);font-size:0.9rem;line-height:1.7;margin-bottom:24px;">{{ $product->description }}</p>

                <div class="detail-stock {{ $product->isLowStock() ? 'low' : '' }}">
                    @if ($product->stock_qty > 10)
                        ✅ In Stock ({{ $product->stock_qty }} available)
                    @elseif ($product->stock_qty > 0)
                        ⚠️ Low Stock — Only {{ $product->stock_qty }} left!
                    @else
                        ❌ Out of Stock
                    @endif
                </div>

                @if ($product->stock_qty > 0)
                    <form method="POST" action="{{ route('cart.add') }}" id="addToCartForm">
                        @csrf
                        <input type="hidden" name="variant_id" id="selectedVariantId" value="">

                        <div style="margin-bottom:24px;">
                            <div style="font-size:0.72rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--light-grey);margin-bottom:12px;">Select Size *</div>
                            <div class="size-grid">
                                @foreach ($product->variants->pluck('size')->unique() as $sz)
                                    <button type="button" class="size-btn" onclick="selectSize('{{ $sz }}', this)">{{ $sz }}</button>
                                @endforeach
                            </div>
                            <div id="sizeError" style="display:none;" class="error-msg">Please select a size.</div>
                        </div>

                        <div style="margin-bottom:32px;">
                            <div style="font-size:0.72rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--light-grey);margin-bottom:12px;">Select Colour *</div>
                            <div class="colour-grid">
                                @foreach ($product->variants->pluck('colour')->unique() as $col)
                                    <button type="button" class="colour-btn" onclick="selectColour('{{ $col }}', this)">{{ $col }}</button>
                                @endforeach
                            </div>
                            <div id="colourError" style="display:none;" class="error-msg">Please select a colour.</div>
                        </div>

                        <div style="margin-bottom:24px;">
                            <div style="font-size:0.72rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--light-grey);margin-bottom:12px;">Quantity</div>
                            <div style="display:flex;align-items:center;gap:12px;">
                                <button type="button" onclick="changeQty(-1)" style="background:var(--dark);border:1px solid var(--border);color:var(--white);width:36px;height:36px;border-radius:4px;font-size:1.2rem;cursor:pointer;">−</button>
                                <input type="number" name="quantity" id="qtyInput" value="1" min="1" max="{{ $product->stock_qty }}" class="form-control" style="width:70px;text-align:center;">
                                <button type="button" onclick="changeQty(1)" style="background:var(--dark);border:1px solid var(--border);color:var(--white);width:36px;height:36px;border-radius:4px;font-size:1.2rem;cursor:pointer;">+</button>
                            </div>
                        </div>

                        @guest
                            <a href="{{ route('login') }}" class="btn btn-primary btn-full" style="font-size:0.9rem;padding:18px;">Login to Add to Cart</a>
                        @else
                            <button type="submit" class="btn btn-primary btn-full" style="font-size:0.9rem;padding:18px;">🛒 Add to Cart</button>
                        @endguest
                    </form>
                @else
                    <div class="btn btn-dark btn-full" style="opacity:0.5;cursor:not-allowed;justify-content:center;padding:18px;">Out of Stock</div>
                @endif

                <div style="margin-top:32px;padding-top:24px;border-top:1px solid var(--border);">
                    <div style="display:flex;gap:24px;flex-wrap:wrap;font-size:0.78rem;color:var(--grey);">
                        <span>📦 Free shipping above RM150</span>
                        <span>🔄 7-day returns</span>
                        <span>✅ Authentic product</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@php
$variantsData = $product->variants->map(function($v) {
    return [
        'variant_id' => $v->variant_id,
        'size' => $v->size,
        'colour' => $v->colour,
        'stock_qty' => $v->stock_qty,
    ];
})->values();
@endphp
<script>
const variants = @json($variantsData);

let selectedSize = null;
let selectedColour = null;

function updateVariantId() {
    const match = variants.find(v => v.size === selectedSize && v.colour === selectedColour);
    document.getElementById('selectedVariantId').value = match ? match.variant_id : '';
}

function selectSize(size, btn) {
    document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
    selectedSize = size;
    document.getElementById('sizeError').style.display = 'none';
    updateVariantId();
}

function selectColour(colour, btn) {
    document.querySelectorAll('.colour-btn').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
    selectedColour = colour;
    document.getElementById('colourError').style.display = 'none';
    updateVariantId();
}

function changeQty(delta) {
    const inp = document.getElementById('qtyInput');
    inp.value = Math.max(1, Math.min({{ $product->stock_qty }}, parseInt(inp.value) + delta));
}

document.getElementById('addToCartForm')?.addEventListener('submit', function(e) {
    let valid = true;
    if (!selectedSize) { document.getElementById('sizeError').style.display = 'block'; valid = false; }
    if (!selectedColour) { document.getElementById('colourError').style.display = 'block'; valid = false; }
    if (!document.getElementById('selectedVariantId').value) { valid = false; }
    if (!valid) e.preventDefault();
});
</script>
@endsection