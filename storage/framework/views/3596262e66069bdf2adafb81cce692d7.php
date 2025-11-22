<?php $__env->startSection('title', 'Register - Dishine'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-10 flex justify-center items-center" style="min-height: 80vh;">
    <div class="w-full max-w-md py-8">

        <h1 class="text-3xl font-serif font-extrabold text-center mb-6" style="color: #AE8B56;">
            Daftar
        </h1>

        
        <?php if($errors->any()): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>• <?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        
        <?php if(session('success')): ?>
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <form action="/register" method="POST">
            <?php echo csrf_field(); ?>

            <div class="mb-4">
                <label for="nama" class="block text-sm font-medium mb-2" style="color: #AE8B56;">Nama:</label>
                <input type="text" name="nama" id="nama" value="<?php echo e(old('nama')); ?>"
                    class="w-full p-2 border rounded focus:ring-0 focus:border-opacity-100"
                    style="border-color: #CC8550; color: #AE8B56;" required>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium mb-2" style="color: #AE8B56;">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo e(old('email')); ?>"
                    class="w-full p-2 border rounded focus:ring-0 focus:border-opacity-100"
                    style="border-color: #CC8550; color: #AE8B56;" required>
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium mb-2" style="color: #AE8B56;">Password:</label>
                <input type="password" name="password" id="password"
                    class="w-full p-2 border rounded focus:ring-0 focus:border-opacity-100"
                    style="border-color: #CC8550; color: #AE8B56;" required>
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium mb-2" style="color: #AE8B56;">Konfirmasi Password:</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="w-full p-2 border rounded focus:ring-0 focus:border-opacity-100"
                    style="border-color: #CC8550; color: #AE8B56;" required>
            </div>

            <div class="mb-4">
                <label for="role" class="block text-sm font-medium mb-2" style="color: #AE8B56;">Role:</label>
                <select name="role" id="role"
                    class="w-full p-2 border rounded focus:ring-0 focus:border-opacity-100"
                    style="border-color: #CC8550; color: #AE8B56;" required>
                    <option value="pelanggan">Pelanggan</option>
                    <option value="reseller">Reseller</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            

            <div class="mb-4">
                <label for="no_hp" class="block text-sm font-medium mb-2" style="color: #AE8B56;">No. HP:</label>
                <input type="text" name="no_hp" id="no_hp" value="<?php echo e(old('no_hp')); ?>"
                    class="w-full p-2 border rounded focus:ring-0 focus:border-opacity-100"
                    style="border-color: #CC8550; color: #AE8B56;" required>
            </div>

            <button type="submit" class="btn-primary w-full py-2 rounded">Daftar</button>
        </form>

        <p class="text-center mt-4">
            Sudah punya akun? 
            <a href="/login" class="text-[#b48a60] hover:underline">Login di sini</a>
        </p>

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
<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aulia\dataD\KULIAH\PJBL\dishine\resources\views/auth/register.blade.php ENDPATH**/ ?>