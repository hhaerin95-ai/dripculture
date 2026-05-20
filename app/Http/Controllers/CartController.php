<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {

        $cartItems = Cart::with(['variant.product.images', 'variant.product.category'])
            ->where('user_id', Auth::id())
            ->get();

        $subtotal = $cartItems->sum(fn($item) => $item->variant->price * $item->quantity);
        $shipping = $subtotal >= 150 ? 0 : ($subtotal > 0 ? 10 : 0);
        $total    = $subtotal + $shipping;

        return view('cart.index', compact('cartItems', 'subtotal', 'shipping', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:variants,variant_id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $uid       = Auth::id();
        $variantId = $request->variant_id;
        $qty       = $request->quantity;

        $variant = Variant::findOrFail($variantId);

        if ($variant->stock_qty < $qty) {
            return back()->with('error', 'Not enough stock available.');
        }

        $existing = Cart::where('user_id', $uid)
            ->where('variant_id', $variantId)
            ->first();

        if ($existing) {
            $existing->increment('quantity', $qty);
        } else {
            Cart::create([
                'user_id'    => $uid,
                'variant_id' => $variantId,
                'quantity'   => $qty,
                'added_at'   => now(),
            ]);
        }

        return redirect()->route('cart.index')->with('success', '🛒 Item added to cart!');
    }

    public function update(Request $request, Cart $cart)
    {
        abort_if($cart->user_id !== Auth::id(), 403);
        $request->validate(['quantity' => 'required|integer|min:1']);
        $cart->update(['quantity' => $request->quantity]);
        return redirect()->route('cart.index');
    }

    public function remove(Cart $cart)
    {
        abort_if($cart->user_id !== Auth::id(), 403);
        $cart->delete();
        return redirect()->route('cart.index')->with('info', 'Item removed from cart.');
    }

    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();
        return redirect()->route('cart.index')->with('info', 'Cart cleared.');
    }
}
