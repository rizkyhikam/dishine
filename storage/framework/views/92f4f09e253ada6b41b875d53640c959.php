<?php $__env->startSection('content'); ?>
<h1 class="mb-4">Manajemen Produk</h1>

<?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
<?php endif; ?>

<!-- Form Tambah Produk -->
<div class="card mb-4">
    <div class="card-header">Tambah Produk Baru</div>
    <div class="card-body">
        <form action="<?php echo e(route('admin.products')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Nama Produk</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Harga Normal</label>
                    <input type="number" name="harga_normal" class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Harga Reseller</label>
                    <input type="number" name="harga_reseller" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Stok</label>
                    <input type="number" name="stok" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label>Gambar Produk</label>
                <input type="file" name="gambar" class="form-control" accept="image/*" required>
            </div>

            <button type="submit" class="btn btn-primary">Tambah Produk</button>
        </form>
    </div>
</div>

<!-- Daftar Produk -->
<div class="card">
    <div class="card-header">Daftar Produk</div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Harga Normal</th>
                    <th>Harga Reseller</th>
                    <th>Stok</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($index + 1); ?></td>
                        <td><?php echo e($product->nama); ?></td>
                        <td>Rp<?php echo e(number_format($product->harga_normal, 0, ',', '.')); ?></td>
                        <td>Rp<?php echo e(number_format($product->harga_reseller, 0, ',', '.')); ?></td>
                        <td><?php echo e($product->stok); ?></td>

                        <td>
                            <?php if($product->gambar): ?>
                                <img src="<?php echo e(asset('storage/' . $product->gambar)); ?>" width="60">
                            <?php endif; ?>
                        </td>

                        <td>
                            <!-- TOMBOL EDIT (ditambahkan) -->
                            <a href="<?php echo e(route('admin.products.edit', $product->id)); ?>"
                               class="btn btn-sm btn-warning me-1">
                                Edit
                            </a>

                            <!-- FORM DELETE (diperbaiki pakai route) -->
                            <form action="<?php echo e(route('admin.products.delete', $product->id)); ?>"
                                  method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>

                                <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('Hapus produk ini?')">
                                    Hapus
                                </button>
                            </form>
                        </td>

                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/admin/products.blade.php ENDPATH**/ ?>