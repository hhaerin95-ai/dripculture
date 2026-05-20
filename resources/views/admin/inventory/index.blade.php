@extends('admin.layouts.app')
@section('title', 'Inventory')
@section('page_title', 'Inventory')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Inventory</h1>
        <p class="page-subtitle">Stock management for all product variants</p>
    </div>
</div>

{{-- Filter --}}
<div class="card-dark mb-4">
    <div class="card-body-dark">
        <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
            <div style="flex:1;min-width:200px;">
                <label class="form-label-dark">Search</label>
                <input name="search" type="text" class="form-control-dark" placeholder="Product name or SKU..." value="{{ request('search') }}">
            </div>
            <div style="min-width:160px;">
                <label class="form-label-dark">Stock Level</label>
                <select name="stock_filter" class="form-select-dark">
                    <option value="">All</option>
                    <option value="out"      {{ request('stock_filter') === 'out'      ? 'selected' : '' }}>Out of Stock (0)</option>
                    <option value="low"      {{ request('stock_filter') === 'low'      ? 'selected' : '' }}>Low Stock (1–5)</option>
                    <option value="in_stock" {{ request('stock_filter') === 'in_stock' ? 'selected' : '' }}>In Stock (>5)</option>
                </select>
            </div>
            <div style="display:flex;gap:8px;align-items:flex-end;padding-bottom:1px;">
                <button type="submit" class="btn-accent"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('admin.inventory.index') }}" class="btn-outline-dim">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-lg-8">
        <div class="card-dark">
            <div style="overflow-x:auto;">
                <table class="table-dark-custom">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Size</th>
                            <th>Colour</th>
                            <th>Stock</th>
                            <th>Adjust</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($variants as $variant)
                        @php
                            $product = $variant->product;
                            $img = $product?->images->first();
                            $stockColor = $variant->stock_qty == 0 ? 'var(--danger)' : ($variant->stock_qty <= 5 ? 'var(--warning)' : 'var(--success)');
                        @endphp
                        <tr>
                            <td style="width:52px;">
                                @if($img)
                                    <img src="{{ asset('storage/'.$img->image_url) }}" class="product-thumb" alt="">
                                @else
                                    <div class="product-thumb" style="display:flex;align-items:center;justify-content:center;background:var(--bg-elevated);color:var(--text-muted);"><i class="bi bi-image"></i></div>
                                @endif
                            </td>
                            <td style="color:var(--text-primary);font-weight:500;">{{ Str::limit($product?->product_name ?? 'N/A', 28) }}</td>
                            <td class="mono" style="font-size:11px;color:var(--text-muted);">{{ $variant->sku_code }}</td>
                            <td style="font-size:13px;">{{ $variant->size }}</td>
                            <td style="font-size:13px;">{{ $variant->colour }}</td>
                            <td>
                                <span class="mono" style="font-size:18px;font-weight:700;color:{{ $stockColor }};">{{ $variant->stock_qty }}</span>
                                @if($variant->stock_qty == 0)
                                    <span class="badge-status badge-cancelled" style="font-size:10px;margin-left:6px;">OUT</span>
                                @elseif($variant->stock_qty <= 5)
                                    <span class="badge-status badge-pending" style="font-size:10px;margin-left:6px;">LOW</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn-outline-dim" style="font-size:11px;padding:5px 10px;"
                                        onclick="openStockModal({{ $variant->variant_id }}, '{{ addslashes($product?->product_name) }}', '{{ $variant->size }}', '{{ $variant->colour }}', {{ $variant->stock_qty }})">
                                    <i class="bi bi-plus-slash-minus"></i> Adjust
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7"><div class="empty-state"><i class="bi bi-stack"></i><p>No variants found.</p></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($variants->hasPages())
            <div style="padding:16px 20px;border-top:1px solid var(--border);">
                {{ $variants->withQueryString()->links() }}
            </div>
            @endif
        </div>
    </div>

    {{-- Recent stock log --}}
    <div class="col-12 col-lg-4">
        <div class="card-dark">
            <div class="card-header-dark">Recent Adjustments</div>
            <div style="overflow-y:auto;max-height:480px;">
                @forelse($recentLogs as $log)
                <div style="padding:12px 20px;border-bottom:1px solid var(--border);">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <div style="font-size:13px;color:var(--text-primary);">{{ Str::limit($log->variant?->product?->product_name ?? 'N/A', 22) }}</div>
                        <span class="mono" style="font-size:14px;font-weight:700;color:{{ $log->quantity_changed >= 0 ? 'var(--success)' : 'var(--danger)' }};">
                            {{ $log->quantity_changed >= 0 ? '+' : '' }}{{ $log->quantity_changed }}
                        </span>
                    </div>
                    <div style="font-size:11px;color:var(--text-muted);margin-top:3px;">
                        {{ $log->change_type }} — {{ \Carbon\Carbon::parse($log->log_date)->format('d M Y, H:i') }}
                    </div>
                </div>
                @empty
                <div class="empty-state" style="padding:32px;"><i class="bi bi-clock-history" style="font-size:32px;"></i><p>No adjustments yet.</p></div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Stock Adjust Modal --}}
<div id="stockModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:2000;align-items:center;justify-content:center;">
    <div style="background:var(--bg-surface);border:1px solid var(--border);border-radius:4px;width:100%;max-width:400px;margin:16px;">
        <div style="padding:20px 24px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
            <span style="font-family:var(--font-display);font-size:12px;letter-spacing:.15em;text-transform:uppercase;color:var(--text-secondary);">Adjust Stock</span>
            <button onclick="closeStockModal()" style="background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:18px;">&times;</button>
        </div>
        <form id="stockForm" method="POST">
            @csrf @method('PATCH')
            <div style="padding:24px;">
                <div style="margin-bottom:16px;padding:12px;background:var(--bg-elevated);border-radius:4px;">
                    <div id="modalProductName" style="font-weight:600;color:var(--text-primary);font-size:14px;"></div>
                    <div id="modalVariantInfo" style="font-size:12px;color:var(--text-muted);margin-top:2px;"></div>
                    <div style="margin-top:8px;font-size:12px;color:var(--text-muted);">Current Stock:
                        <span id="modalCurrentStock" class="mono" style="color:var(--text-primary);font-size:16px;font-weight:700;margin-left:4px;"></span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label-dark">Adjustment Type</label>
                    <select name="change_type" class="form-select-dark" required>
                        <option value="Restock">Restock (+)</option>
                        <option value="Adjustment">Adjustment (±)</option>
                        <option value="Correction">Correction (±)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label-dark">Quantity Change</label>
                    <input type="number" name="quantity" class="form-control-dark" placeholder="e.g. +10 or -3" required>
                    <div style="font-size:11px;color:var(--text-muted);margin-top:4px;">Use negative number to reduce stock.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label-dark">Note (optional)</label>
                    <input type="text" name="note" class="form-control-dark" placeholder="Reason for adjustment...">
                </div>
                <button type="submit" class="btn-accent" style="width:100%;justify-content:center;">
                    <i class="bi bi-check2"></i> Save Adjustment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openStockModal(variantId, productName, size, colour, currentStock) {
    document.getElementById('stockForm').action = `/admin/inventory/variants/${variantId}/stock`;
    document.getElementById('modalProductName').textContent = productName;
    document.getElementById('modalVariantInfo').textContent = size + ' / ' + colour;
    document.getElementById('modalCurrentStock').textContent = currentStock;
    document.getElementById('stockModal').style.display = 'flex';
}
function closeStockModal() {
    document.getElementById('stockModal').style.display = 'none';
}
document.getElementById('stockModal').addEventListener('click', function(e) {
    if (e.target === this) closeStockModal();
});
</script>
@endpush
