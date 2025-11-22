<?php $__env->startSection('title', 'Login - Dishine'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-10 flex justify-center">
    <div class="w-full max-w-sm py-8">
        <h1 class="text-3xl font-serif font-extrabold text-center mb-6" style="color: #AE8B56;">
            Login
        </h1>
        
        <?php if(session('success')): ?>
            <div class="mb-4 flex items-center p-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50" role="alert">
                <svg class="flex-shrink-0 inline w-4 h-4 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
                <span class="sr-only">Info</span>
                <div>
                    <span class="font-medium">Berhasil!</span> <?php echo e(session('success')); ?>

                </div>
            </div>
        <?php endif; ?>

        
        <?php if($errors->any()): ?>
            <div class="mb-4 flex p-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50" role="alert">
                <svg class="flex-shrink-0 inline w-4 h-4 mr-3 mt-[2px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
                </svg>
                <span class="sr-only">Error</span>
                <div>
                    <span class="font-medium">Gagal Masuk:</span>
                    <ul class="mt-1.5 ml-4 list-disc list-inside">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
        <form action="/login" method="POST">
            <?php echo csrf_field(); ?>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium mb-2" style="color: #AE8B56;">Email:</label>
                <input type="email" name="email" id="email" class="w-full p-2 border rounded focus:ring-0 focus:border-opacity-100" 
                style="border-color: #CC8550; color: #AE8B56;" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium mb-2" style="color: #AE8B56;">Password:</label>
                <input type="password" name="password" id="password" 
                class="w-full p-2 border rounded focus:ring-0 focus:border-opacity-100" 
                style="border-color: #CC8550; color: #AE8B56;" required>
            </div>
            <div class="flex justify-end mb-4">
                <a href="<?php echo e(route('password.request')); ?>" class="text-sm text-[#b48a60] hover:text-[#a07850] hover:underline">
                    Lupa Password?
                </a>
            </div>
            <button type="submit" class="btn-primary w-full py-2 rounded">Login</button>
        </form>
        <p class="text-center mt-4">Belum punya akun? <a href="/register" class="text-[#b48a60] hover:underline">Daftar di sini</a></p>
            <div class="text-center mt-12"> 
                <a href="/" class="inline-block">
                <img src="<?php echo e(asset('logodishine.png')); ?>" 
                alt="Dishine Logo - Design with Quality" 
                class="mx-auto h-35 mb-6 hover:opacity-90 transition duration-200" 
                style="max-width: 200px;">
                </a>
            </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aulia\dataD\KULIAH\PJBL\dishine\resources\views/auth/login.blade.php ENDPATH**/ ?>