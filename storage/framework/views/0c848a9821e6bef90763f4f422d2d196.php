<?php $pageTitle = $product->product_name ?>
<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div class="container">
        <div class="breadcrumb">
            <a href="<?php echo e(route('home')); ?>">Home</a> <span>/</span>
            <a href="<?php echo e(route('products.index')); ?>">Shop</a> <span>/</span>
            <a href="<?php echo e(route('products.index', ['cat' => $product->category_id])); ?>"><?php echo e($product->category->category_name); ?></a> <span>/</span>
            <?php echo e($product->product_name); ?>

        </div>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="product-detail-grid">
            <!-- Image -->
            <div>
                <div style="background:var(--darker);border:1px solid var(--border);border-radius:4px;aspect-ratio:1/1;overflow:hidden;display:flex;align-items:center;justify-content:center;">
                    <?php $primaryImg = $product->images->firstWhere('is_primary', 1) ?? $product->images->first(); ?>
                    <?php if($primaryImg): ?>
                        <img src="<?php echo e(asset('storage/' . $primaryImg->image_url)); ?>"
                             alt="<?php echo e($product->product_name); ?>"
                             style="width:100%;height:100%;object-fit:cover;">
                    <?php else: ?>
                        <div style="font-size:6rem;">👕</div>
                    <?php endif; ?>
                </div>
                <div style="display:flex;gap:8px;margin-top:12px;">
                    <?php $__empty_1 = true; $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div style="flex:1;aspect-ratio:1/1;background:var(--darker);border:1px solid var(--border);border-radius:4px;overflow:hidden;cursor:pointer;">
                            <img src="<?php echo e(asset('storage/' . $img->image_url)); ?>"
                                 style="width:100%;height:100%;object-fit:cover;">
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <?php for($i = 0; $i < 4; $i++): ?>
                            <div style="flex:1;aspect-ratio:1/1;background:var(--darker);border:1px solid var(--border);border-radius:4px;"></div>
                        <?php endfor; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Info -->
            <div>
                <div style="font-size:0.72rem;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:var(--accent);margin-bottom:12px;"><?php echo e($product->category->category_name); ?></div>
                <h1 style="font-size:2rem;color:var(--white);margin-bottom:8px;text-transform:uppercase;letter-spacing:1px;"><?php echo e($product->product_name); ?></h1>
                <div class="detail-price">RM <?php echo e(number_format($product->base_price, 2)); ?></div>
                <p style="color:var(--grey);font-size:0.9rem;line-height:1.7;margin-bottom:24px;"><?php echo e($product->description); ?></p>

                <div class="detail-stock <?php echo e($product->isLowStock() ? 'low' : ''); ?>">
                    <?php if($product->stock_qty > 10): ?>
                        ✅ In Stock (<?php echo e($product->stock_qty); ?> available)
                    <?php elseif($product->stock_qty > 0): ?>
                        ⚠️ Low Stock — Only <?php echo e($product->stock_qty); ?> left!
                    <?php else: ?>
                        ❌ Out of Stock
                    <?php endif; ?>
                </div>

                <?php if($product->stock_qty > 0): ?>
                    <form method="POST" action="<?php echo e(route('cart.add')); ?>" id="addToCartForm">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="variant_id" id="selectedVariantId" value="">

                        <div style="margin-bottom:24px;">
                            <div style="font-size:0.72rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--light-grey);margin-bottom:12px;">Select Size *</div>
                            <div class="size-grid">
                                <?php $__currentLoopData = $product->variants->pluck('size')->unique(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <button type="button" class="size-btn" onclick="selectSize('<?php echo e($sz); ?>', this)"><?php echo e($sz); ?></button>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <div id="sizeError" style="display:none;" class="error-msg">Please select a size.</div>
                        </div>

                        <div style="margin-bottom:32px;">
                            <div style="font-size:0.72rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--light-grey);margin-bottom:12px;">Select Colour *</div>
                            <div class="colour-grid">
                                <?php $__currentLoopData = $product->variants->pluck('colour')->unique(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <button type="button" class="colour-btn" onclick="selectColour('<?php echo e($col); ?>', this)"><?php echo e($col); ?></button>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <div id="colourError" style="display:none;" class="error-msg">Please select a colour.</div>
                        </div>

                        <div style="margin-bottom:24px;">
                            <div style="font-size:0.72rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--light-grey);margin-bottom:12px;">Quantity</div>
                            <div style="display:flex;align-items:center;gap:12px;">
                                <button type="button" onclick="changeQty(-1)" style="background:var(--dark);border:1px solid var(--border);color:var(--white);width:36px;height:36px;border-radius:4px;font-size:1.2rem;cursor:pointer;">−</button>
                                <input type="number" name="quantity" id="qtyInput" value="1" min="1" max="<?php echo e($product->stock_qty); ?>" class="form-control" style="width:70px;text-align:center;">
                                <button type="button" onclick="changeQty(1)" style="background:var(--dark);border:1px solid var(--border);color:var(--white);width:36px;height:36px;border-radius:4px;font-size:1.2rem;cursor:pointer;">+</button>
                            </div>
                        </div>

                        <?php if(auth()->guard()->guest()): ?>
                            <a href="<?php echo e(route('login')); ?>" class="btn btn-primary btn-full" style="font-size:0.9rem;padding:18px;">Login to Add to Cart</a>
                        <?php else: ?>
                            <button type="submit" class="btn btn-primary btn-full" style="font-size:0.9rem;padding:18px;">🛒 Add to Cart</button>
                        <?php endif; ?>
                    </form>
                <?php else: ?>
                    <div class="btn btn-dark btn-full" style="opacity:0.5;cursor:not-allowed;justify-content:center;padding:18px;">Out of Stock</div>
                <?php endif; ?>

                <div style="margin-top:32px;padding-top:24px;border-top:1px solid var(--border);">
                    <div style="display:flex;gap:24px;flex-wrap:wrap;font-size:0.78rem;color:var(--grey);">
                        <span>📦 Free shipping above RM150</span>
                        <span>🔄 7-day returns</span>
                        <span>✅ Authentic product</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$variantsData = $product->variants->map(function($v) {
    return [
        'variant_id' => $v->variant_id,
        'size' => $v->size,
        'colour' => $v->colour,
        'stock_qty' => $v->stock_qty,
    ];
})->values();
?>
<script>
const variants = <?php echo json_encode($variantsData, 15, 512) ?>;

let selectedSize = null;
let selectedColour = null;

function updateVariantId() {
    const match = variants.find(v => v.size === selectedSize && v.colour === selectedColour);
    document.getElementById('selectedVariantId').value = match ? match.variant_id : '';
}

function selectSize(size, btn) {
    document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
    selectedSize = size;
    document.getElementById('sizeError').style.display = 'none';
    updateVariantId();
}

function selectColour(colour, btn) {
    document.querySelectorAll('.colour-btn').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
    selectedColour = colour;
    document.getElementById('colourError').style.display = 'none';
    updateVariantId();
}

function changeQty(delta) {
    const inp = document.getElementById('qtyInput');
    inp.value = Math.max(1, Math.min(<?php echo e($product->stock_qty); ?>, parseInt(inp.value) + delta));
}

document.getElementById('addToCartForm')?.addEventListener('submit', function(e) {
    let valid = true;
    if (!selectedSize) { document.getElementById('sizeError').style.display = 'block'; valid = false; }
    if (!selectedColour) { document.getElementById('colourError').style.display = 'block'; valid = false; }
    if (!document.getElementById('selectedVariantId').value) { valid = false; }
    if (!valid) e.preventDefault();
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Downloads\drip-culture-fixed (4)\drip-culture\resources\views/products/show.blade.php ENDPATH**/ ?>