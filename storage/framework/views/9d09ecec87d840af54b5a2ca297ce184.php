<?php $pageTitle = 'Shopping Cart' ?>
<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div class="container">
        <h1>Shopping Cart</h1>
        <div class="breadcrumb"><a href="<?php echo e(route('home')); ?>">Home</a> <span>/</span> Cart</div>
    </div>
</div>

<section class="section">
    <div class="container">
        <?php if($cartItems->isEmpty()): ?>
            <div style="text-align:center;padding:80px 0;">
                <div style="font-size:5rem;margin-bottom:24px;">🛒</div>
                <h2 style="color:var(--white);margin-bottom:12px;">Your Cart is Empty</h2>
                <p style="margin-bottom:32px;">Looks like you haven't added anything yet. Let's fix that.</p>
                <a href="<?php echo e(route('products.index')); ?>" class="btn btn-primary">Start Shopping →</a>
            </div>
        <?php else: ?>
            <div class="cart-layout">
                <div>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
                        <h3 style="color:var(--white);">Cart Items (<?php echo e($cartItems->count()); ?>)</h3>
                        <form method="POST" action="<?php echo e(route('cart.clear')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Clear entire cart?')">Clear All</button>
                        </form>
                    </div>
                    <table class="cart-table">
                        <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Total</th><th></th></tr></thead>
                        <tbody>
<?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<tr>
<td>
<div style="display:flex;align-items:center;gap:16px;">
<div style="width:72px;height:72px;background:var(--darker);border:1px solid var(--border);border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:2rem;flex-shrink:0;">
<?php echo e(optional($item->variant->product)->emoji ?? '🛍️'); ?>

</div>

<div>
<div class="cart-item-name">
<?php echo e(optional($item->variant->product)->product_name ?? 'Product removed'); ?>

</div>

<div class="cart-item-meta">
Size: <?php echo e($item->variant->size ?? '-'); ?>

&nbsp;|&nbsp;
Colour: <?php echo e($item->variant->colour ?? '-'); ?>

</div>
</div>
</div>
</td>

<td style="color:var(--grey);">
RM <?php echo e(number_format(optional($item->variant->product)->base_price ?? 0, 2)); ?>

</td>

<td>
<form method="POST" action="<?php echo e(route('cart.update', $item->cart_id)); ?>" style="display:inline;">
<?php echo csrf_field(); ?>
<div style="display:flex;gap:4px;align-items:center;">
<input type="number" name="quantity" value="<?php echo e($item->quantity); ?>" min="1" max="99" class="qty-input">
<button type="submit" style="background:none;border:none;color:var(--accent);cursor:pointer;font-size:0.9rem;">✓</button>
</div>
</form>
</td>

<td style="color:var(--accent);font-weight:700;font-family:'Bebas Neue',sans-serif;font-size:1.2rem;">
RM <?php echo e(number_format((optional($item->variant->product)->base_price ?? 0) * $item->quantity, 2)); ?>

</td>

<td>
<form method="POST" action="<?php echo e(route('cart.remove', $item->cart_id)); ?>">
<?php echo csrf_field(); ?>
<button type="submit" style="background:none;border:none;color:var(--grey);cursor:pointer;font-size:1.2rem;">✕</button>
</form>
</td>

</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</tbody>
                    </table>
                </div>

                <div>
                    <div class="cart-summary">
                        <h3 style="color:var(--white);margin-bottom:20px;">Order Summary</h3>
                        <div class="summary-row">
                            <span style="color:var(--grey);">Subtotal</span>
                            <span style="color:var(--white);">RM <?php echo e(number_format($subtotal, 2)); ?></span>
                        </div>
                        <div class="summary-row">
                            <span style="color:var(--grey);">Shipping</span>
                            <span style="color:<?php echo e($shipping === 0 ? 'var(--accent)' : 'var(--white)'); ?>;">
                                <?php echo e($shipping === 0 ? 'FREE ✅' : 'RM ' . number_format($shipping, 2)); ?>

                            </span>
                        </div>
                        <?php if($shipping > 0): ?>
                            <div style="font-size:0.75rem;color:var(--grey);text-align:right;margin-top:4px;">Add RM <?php echo e(number_format(150 - $subtotal, 2)); ?> more for free shipping</div>
                        <?php endif; ?>
                        <div class="summary-total">
                            <span>Total</span>
                            <span>RM <?php echo e(number_format($total, 2)); ?></span>
                        </div>
                        <a href="<?php echo e(route('checkout.index')); ?>" class="btn btn-primary btn-full" style="margin-top:24px;">Proceed to Checkout →</a>
                        <a href="<?php echo e(route('products.index')); ?>" class="btn btn-dark btn-full" style="margin-top:8px;">Continue Shopping</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Downloads\drip-culture-fixed (4)\drip-culture\resources\views/cart/index.blade.php ENDPATH**/ ?>