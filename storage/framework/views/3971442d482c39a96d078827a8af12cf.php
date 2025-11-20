<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Dishine - E-commerce Terpercaya'); ?></title>

    <!-- Font & Tailwind -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f5f2;
            color: #3c2f2f;
        }
        .btn-primary {
            background-color: #b48a60;
            color: white;
        }
        .btn-primary:hover {
            background-color: #a07850;
        }
        /* Untuk card form */
        .auth-card {
            background-color: white;
            border: 1px solid #d6c3b3;
            border-radius: 0.75rem;
            box-shadow: 0 4px 8px rgba(60, 47, 47, 0.1);
        }
    </style>
</head>

<body class="bg-[#f8f5f2] flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md px-6 py-10 auth-card">
        <?php echo $__env->yieldContent('content'); ?>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
<?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/layouts/auth.blade.php ENDPATH**/ ?>