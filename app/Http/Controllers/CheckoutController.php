<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    private array $states = [
        'Johor','Kedah','Kelantan','Melaka','Negeri Sembilan','Pahang',
        'Penang','Perak','Perlis','Sabah','Sarawak','Selangor',
        'Terengganu','Kuala Lumpur','Putrajaya','Labuan',
    ];

    public function index()
    {
        $cartItems = Cart::with(['variant.product.images'])
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('info', 'Your cart is empty.');
        }

        $subtotal = $cartItems->sum(fn($i) =>
    ($i->variant->product->base_price + $i->variant->additional_price) * $i->quantity
);
        $shipping = $subtotal >= 150 ? 0 : 10;
        $total    = $subtotal + $shipping;
        $user     = Auth::user();
        $states   = $this->states;

        return view('checkout.index', compact('cartItems', 'subtotal', 'shipping', 'total', 'user', 'states'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'delivery_name'     => 'required|string|min:3',
            'delivery_phone'    => 'required|regex:/^01[0-9]{8,9}$/',
            'delivery_address'  => 'required|string|min:10',
            'delivery_postcode' => 'required|regex:/^\d{5}$/',
            'delivery_state'    => 'required|in:' . implode(',', $this->states),
            'payment_method'    => 'required|in:Cash on Delivery,Bank Transfer',
        ], [
            'delivery_phone.regex'    => 'Enter valid Malaysian phone number.',
            'delivery_postcode.regex' => 'Postcode must be 5 digits.',
        ]);

        $uid       = Auth::id();
        $cartItems = Cart::with('variant.product')->where('user_id', $uid)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $subtotal = $cartItems->sum(fn($i) => $i->variant->price * $i->quantity);
        $shipping = $subtotal >= 150 ? 0 : 10;
        $total    = $subtotal + $shipping;

        DB::transaction(function () use ($request, $uid, $cartItems, $total, $shipping) {
            // Create delivery address record
            $address = Address::create([
                'user_id'        => $uid,
                'recipient_name' => $request->delivery_name,
                'phone_number'   => $request->delivery_phone,
                'address_line'   => $request->delivery_address,
                'postcode'       => $request->delivery_postcode,
                'state'          => $request->delivery_state,
                'is_default'     => 0,
            ]);

            $order = Order::create([
                'user_id'      => $uid,
                'address_id'   => $address->address_id,
                'order_date'   => now(),
                'total_amount' => $total,
                'order_status' => 'Pending',
            ]);

            foreach ($cartItems as $item) {
                $price = $item->variant->product->base_price + $item->variant->additional_price;
                OrderItem::create([
                    'order_id'          => $order->order_id,
                    'variant_id'        => $item->variant_id,
                    'quantity'          => $item->quantity,
                    'price_at_purchase' => $price,
                    'subtotal'          => $price * $item->quantity,
                ]);

                // Decrement stock
                $item->variant->decrement('stock_qty', $item->quantity);
            }

            // Create payment record
            Payment::create([
                'order_id'       => $order->order_id,
                'payment_method' => $request->payment_method,
                'payment_status' => 'Pending',
                'amount'         => $total,
                'payment_date'   => now(),
            ]);

            Cart::where('user_id', $uid)->delete();

            session(['last_order_id' => $order->order_id]);
        });

        return redirect()->route('checkout.confirmation');
    }

    public function confirmation()
    {
        $orderId = session('last_order_id');
        if (!$orderId) return redirect()->route('home');

        $order = Order::with(['items.variant.product', 'payment', 'address'])->findOrFail($orderId);
        abort_if($order->user_id !== Auth::id(), 403);

        return view('checkout.confirmation', compact('order'));
    }
}
