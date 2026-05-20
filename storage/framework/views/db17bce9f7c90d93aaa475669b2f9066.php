<?php $pageTitle = 'Checkout' ?>
<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div class="container">
        <h1>Checkout</h1>
        <div class="breadcrumb"><a href="<?php echo e(route('home')); ?>">Home</a> <span>/</span> <a href="<?php echo e(route('cart.index')); ?>">Cart</a> <span>/</span> Checkout</div>
    </div>
</div>

<section class="section">
    <div class="container">
        <form method="POST" action="<?php echo e(route('checkout.store')); ?>">
            <?php echo csrf_field(); ?>
            <div style="display:grid;grid-template-columns:1.4fr 1fr;gap:40px;align-items:start;">

                <!-- Delivery Form -->
                <div>
                    <h3 style="color:var(--white);margin-bottom:24px;">Delivery Information</h3>

                    <div class="form-group">
                        <label class="form-label">Recipient Name *</label>
                        <input type="text" name="delivery_name" class="form-control <?php $__errorArgs = ['delivery_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('delivery_name', $user->name)); ?>">
                        <?php $__errorArgs = ['delivery_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="error-msg"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone Number *</label>
                        <input type="tel" name="delivery_phone" class="form-control <?php $__errorArgs = ['delivery_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('delivery_phone', $user->phone)); ?>">
                        <?php $__errorArgs = ['delivery_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="error-msg"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Delivery Address *</label>
                        <textarea name="delivery_address" rows="3" class="form-control <?php $__errorArgs = ['delivery_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('delivery_address', $user->address)); ?></textarea>
                        <?php $__errorArgs = ['delivery_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="error-msg"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Postcode *</label>
                            <input type="text" name="delivery_postcode" maxlength="5" class="form-control <?php $__errorArgs = ['delivery_postcode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('delivery_postcode', $user->postcode)); ?>">
                            <?php $__errorArgs = ['delivery_postcode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="error-msg"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="form-group">
                            <label class="form-label">State *</label>
                            <select name="delivery_state" class="form-control <?php $__errorArgs = ['delivery_state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">— Select State —</option>
                                <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($s); ?>" <?php echo e(old('delivery_state', $user->state) === $s ? 'selected' : ''); ?>><?php echo e($s); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['delivery_state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="error-msg"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Payment Method *</label>
                        <div style="display:flex;gap:16px;flex-wrap:wrap;margin-top:8px;">
                            <?php $__currentLoopData = ['Cash on Delivery', 'Bank Transfer']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;background:var(--darker);border:1px solid var(--border);border-radius:4px;padding:14px 20px;">
                                    <input type="radio" name="payment_method" value="<?php echo e($pm); ?>" <?php echo e(old('payment_method') === $pm ? 'checked' : ''); ?> style="accent-color:var(--accent);">
                                    <span style="font-size:0.85rem;font-weight:700;color:var(--white);"><?php echo e($pm); ?></span>
                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="error-msg"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Order Notes (Optional)</label>
                        <textarea name="notes" rows="2" class="form-control" placeholder="Any special instructions..."><?php echo e(old('notes')); ?></textarea>
                    </div>
                </div>

                <!-- Order Summary -->
                <div>
                    <div class="cart-summary">
                        <h3 style="color:var(--white);margin-bottom:20px;">Order Summary</h3>
                        <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid var(--border);gap:12px;">
                                <div style="display:flex;gap:10px;align-items:center;">
                                    <span style="font-size:1.5rem;"><?php echo e(optional($item->variant->product)->emoji ?? '🛍️'); ?></span>
                                    <div>
                                        <div style="font-size:0.8rem;font-weight:700;color:var(--white);"><?php echo e(optional($item->variant->product)->product_name); ?></div>
                                        <div style="font-size:0.72rem;color:var(--grey);"><?php echo e($item->size); ?> · <?php echo e($item->colour); ?> × <?php echo e($item->quantity); ?></div>
                                    </div>
                                </div>
                                <div style="color:var(--accent);font-weight:700;">RM <?php echo e(number_format($item->variant->product->base_price * $item->quantity, 2)); ?></div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <div class="summary-row" style="margin-top:8px;">
                            <span style="color:var(--grey);">Subtotal</span>
                            <span style="color:var(--white);">RM <?php echo e(number_format($subtotal, 2)); ?></span>
                        </div>
                        <div class="summary-row">
                            <span style="color:var(--grey);">Shipping</span>
                            <span style="color:<?php echo e($shipping === 0 ? 'var(--accent)' : 'var(--white)'); ?>;"><?php echo e($shipping === 0 ? 'FREE' : 'RM ' . number_format($shipping, 2)); ?></span>
                        </div>
                        <div class="summary-total">
                            <span>Total</span>
                            <span>RM <?php echo e(number_format($total, 2)); ?></span>
                        </div>
                        <button type="submit" class="btn btn-primary btn-full" style="margin-top:24px;">Place Order →</button>
                        <a href="<?php echo e(route('cart.index')); ?>" class="btn btn-dark btn-full" style="margin-top:8px;">← Back to Cart</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Downloads\drip-culture-fixed (4)\drip-culture\resources\views/checkout/index.blade.php ENDPATH**/ ?>