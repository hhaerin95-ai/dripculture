<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['order.user'])
            ->latest('payment_date');

        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        if ($request->filled('method')) {
            $query->where('payment_method', $request->method);
        }

        $payments = $query->paginate(15)->withQueryString();

        return view('admin.orders.payments', compact('payments'));
    }

    public function verify(Payment $payment)
    {
        $payment->update(['payment_status' => 'Successful']);

        // Also update the linked order status to Processing
        $payment->order()->update(['order_status' => 'Processing']);

        return back()->with('success', 'Payment verified successfully.');
    }

    public function reject(Payment $payment)
    {
        $payment->update(['payment_status' => 'Failed']);
        $payment->order()->update(['order_status' => 'Cancelled']);

        return back()->with('success', 'Payment rejected and order cancelled.');
    }
}
