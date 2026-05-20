@extends('admin.layouts.app')
@section('title', 'Payments')
@section('page_title', 'Payments')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Payments</h1>
        <p class="page-subtitle">Verify and manage customer payment records</p>
    </div>
</div>

{{-- Filter --}}
<div class="card-dark mb-4">
    <div class="card-body-dark">
        <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
            <div style="min-width:150px;">
                <label class="form-label-dark">Payment Status</label>
                <select name="status" class="form-select-dark">
                    <option value="">All</option>
                    <option value="Pending"    {{ request('status') === 'Pending'    ? 'selected' : '' }}>Pending</option>
                    <option value="Successful" {{ request('status') === 'Successful' ? 'selected' : '' }}>Successful</option>
                    <option value="Failed"     {{ request('status') === 'Failed'     ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div style="min-width:160px;">
                <label class="form-label-dark">Method</label>
                <select name="method" class="form-select-dark">
                    <option value="">All Methods</option>
                    <option value="Cash on Delivery" {{ request('method') === 'Cash on Delivery' ? 'selected' : '' }}>Cash on Delivery</option>
                    <option value="Bank Transfer"    {{ request('method') === 'Bank Transfer'    ? 'selected' : '' }}>Bank Transfer</option>
                </select>
            </div>
            <div style="display:flex;gap:8px;align-items:flex-end;padding-bottom:1px;">
                <button type="submit" class="btn-accent"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('admin.payments.index') }}" class="btn-outline-dim">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card-dark">
    <div style="overflow-x:auto;">
        <table class="table-dark-custom">
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Method</th>
                    <th>Amount</th>
                    <th>Reference</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td class="mono" style="color:var(--text-muted);">#{{ $payment->payment_id }}</td>
                    <td class="mono" style="color:var(--accent);">
                        <a href="{{ route('admin.orders.show', $payment->order) }}" style="color:inherit;text-decoration:none;">
                            #{{ str_pad($payment->order?->order_id, 4, '0', STR_PAD_LEFT) }}
                        </a>
                    </td>
                    <td>{{ $payment->order?->user?->full_name ?? 'N/A' }}</td>
                    <td style="font-size:12px;">{{ $payment->payment_method }}</td>
                    <td class="mono">RM {{ number_format($payment->amount, 2) }}</td>
                    <td class="mono" style="font-size:11px;color:var(--text-muted);">
                        {{ $payment->transaction_reference ?? '—' }}
                    </td>
                    <td style="font-size:12px;color:var(--text-muted);">
                        {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') : '—' }}
                    </td>
                    <td>
                        <span class="badge-status badge-{{ strtolower($payment->payment_status) }}">
                            {{ $payment->payment_status }}
                        </span>
                    </td>
                    <td>
                        @if($payment->payment_status === 'Pending')
                        <div style="display:flex;gap:6px;">
                            <form method="POST" action="{{ route('admin.payments.verify', $payment) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn-outline-dim" style="font-size:11px;padding:5px 10px;color:var(--success);border-color:rgba(0,230,118,.3);"
                                        onclick="return confirm('Mark this payment as Successful?')">
                                    <i class="bi bi-check2"></i> Verify
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.payments.reject', $payment) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn-danger-dim" style="font-size:11px;padding:5px 10px;"
                                        onclick="return confirm('Reject this payment and cancel the order?')">
                                    <i class="bi bi-x"></i> Reject
                                </button>
                            </form>
                        </div>
                        @else
                            <span style="color:var(--text-muted);font-size:12px;">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="9">
                    <div class="empty-state"><i class="bi bi-credit-card"></i><p>No payments found.</p></div>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($payments->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border);">
        {{ $payments->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
