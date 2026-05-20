@extends('admin.layouts.app')
@section('title', 'Order #' . str_pad($order->order_id, 4, '0', STR_PAD_LEFT))
@section('page_title', 'Orders / Detail')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Order <span style="color:var(--accent);">#{{ str_pad($order->order_id, 4, '0', STR_PAD_LEFT) }}</span></h1>
        <p class="page-subtitle">{{ \Carbon\Carbon::parse($order->order_date)->format('d F Y, H:i') }}</p>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="btn-outline-dim">
        <i class="bi bi-arrow-left"></i> Back to Orders
    </a>
</div>

<div class="row g-4">
    {{-- Left: items + history --}}
    <div class="col-12 col-lg-8">

        {{-- Order items --}}
        <div class="card-dark mb-4">
            <div class="card-header-dark">Order Items ({{ $order->items->count() }})</div>
            <table class="table-dark-custom">
                <thead>
                    <tr>
                        <th></th>
                        <th>Product</th>
                        <th>Variant</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    @php $variant = $item->variant; $product = $variant?->product; $img = $product?->images->first(); @endphp
                    <tr>
                        <td style="width:52px;">
                            @if($img)
                                <img src="{{ asset('storage/'.$img->image_url) }}" class="product-thumb" alt="">
                            @else
                                <div class="product-thumb" style="display:flex;align-items:center;justify-content:center;background:var(--bg-elevated);color:var(--text-muted);">
                                    <i class="bi bi-image"></i>
                                </div>
                            @endif
                        </td>
                        <td style="color:var(--text-primary);font-weight:500;">{{ $product?->product_name ?? 'N/A' }}</td>
                        <td style="font-size:12px;color:var(--text-muted);">{{ $variant?->size }} / {{ $variant?->colour }}</td>
                        <td class="mono">{{ $item->quantity }}</td>
                        <td class="mono">RM {{ number_format($item->price_at_purchase, 2) }}</td>
                        <td class="mono" style="color:var(--accent);">RM {{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" style="text-align:right;font-family:var(--font-display);font-size:12px;letter-spacing:.1em;color:var(--text-muted);padding:14px 16px;">
                            TOTAL
                        </td>
                        <td class="mono" style="color:var(--accent);font-size:16px;font-weight:700;padding:14px 16px;">
                            RM {{ number_format($order->total_amount, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Order history timeline --}}
        <div class="card-dark">
            <div class="card-header-dark">Status History</div>
            <div class="card-body-dark">
                @forelse($order->histories->sortByDesc('updated_at') as $hist)
                <div style="display:flex;gap:14px;align-items:flex-start;margin-bottom:16px;">
                    <div style="width:8px;height:8px;border-radius:50%;background:var(--accent);margin-top:6px;flex-shrink:0;"></div>
                    <div>
                        <span class="badge-status badge-{{ strtolower($hist->status) }}" style="font-size:11px;">{{ $hist->status }}</span>
                        <div style="font-size:12px;color:var(--text-muted);margin-top:4px;">
                            {{ \Carbon\Carbon::parse($hist->updated_at)->format('d M Y, H:i') }}
                            @if($hist->note) — {{ $hist->note }} @endif
                        </div>
                    </div>
                </div>
                @empty
                <p style="color:var(--text-muted);font-size:13px;">No history recorded yet.</p>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Right: summary cards --}}
    <div class="col-12 col-lg-4">

        {{-- Update status --}}
        <div class="card-dark mb-4">
            <div class="card-header-dark">Update Order Status</div>
            <div class="card-body-dark">
                <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
                    @csrf @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label-dark">New Status</label>
                        <select name="order_status" class="form-select-dark" required>
                            @foreach(['Pending','Processing','Packed','Shipped','Delivered','Cancelled'] as $s)
                                <option value="{{ $s }}" {{ $order->order_status === $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-dark">Note (optional)</label>
                        <input type="text" name="note" class="form-control-dark" placeholder="e.g. Dispatched via Pos Laju">
                    </div>
                    <button type="submit" class="btn-accent" style="width:100%;justify-content:center;">
                        <i class="bi bi-check2"></i> Update Status
                    </button>
                </form>
            </div>
        </div>

        {{-- Customer info --}}
        <div class="card-dark mb-4">
            <div class="card-header-dark">Customer</div>
            <div class="card-body-dark">
                <div style="margin-bottom:10px;">
                    <div style="font-size:11px;color:var(--text-muted);">Name</div>
                    <div style="font-size:14px;font-weight:500;">{{ $order->user->full_name ?? 'N/A' }}</div>
                </div>
                <div style="margin-bottom:10px;">
                    <div style="font-size:11px;color:var(--text-muted);">Email</div>
                    <div style="font-size:13px;">{{ $order->user->email ?? 'N/A' }}</div>
                </div>
                <div>
                    <div style="font-size:11px;color:var(--text-muted);">Phone</div>
                    <div style="font-size:13px;">{{ $order->user->phone_number ?? 'N/A' }}</div>
                </div>
            </div>
        </div>

        {{-- Delivery address --}}
        <div class="card-dark mb-4">
            <div class="card-header-dark">Delivery Address</div>
            <div class="card-body-dark" style="font-size:13px;line-height:1.8;color:var(--text-secondary);">
                @if($order->address)
                    <strong style="color:var(--text-primary);">{{ $order->address->recipient_name }}</strong><br>
                    {{ $order->address->phone_number }}<br>
                    {{ $order->address->address_line }}<br>
                    {{ $order->address->postcode }}, {{ $order->address->state }}
                @else
                    <span style="color:var(--text-muted);">No address on record.</span>
                @endif
            </div>
        </div>

        {{-- Payment info --}}
        <div class="card-dark">
            <div class="card-header-dark">Payment</div>
            <div class="card-body-dark">
                @if($order->payment)
                <div style="margin-bottom:8px;">
                    <div style="font-size:11px;color:var(--text-muted);">Method</div>
                    <div style="font-size:13px;">{{ $order->payment->payment_method }}</div>
                </div>
                <div style="margin-bottom:8px;">
                    <div style="font-size:11px;color:var(--text-muted);">Status</div>
                    <span class="badge-status badge-{{ strtolower($order->payment->payment_status) }}">
                        {{ $order->payment->payment_status }}
                    </span>
                </div>
                @if($order->payment->transaction_reference)
                <div>
                    <div style="font-size:11px;color:var(--text-muted);">Reference</div>
                    <div class="mono" style="font-size:12px;">{{ $order->payment->transaction_reference }}</div>
                </div>
                @endif
                @else
                    <span style="color:var(--text-muted);font-size:13px;">No payment record.</span>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
