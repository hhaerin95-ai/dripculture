<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sales Report {{ $dateFrom }} to {{ $dateTo }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #222; margin: 24px; }
        h1   { font-size: 20px; margin-bottom: 4px; }
        h2   { font-size: 13px; font-weight: normal; color: #555; margin-bottom: 24px; }
        .summary { display: flex; gap: 24px; margin-bottom: 24px; }
        .stat { border: 1px solid #ddd; padding: 12px 20px; border-radius: 4px; }
        .stat-label { font-size: 10px; text-transform: uppercase; color: #888; margin-bottom: 4px; }
        .stat-value { font-size: 20px; font-weight: 700; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f0f0f0; padding: 8px 10px; text-align: left; font-size: 11px; text-transform: uppercase; }
        td { padding: 8px 10px; border-bottom: 1px solid #eee; }
        .total-row td { font-weight: 700; background: #f9f9f9; }
        @media print { @page { margin: 16px; } }
    </style>
</head>
<body>
    <h1>DRIP CULTURE — Sales Report</h1>
    <h2>Period: {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }} to {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}
        @if($status) | Status: {{ $status }} @endif
    </h2>

    <div class="summary">
        <div class="stat">
            <div class="stat-label">Total Orders</div>
            <div class="stat-value">{{ $orders->count() }}</div>
        </div>
        <div class="stat">
            <div class="stat-label">Total Revenue</div>
            <div class="stat-value">RM {{ number_format($totalRevenue, 2) }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Payment Method</th>
                <th>Status</th>
                <th>Total (RM)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>#{{ str_pad($order->order_id, 4, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $order->user->full_name ?? 'N/A' }}</td>
                <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</td>
                <td>{{ $order->payment?->payment_method ?? '—' }}</td>
                <td>{{ $order->order_status }}</td>
                <td>{{ number_format($order->total_amount, 2) }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="5" style="text-align:right;">TOTAL REVENUE</td>
                <td>{{ number_format($totalRevenue, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <p style="margin-top:32px;font-size:11px;color:#888;">
        Generated: {{ now()->format('d M Y H:i') }} — DRIP CULTURE Admin System
    </p>

    <script>window.onload = () => window.print();</script>
</body>
</html>
