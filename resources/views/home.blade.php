@extends('layouts.app')
@section('content')

<!-- HERO -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <div class="hero-tag">⚡ New Drop — SS2025 Collection</div>
            <h1>Wear Your <span class="accent">Identity.</span></h1>
            <p>Premium streetwear and urban fashion for those who move differently. Limited drops. Unlimited style.</p>
            <div class="hero-actions">
                <a href="{{ route('products.index') }}" class="btn btn-primary">Shop Now →</a>
                <a href="#featured" class="btn btn-outline">View Collection</a>
            </div>
            <div class="hero-stats">
                <div><div class="hero-stat-num">200+</div><div class="hero-stat-label">Products</div></div>
                <div><div class="hero-stat-num">5K+</div><div class="hero-stat-label">Customers</div></div>
                <div><div class="hero-stat-num">100%</div><div class="hero-stat-label">Authentic</div></div>
            </div>
        </div>
    </div>
</section>

<!-- MARQUEE STRIP -->
<section style="background:var(--accent);padding:16px 0;overflow:hidden;">
    <div style="display:flex;gap:40px;white-space:nowrap;animation:marquee 18s linear infinite;">
        @for ($i = 0; $i < 3; $i++)
            <span style="font-family:'Bebas Neue',sans-serif;font-size:1.1rem;letter-spacing:3px;color:var(--black);font-weight:900;">T-SHIRTS</span>
            <span style="color:var(--black);">✦</span>
            <span style="font-family:'Bebas Neue',sans-serif;font-size:1.1rem;letter-spacing:3px;color:var(--black);font-weight:900;">HOODIES</span>
            <span style="color:var(--black);">✦</span>
            <span style="font-family:'Bebas Neue',sans-serif;font-size:1.1rem;letter-spacing:3px;color:var(--black);font-weight:900;">CAPS</span>
            <span style="color:var(--black);">✦</span>
            <span style="font-family:'Bebas Neue',sans-serif;font-size:1.1rem;letter-spacing:3px;color:var(--black);font-weight:900;">PANTS</span>
            <span style="color:var(--black);">✦</span>
            <span style="font-family:'Bebas Neue',sans-serif;font-size:1.1rem;letter-spacing:3px;color:var(--black);font-weight:900;">ACCESSORIES</span>
            <span style="color:var(--black);">✦</span>
        @endfor
    </div>
    <style>@keyframes marquee{0%{transform:translateX(0)}100%{transform:translateX(-33.33%)}}</style>
</section>

<!-- FEATURED PRODUCTS -->
<section class="section" id="featured">
    <div class="container">
        <p class="section-sub" style="color:var(--accent);font-weight:700;letter-spacing:2px;text-transform:uppercase;margin-bottom:8px;">Featured Drops</p>
        <h2 class="section-title" style="color:var(--white);">This Season's Heat</h2>
        <div class="divider"></div>
        <div class="products-grid">
            @forelse ($featured as $p)
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
                        <span class="product-badge">Featured</span>
                    </div>
                    <div class="product-info">
                        <div class="product-cat">{{ $p->category->category_name }}</div>
                        <div class="product-name">{{ $p->product_name }}</div>
                        <div class="product-footer">
                            <div class="product-price">RM <span>{{ number_format($p->base_price, 2) }}</span></div>
                            <a href="{{ route('products.show', $p->product_id) }}" class="quick-add">View →</a>
                        </div>
                    </div>
                </div>
            @empty
                <p style="color:var(--grey);grid-column:1/-1;text-align:center;">No products yet. Check back soon!</p>
            @endforelse
        </div>
        <div style="text-align:center;margin-top:48px;">
            <a href="{{ route('products.index') }}" class="btn btn-primary">View All Products →</a>
        </div>
    </div>
</section>

<!-- ABOUT -->
<section class="section" id="about" style="background:var(--darker);">
    <div class="container">
        <div class="about-grid">
            <div>
                <div class="about-tag">Our Story</div>
                <h2 style="color:var(--white);margin-bottom:24px;">Born From The Streets</h2>
                <p class="about-text">DRIP CULTURE was founded by a group of streetwear enthusiasts who couldn't find local brands that truly understood the culture.</p>
                <p class="about-text">Every piece we design is a statement — crafted for comfort, made for the streets, built to last.</p>
                <div class="value-list">
                    <div class="value-item"><span class="value-icon">🔥</span><div><div class="value-title">Authentic Design</div><div class="value-desc">Every piece created with genuine street culture in mind.</div></div></div>
                    <div class="value-item"><span class="value-icon">🌱</span><div><div class="value-title">Quality Materials</div><div class="value-desc">Premium heavyweight cotton and sustainable fabrics.</div></div></div>
                    <div class="value-item"><span class="value-icon">💯</span><div><div class="value-title">100% Local</div><div class="value-desc">Designed and produced in Malaysia.</div></div></div>
                </div>
            </div>
            <div class="about-visual"><div class="about-visual-box">🏙️</div></div>
        </div>
    </div>
</section>

<!-- CATEGORIES -->
<section class="section">
    <div class="container">
        <p class="section-sub" style="color:var(--accent);font-weight:700;letter-spacing:2px;text-transform:uppercase;margin-bottom:8px;">Shop By Category</p>
        <h2 class="section-title" style="color:var(--white);">Find Your Vibe</h2>
        <div class="divider"></div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;">
            @foreach ([['cat'=>1,'emoji'=>'👕','name'=>'T-Shirts'],['cat'=>2,'emoji'=>'🧥','name'=>'Hoodies'],['cat'=>3,'emoji'=>'🧢','name'=>'Caps'],['cat'=>4,'emoji'=>'👖','name'=>'Pants'],['cat'=>5,'emoji'=>'🎒','name'=>'Accessories']] as $c)
                <a href="{{ route('products.index', ['cat' => $c['cat']]) }}" style="display:block;background:var(--darker);border:1px solid var(--border);border-radius:4px;padding:32px 16px;text-align:center;transition:all 0.25s;" onmouseover="this.style.borderColor='rgba(232,255,71,0.5)'" onmouseout="this.style.borderColor='var(--border)'">
                    <div style="font-size:2.5rem;margin-bottom:12px;">{{ $c['emoji'] }}</div>
                    <div style="font-weight:700;font-size:0.85rem;letter-spacing:1.5px;text-transform:uppercase;color:var(--white);">{{ $c['name'] }}</div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA -->
<section style="background:var(--accent);padding:80px 0;">
    <div class="container" style="text-align:center;">
        <h2 style="color:var(--black);margin-bottom:16px;">Ready To Drip?</h2>
        <p style="color:rgba(0,0,0,0.7);max-width:500px;margin:0 auto 32px;">Join thousands of streetwear enthusiasts.</p>
        @guest
            <a href="{{ route('register') }}" class="btn" style="background:var(--black);color:var(--accent);border-color:var(--black);">Create Account →</a>
        @else
            <a href="{{ route('products.index') }}" class="btn" style="background:var(--black);color:var(--accent);border-color:var(--black);">Shop Now →</a>
        @endguest
    </div>
</section>
@endsection