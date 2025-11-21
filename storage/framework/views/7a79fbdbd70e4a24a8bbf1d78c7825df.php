<?php $__env->startSection('title', 'Manajemen Produk - DISHINE Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <h1 class="text-2xl font-semibold text-[#3c2f2f]">Manajemen Produk</h1>
    <p class="text-sm text-gray-500 mt-1">Kelola semua produk toko Anda di sini.</p>
</div>


<?php if(session('success')): ?>
    <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 p-4 rounded-lg mb-6">
        <i data-lucide="check-circle" class="w-5 h-5"></i>
        <span><?php echo e(session('success')); ?></span>
    </div>
<?php endif; ?>


<?php if($errors->any()): ?>
    <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg mb-6">
        <div class="flex items-center gap-2 mb-2">
            <i data-lucide="alert-circle" class="w-5 h-5"></i>
            <p class="font-semibold">Ada kesalahan pada input:</p>
        </div>
        <ul class="list-disc list-inside text-sm ml-7">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>


<div id="clientErrorBox" class="hidden bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg mb-6">
    <div class="flex items-center gap-2 mb-2">
        <i data-lucide="alert-circle" class="w-5 h-5"></i>
        <p class="font-semibold">Ada kesalahan pada form:</p>
    </div>
    <ul id="clientErrorList" class="list-disc list-inside text-sm ml-7"></ul>
</div>




<div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
    <div class="px-6 py-4 border-b border-gray-200 bg-[#f8f5f2]">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-[#b48a60] rounded-lg">
                <i data-lucide="plus-circle" class="w-5 h-5 text-white"></i>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-[#3c2f2f]">Tambah Produk Baru</h2>
                <p class="text-gray-500 text-sm">Isi data di bawah ini untuk menambahkan produk baru.</p>
            </div>
        </div>
    </div>

    <div class="p-6">
        <form id="productCreateForm" action="<?php echo e(route('admin.products.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            
            <div class="flex items-center gap-2 mb-4">
                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-[#b48a60] text-white text-xs font-semibold">1</span>
                <h3 class="text-base font-semibold text-[#3c2f2f]">Informasi Produk</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Nama Produk <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama"
                        value="<?php echo e(old('nama')); ?>"
                        placeholder="Masukkan nama produk"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#b48a60] focus:border-[#b48a60] outline-none transition <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        required>
                    <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Kategori Produk <span class="text-red-500">*</span>
                    </label>
                    <select name="category_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm bg-white focus:ring-2 focus:ring-[#b48a60] focus:border-[#b48a60] outline-none transition <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id') == $category->id ? 'selected' : ''); ?>>
                                <?php echo e($category->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Deskripsi Produk <span class="text-red-500">*</span>
                </label>
                <textarea name="deskripsi"
                    placeholder="Tuliskan deskripsi produk secara detail..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#b48a60] focus:border-[#b48a60] outline-none transition <?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    rows="4" required><?php echo e(old('deskripsi')); ?></textarea>
                <?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="flex items-center gap-2 mb-4">
                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-[#b48a60] text-white text-xs font-semibold">2</span>
                <h3 class="text-base font-semibold text-[#3c2f2f]">Harga Produk</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Harga Normal <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                        <input type="number" name="harga_normal"
                               value="<?php echo e(old('harga_normal')); ?>"
                               placeholder="0"
                               class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-[#b48a60] focus:border-[#b48a60] outline-none transition <?php $__errorArgs = ['harga_normal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               required>
                    </div>
                    <?php $__errorArgs = ['harga_normal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Harga Reseller <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                        <input type="number" name="harga_reseller"
                            value="<?php echo e(old('harga_reseller')); ?>"
                            placeholder="0"
                            class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-[#b48a60] focus:border-[#b48a60] outline-none transition <?php $__errorArgs = ['harga_reseller'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            required>
                    </div>
                    <?php $__errorArgs = ['harga_reseller'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            
            <div class="flex items-center gap-2 mb-4">
                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-[#b48a60] text-white text-xs font-semibold">3</span>
                <h3 class="text-base font-semibold text-[#3c2f2f]">Varian & Ukuran Produk</h3>
            </div>

            <label class="inline-flex items-center gap-2 mb-4 cursor-pointer">
                <input type="checkbox" id="useVariants" name="use_variants" value="1"
                       class="w-4 h-4 text-[#b48a60] border-gray-300 rounded focus:ring-[#b48a60]" <?php echo e(old('use_variants') ? 'checked' : ''); ?>>
                <span class="text-sm text-gray-700">Produk ini memiliki varian warna</span>
            </label>

            
            <div id="variantSection" class="hidden bg-[#f8f5f2] border border-[#d6c3b3] rounded-lg p-5 mb-6">
                <p class="text-sm text-gray-600 mb-4">Tambahkan varian warna, lalu atur ukuran dan stoknya.</p>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-600 border-b border-[#d6c3b3]">
                                <th class="pb-3 font-medium">Warna</th>
                                <th class="pb-3 font-medium">Ukuran & Stok</th>
                                <th class="pb-3 font-medium">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="variantList"></tbody>
                    </table>
                </div>

                <button type="button"
                    id="addVariantBtn"
                    class="mt-4 inline-flex items-center gap-2 bg-[#b48a60] text-white px-4 py-2 rounded-lg hover:bg-[#9a7550] transition text-sm font-medium">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Tambah Varian
                </button>
            </div>

            
            <div id="defaultSizeSection" class="bg-[#f8f5f2] border border-[#d6c3b3] rounded-lg p-5 mb-8">
                <p class="text-sm text-gray-600 mb-4">
                    Pilih ukuran jika produk <span class="font-semibold">tidak</span> memiliki varian warna.
                </p>
                <div id="defaultSizeList" class="grid grid-cols-2 md:grid-cols-4 gap-3"></div>
            </div>

            
            <div class="flex items-center gap-2 mb-4">
                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-[#b48a60] text-white text-xs font-semibold">4</span>
                <h3 class="text-base font-semibold text-[#3c2f2f]">Gambar Produk</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Gambar Sampul <span class="text-red-500">*</span>
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-[#b48a60] transition cursor-pointer">
                        <input type="file" name="gambar" id="gambarInput"
                            class="hidden" required>
                        <label for="gambarInput" class="cursor-pointer">
                            <i data-lucide="image" class="w-8 h-8 mx-auto text-gray-400 mb-2"></i>
                            <p class="text-sm text-gray-500">Klik untuk upload gambar</p>
                            <p class="text-xs text-gray-400 mt-1">PNG, JPG, JPEG (Max 2MB)</p>
                        </label>
                    </div>
                    <?php $__errorArgs = ['gambar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Galeri Foto <span class="text-gray-400">(Opsional)</span>
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-[#b48a60] transition cursor-pointer">
                        <input type="file" name="gallery[]" id="galleryInput" multiple
                            class="hidden">
                        <label for="galleryInput" class="cursor-pointer">
                            <i data-lucide="images" class="w-8 h-8 mx-auto text-gray-400 mb-2"></i>
                            <p class="text-sm text-gray-500">Klik untuk upload galeri</p>
                            <p class="text-xs text-gray-400 mt-1">Pilih beberapa file sekaligus</p>
                        </label>
                    </div>
                    <?php $__errorArgs = ['gallery.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            
            <input type="hidden" name="variants" id="variantsData">
            <input type="hidden" name="default_sizes" id="defaultSizesData">

            
            <div class="flex justify-end pt-4 border-t border-gray-200">
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-[#3c2f2f] text-white px-6 py-2.5 rounded-lg hover:bg-[#2a2020] transition font-medium">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Simpan Produk
                </button>
            </div>
        </form>
    </div>
</div>




<div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
    <div class="p-5">
        <form action="<?php echo e(route('admin.products')); ?>" method="GET">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label for="search_nama" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Cari Nama Produk
                    </label>
                    <div class="relative">
                        <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="search_nama" id="search_nama"
                               class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-[#b48a60] focus:border-[#b48a60] outline-none transition"
                               placeholder="Ketik nama produk..."
                               value="<?php echo e($filters['search_nama'] ?? ''); ?>">
                    </div>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-[#3c2f2f] text-white px-5 py-2.5 rounded-lg hover:bg-[#2a2020] transition text-sm font-medium">
                        <i data-lucide="search" class="w-4 h-4"></i>
                        Cari
                    </button>
                    <a href="<?php echo e(route('admin.products')); ?>"
                       class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 px-5 py-2.5 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>




<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-[#f8f5f2]">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-[#b48a60] rounded-lg">
                <i data-lucide="package" class="w-5 h-5 text-white"></i>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-[#3c2f2f]">Daftar Produk</h2>
                <p class="text-gray-500 text-sm">Total <?php echo e($products->count()); ?> produk</p>
            </div>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Produk</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga Normal</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga Reseller</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Stok</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e($index + 1); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <?php if($product->gambar): ?>
                                    <img src="<?php echo e(asset('storage/' . $product->gambar)); ?>" 
                                         class="w-12 h-12 rounded-lg object-cover border border-gray-200">
                                <?php else: ?>
                                    <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <i data-lucide="image" class="w-5 h-5 text-gray-400"></i>
                                    </div>
                                <?php endif; ?>
                                <span class="font-medium text-gray-900"><?php echo e($product->nama); ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-[#f8f5f2] text-[#b48a60]">
                                <?php echo e($product->category->name ?? 'N/A'); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">
                            Rp<?php echo e(number_format($product->harga_normal, 0, ',', '.')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">
                            Rp<?php echo e(number_format($product->harga_reseller, 0, ',', '.')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold 
                                <?php echo e($product->total_stok > 10 ? 'bg-green-100 text-green-700' : ($product->total_stok > 0 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700')); ?>">
                                <?php echo e($product->total_stok); ?> pcs
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <a href="<?php echo e(route('admin.products.edit', $product->id)); ?>"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-[#b48a60] text-white text-xs font-medium rounded-lg hover:bg-[#9a7550] transition">
                                    <i data-lucide="edit-2" class="w-3.5 h-3.5"></i>
                                    Edit
                                </a>

                                <form action="<?php echo e(route('admin.products.delete', $product->id)); ?>"
                                      method="POST" class="inline"
                                      onsubmit="return confirm('Hapus produk ini?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-500 text-white text-xs font-medium rounded-lg hover:bg-red-600 transition">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-12">
                            <i data-lucide="package-x" class="w-12 h-12 mx-auto text-gray-300 mb-3"></i>
                            <p class="text-gray-500">
                                <?php if(request()->has('search_nama')): ?>
                                    Tidak ada produk yang cocok dengan pencarian Anda.
                                <?php else: ?>
                                    Belum ada produk. Tambahkan produk pertama Anda!
                                <?php endif; ?>
                            </p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<div id="sizeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
    <div class="bg-white p-6 rounded-xl w-[420px] shadow-2xl">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-lg font-semibold text-[#3c2f2f]">Atur Ukuran</h3>
            <button type="button" id="closeModalX" class="text-gray-400 hover:text-gray-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <div id="modalSizeList" class="max-h-64 overflow-y-auto pr-2 space-y-2"></div>

        <div class="flex justify-end gap-2 mt-6 pt-4 border-t">
            <button type="button" id="closeModal"
                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-medium text-gray-700 transition">
                Batal
            </button>

            <button type="button" id="saveSizeModal"
                class="px-4 py-2 bg-[#b48a60] hover:bg-[#9a7550] rounded-lg text-white text-sm font-medium transition">
                Simpan
            </button>
        </div>
    </div>
</div>




<script>
    // Data dari server
    let sizes = <?php echo json_encode(\App\Models\Size::all(), 15, 512) ?>;
    let variants = [];
    let defaultSizes = [];
    let editingVariant = -1;

    const useVariantsCheckbox = document.getElementById("useVariants");
    const variantSection      = document.getElementById("variantSection");
    const defaultSizeSection  = document.getElementById("defaultSizeSection");
    const variantList         = document.getElementById("variantList");
    const defaultSizeList     = document.getElementById("defaultSizeList");
    const variantsInput       = document.getElementById("variantsData");
    const defaultSizesInput   = document.getElementById("defaultSizesData");
    const form                = document.getElementById("productCreateForm");

    // Toggle mode
    useVariantsCheckbox.addEventListener("change", function () {
        const use = this.checked;
        variantSection.classList.toggle("hidden", !use);
        defaultSizeSection.classList.toggle("hidden", use);
        lucide.createIcons();
    });

    // Tambah varian
    document.getElementById("addVariantBtn").onclick = function () {
        variants.push({ warna: "", sizes: [] });
        renderVariants();
    };

    window.updateVariantColor = function(index, value) {
        variants[index].warna = value;
        updateHiddenJson();
    };

    function renderVariants() {
        let html = "";
        variants.forEach((v, i) => {
            html += `
                <tr class="border-b border-[#d6c3b3] last:border-0">
                    <td class="py-3 pr-3">
                        <input type="text" 
                            class="border border-gray-300 px-3 py-2 rounded-lg w-full text-sm focus:ring-2 focus:ring-[#b48a60] focus:border-[#b48a60] outline-none"
                            placeholder="Contoh: Hitam"
                            value="${v.warna}"
                            oninput="updateVariantColor(${i}, this.value)">
                    </td>
                    <td class="py-3 pr-3">
                        <button class="inline-flex items-center gap-1.5 bg-white border border-gray-300 px-3 py-2 rounded-lg text-xs hover:bg-gray-50 transition" type="button"
                            onclick="openSizeModal(${i})">
                            <i data-lucide="settings-2" class="w-3.5 h-3.5"></i>
                            Atur ukuran (${v.sizes.length} dipilih)
                        </button>
                    </td>
                    <td class="py-3">
                        <button type="button" class="inline-flex items-center gap-1 bg-red-500 text-white px-3 py-1.5 rounded-lg text-xs hover:bg-red-600 transition"
                            onclick="deleteVariant(${i})">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                            Hapus
                        </button>
                    </td>
                </tr>
            `;
        });

        variantList.innerHTML = html;
        updateHiddenJson();
        lucide.createIcons();
    }

    window.deleteVariant = function(index) {
        variants.splice(index, 1);
        renderVariants();
    }

    window.openSizeModal = function (index) {
        editingVariant = index;
        let html = "";

        sizes.forEach(size => {
            let found = variants[index].sizes.find(s => s.id === size.id);
            let stok  = found ? found.stok : "";

            html += `
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                    <input type="checkbox" ${found ? "checked" : ""}
                        class="w-4 h-4 text-[#b48a60] border-gray-300 rounded focus:ring-[#b48a60]"
                        onchange="toggleSize(${index}, ${size.id}, this.checked)">
                    <span class="w-16 font-medium text-sm text-gray-700">${size.name}</span>
                    <input type="number" value="${stok}" placeholder="Stok"
                        class="border border-gray-300 px-3 py-1.5 rounded-lg w-24 text-sm focus:ring-2 focus:ring-[#b48a60] focus:border-[#b48a60] outline-none"
                        style="display: ${found ? 'block' : 'none'}"
                        id="modal-stok-${index}-${size.id}"
                        onchange="updateSizeStok(${index}, ${size.id}, this.value)">
                </div>
            `;
        });

        document.getElementById("modalSizeList").innerHTML = html;
        document.getElementById("sizeModal").classList.remove("hidden");
        document.getElementById("sizeModal").classList.add("flex");
    };

    document.getElementById("closeModal").onclick = closeModal;
    document.getElementById("closeModalX").onclick = closeModal;

    function closeModal() {
        document.getElementById("sizeModal").classList.add("hidden");
        document.getElementById("sizeModal").classList.remove("flex");
    }

    document.getElementById("saveSizeModal").onclick = () => {
        closeModal();
        renderVariants();
    };

    window.toggleSize = function (variantIndex, sizeId, checked) {
        let stokInput = document.getElementById(`modal-stok-${variantIndex}-${sizeId}`);
        
        if (checked) {
            if (!variants[variantIndex].sizes.find(s => s.id === sizeId)) {
                variants[variantIndex].sizes.push({ id: sizeId, stok: 0 });
            }
            stokInput.style.display = 'block';
            stokInput.focus();
        } else {
            variants[variantIndex].sizes = variants[variantIndex].sizes.filter(s => s.id !== sizeId);
            stokInput.style.display = 'none';
            stokInput.value = '';
        }
        updateHiddenJson();
    };

    window.updateSizeStok = function (variantIndex, sizeId, value) {
        let item = variants[variantIndex].sizes.find(s => s.id === sizeId);
        if (item) item.stok = parseInt(value || 0);
        updateHiddenJson();
    };

    function renderDefaultSizes() {
        let html = "";

        sizes.forEach(size => {
            let found = defaultSizes.find(s => s.id === size.id);
            let stok  = found ? found.stok : "";

            html += `
                <div class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-lg">
                    <input type="checkbox"
                        class="w-4 h-4 text-[#b48a60] border-gray-300 rounded focus:ring-[#b48a60]"
                        ${found ? "checked" : ""}
                        onchange="toggleDefaultSize(${size.id}, this.checked)">
                    
                    <span class="font-medium text-sm text-gray-700">${size.name}</span>

                    <input 
                        type="number" 
                        placeholder="Stok" 
                        class="border border-gray-300 rounded-lg px-3 py-1.5 w-20 text-sm focus:ring-2 focus:ring-[#b48a60] focus:border-[#b48a60] outline-none"
                        value="${stok}"
                        onchange="updateDefaultSize(${size.id}, this.value)"
                        style="display: ${found ? 'block' : 'none'}"
                        id="stok-input-${size.id}"
                    >
                </div>
            `;
        });

        defaultSizeList.innerHTML = html;
    }
    renderDefaultSizes();

    window.toggleDefaultSize = function (sizeId, checked) {
        let input = document.getElementById('stok-input-' + sizeId);

        if (checked) {
            if (!defaultSizes.find(s => s.id === sizeId)) {
                defaultSizes.push({ id: sizeId, stok: 0 });
            }
            input.style.display = "block";
            input.focus();
        } else {
            defaultSizes = defaultSizes.filter(s => s.id !== sizeId);
            input.style.display = "none";
            input.value = "";
        }

        updateHiddenJson();
    };

    window.updateDefaultSize = function (sizeId, value) {
        let item = defaultSizes.find(s => s.id === sizeId);
        if (item) item.stok = parseInt(value || 0);
        updateHiddenJson();
    };

    function updateHiddenJson() {
        variantsInput.value     = JSON.stringify(variants);
        defaultSizesInput.value = JSON.stringify(defaultSizes);
    }

    // Validasi client-side
    form.addEventListener('submit', function (e) {
        let errors = [];
        const clientErrorBox  = document.getElementById('clientErrorBox');
        const clientErrorList = document.getElementById('clientErrorList');
        clientErrorList.innerHTML = '';
        clientErrorBox.classList.add('hidden');

        updateHiddenJson();

        const useVariants = useVariantsCheckbox.checked;

        if (useVariants) {
            if (variants.length === 0) {
                errors.push('Tambahkan minimal 1 varian warna.');
            }

            variants.forEach((v, index) => {
                if (!v.warna || v.warna.trim() === '') {
                    errors.push(`Varian baris ke-${index + 1}: Warna wajib diisi.`);
                }
                if (!v.sizes || v.sizes.length === 0) {
                    errors.push(`Varian "${v.warna || ('#'+(index+1))}" wajib memiliki minimal 1 ukuran.`);
                } else {
                    v.sizes.forEach(s => {
                        let sizeName = sizes.find(sz => sz.id === s.id)?.name || 'Unknown';
                        if (s.stok === "" || s.stok < 0) {
                            errors.push(`Stok untuk ukuran ${sizeName} pada varian "${v.warna}" wajib diisi.`);
                        }
                    });
                }
            });

        } else {
            if (defaultSizes.length === 0) {
                const lanjut = confirm('Anda belum memilih ukuran sama sekali. Lanjutkan tanpa stok?');
                if (!lanjut) {
                    e.preventDefault();
                    return;
                }
            } else {
                defaultSizes.forEach(s => {
                    let sizeName = sizes.find(sz => sz.id === s.id)?.name || 'Unknown';
                    if (s.stok === "" || s.stok < 0) {
                        errors.push(`Stok untuk ukuran ${sizeName} wajib diisi.`);
                    }
                });
            }
        }

        if (errors.length > 0) {
            e.preventDefault();
            errors.forEach(msg => {
                const li = document.createElement('li');
                li.textContent = msg;
                clientErrorList.appendChild(li);
            });
            clientErrorBox.classList.remove('hidden');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });

    // Re-init icons
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aulia\dataD\KULIAH\PJBL\dishine\resources\views/admin/products.blade.php ENDPATH**/ ?>