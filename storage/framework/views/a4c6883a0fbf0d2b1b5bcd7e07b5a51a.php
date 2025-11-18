<!-- Products Ordered - Sesuai HIFI -->
<div class="bg-white rounded-lg border p-6 mb-6">
    <h3 class="font-bold text-lg mb-4 text-gray-900">Produk Dipesan</h3>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b">
                    <th class="text-left font-semibold text-gray-600 pb-3">Produk</th>
                    <th class="text-center font-semibold text-gray-600 pb-3">Harga Satuan</th>
                    <th class="text-center font-semibold text-gray-600 pb-3">Jumlah</th>
                    <th class="text-center font-semibold text-gray-600 pb-3">Sub Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="border-b">
                    <td class="py-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-16 h-16 bg-gray-100 rounded flex items-center justify-center">
                                <?php if($item->product->gambar): ?>
                                    <img src="<?php echo e(asset('storage/' . $item->product->gambar)); ?>" 
                                         alt="<?php echo e($item->product->nama); ?>"
                                         class="w-16 h-16 object-cover rounded">
                                <?php else: ?>
                                    <span class="text-gray-400 font-semibold text-sm">
                                        <?php echo e(substr($item->product->nama, 0, 2)); ?>

                                    </span>
                                <?php endif; ?>
                            </div>
                            <span class="font-semibold text-gray-900">
                                <?php echo e($item->product->nama); ?>

                            </span>
                        </div>
                    </td>
                    <td class="text-center text-gray-700 py-4">
                        Rp <?php echo e(number_format($item->product->harga_normal, 0, ',', '.')); ?>

                    </td>
                    <td class="text-center text-gray-700 py-4">
                        <?php echo e($item->quantity); ?>

                    </td>
                    <td class="text-center font-semibold text-gray-900 py-4">
                        Rp <?php echo e(number_format($item->product->harga_normal * $item->quantity, 0, ',', '.')); ?>

                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div><?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/checkout/partials/products.blade.php ENDPATH**/ ?>