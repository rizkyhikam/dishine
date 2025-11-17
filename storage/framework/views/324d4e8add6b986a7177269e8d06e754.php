<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - DISHINE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?php echo e(route('admin.dashboard')); ?>">DISHINE Admin</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="<?php echo e(route('admin.products')); ?>">Manajemen Produk</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo e(route('admin.categories')); ?>">Manajemen Kategori</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo e(route('admin.orders')); ?>">Pesanan</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo e(route('admin.faq')); ?>">FAQ</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <?php echo $__env->yieldContent('content'); ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/layouts/admin.blade.php ENDPATH**/ ?>