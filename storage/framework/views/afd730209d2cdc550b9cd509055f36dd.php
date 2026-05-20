<?php $pageTitle = 'Login' ?>
<?php $__env->startSection('content'); ?>

<section class="section" style="min-height:80vh;display:flex;align-items:center;">
    <div class="container">
        <div class="form-card">
            <div class="form-title">Welcome Back</div>
            <p class="form-sub">Sign in to your DRIP CULTURE account.</p>

            <?php if($errors->has('email')): ?>
                <div class="flash flash-error"><?php echo e($errors->first('email')); ?></div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('login')); ?>" novalidate>
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           placeholder="your@email.com" value="<?php echo e(old('email')); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Password *</label>
                    <input type="password" name="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           placeholder="Your password">
                </div>
                <div class="form-group" style="display:flex;align-items:center;gap:8px;">
                    <input type="checkbox" name="remember" id="remember" style="accent-color:var(--accent);">
                    <label for="remember" style="color:var(--grey);font-size:0.85rem;">Remember me</label>
                </div>
                <button type="submit" class="btn btn-primary btn-full" style="margin-top:8px;">Login →</button>
            </form>

            <div class="form-divider">Don't have an account?</div>
            <a href="<?php echo e(route('register')); ?>" class="btn btn-dark btn-full">Create Account</a>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Downloads\drip-culture-fixed (4)\drip-culture\resources\views/auth/login.blade.php ENDPATH**/ ?>