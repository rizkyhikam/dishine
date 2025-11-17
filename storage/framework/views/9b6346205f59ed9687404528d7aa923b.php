

<?php $__env->startSection('content'); ?>
<div class="container mt-5">
    <h2 class="mb-4 text-center">👤 Profil Pengguna</h2>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <form action="<?php echo e(route('profil.update')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $user->name ?? '')); ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo e(old('email', $user->email ?? '')); ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control"><?php echo e(old('alamat', $user->alamat ?? '')); ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Nomor Telepon</label>
            <input type="text" name="telepon" class="form-control" value="<?php echo e(old('telepon', $user->telepon ?? '')); ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Role</label>
            <input type="text" class="form-control" value="<?php echo e(ucfirst($user->role ?? 'guest')); ?>" readonly>
        </div>

        <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/profil.blade.php ENDPATH**/ ?>