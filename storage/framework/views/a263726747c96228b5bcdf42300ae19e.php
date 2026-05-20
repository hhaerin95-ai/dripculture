<?php $pageTitle = 'Order Confirmed' ?>
<?php $__env->startSection('content'); ?>

<section class="section" style="min-height:80vh;display:flex;align-items:center;">
    <div class="container">
        <div class="confirm-card">
            <div class="confirm-icon">✅</div>
            <h2 style="color:var(--white);margin-bottom:8px;">Order Confirmed!</h2>
            <p style="margin-bottom:24px;">Thank you for your order. We'll get it ready for you ASAP.</p>

            <div style="background:var(--dark);border:1px solid rgba(232,255,71,0.2);border-radius:4px;padding:20px;margin-bottom:24px;">
                <div style="font-size:0.72rem;color:var(--grey);letter-spacing:1.5px;text-transform:uppercase;margin-bottom:8px;">Order ID</div>
                <div class="confirm-order-id"><?php echo e($order->formatted_id); ?></div>
            </div>

            <div style="text-align:left;margin-bottom:24px;">
                <div style="font-size:0.72rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--grey);margin-bottom:12px;">Items Ordered</div>
                <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border);font-size:0.85rem;">
                        <span style="color:var(--text);"><?php echo e($item->variant->product->product_name); ?> (<?php echo e($item->variant->size); ?>, <?php echo e($item->variant->colour); ?>) × <?php echo e($item->quantity); ?></span>
                        <span style="color:var(--accent);">RM <?php echo e(number_format($item->price_at_purchase * $item->quantity, 2)); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <div style="display:flex;justify-content:space-between;padding:12px 0;font-family:'Bebas Neue',sans-serif;font-size:1.4rem;color:var(--accent);">
                    <span>TOTAL</span>
                    <span>RM <?php echo e(number_format($order->total_amount, 2)); ?></span>
                </div>
            </div>

            <div style="text-align:left;margin-bottom:32px;">
                <div style="font-size:0.72rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--grey);margin-bottom:12px;">Delivery Details</div>
                <p style="font-size:0.85rem;color:var(--text);line-height:1.7;">
                    <?php echo e($order->delivery_name); ?><br>
                    <?php echo e($order->delivery_phone); ?><br>
                    <?php echo e($order->delivery_address); ?><br>
                    <?php echo e($order->delivery_postcode); ?>, <?php echo e($order->delivery_state); ?>

                </p>
            </div>

            <div style="background:rgba(232,255,71,0.08);border:1px solid rgba(232,255,71,0.2);border-radius:4px;padding:16px;margin-bottom:32px;font-size:0.82rem;color:var(--grey);">
                📦 Estimated delivery: 3–7 business days<br>
                💳 Payment: <?php echo e($order->payment_method); ?>

            </div>

            <div style="display:flex;gap:12px;flex-wrap:wrap;justify-content:center;">
                <a href="<?php echo e(route('orders.index')); ?>" class="btn btn-primary">Track My Orders</a>
                <a href="<?php echo e(route('products.index')); ?>" class="btn btn-dark">Keep Shopping</a>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Downloads\drip-culture-fixed (4)\drip-culture\resources\views/checkout/confirmation.blade.php ENDPATH**/ ?>