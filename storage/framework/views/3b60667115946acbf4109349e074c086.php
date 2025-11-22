<?php $__env->startSection('title', 'Edit Produk - DISHINE Admin'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center space-x-4">
            <div class="bg-gradient-to-r from-[#CC8650] to-[#D4BA98] p-4 rounded-2xl shadow-lg">
                <i data-lucide="package" class="w-8 h-8 text-white"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold text-gray-800">Edit Produk</h1>
                <p class="text-gray-600 mt-1">Perbarui data produk: <?php echo e($product->nama); ?></p>
            </div>
        </div>
    </div>

    
    <?php if(session('success')): ?>
        <div class="mb-6 bg-gradient-to-r from-green-100 to-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm">
            <div class="flex items-center">
                <i data-lucide="check-circle" class="w-5 h-5 mr-2 text-green-600"></i>
                <span class="font-medium"><?php echo e(session('success')); ?></span>
            </div>
        </div>
    <?php endif; ?>

    
    <?php if($errors->any()): ?>
        <div class="mb-6 bg-gradient-to-r from-red-100 to-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-sm">
            <div class="flex items-center mb-2">
                <i data-lucide="alert-circle" class="w-5 h-5 mr-2 text-red-600"></i>
                <span class="font-medium">Ada kesalahan pada input:</span>
            </div>
            <ul class="list-disc list-inside text-sm ml-6">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    
    <div id="clientErrorBox" class="hidden mb-6 bg-gradient-to-r from-red-100 to-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-sm">
        <div class="flex items-center mb-2">
            <i data-lucide="alert-circle" class="w-5 h-5 mr-2 text-red-600"></i>
            <span class="font-medium">Ada kesalahan pada form:</span>
        </div>
        <ul id="clientErrorList" class="list-disc list-inside text-sm ml-6"></ul>
    </div>

    
    
    
    <div class="bg-white rounded-2xl shadow-md overflow-hidden mb-8">
        <!-- Header Form dengan Gradient -->
        <div class="bg-gradient-to-r from-[#CC8650] to-[#D4BA98] px-8 py-6">
            <h2 class="text-2xl font-bold text-white">Edit Detail Produk</h2>
            <p class="text-white text-opacity-90 mt-1">Perbarui data produk di bawah ini</p>
        </div>

        <div class="p-8">
            <form id="productEditForm"
                  action="<?php echo e(route('admin.products.update', $product->id)); ?>"
                  method="POST"
                  enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                
                <div class="mb-8">
                    <div class="flex items-center mb-6">
                        <div class="w-1 h-8 bg-gradient-to-b from-[#CC8650] to-[#D4BA98] rounded-full mr-4"></div>
                        <h3 class="text-xl font-bold text-gray-800">Informasi Produk</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i data-lucide="tag" class="w-4 h-4 inline mr-1 text-[#AE8B56]"></i>
                                Nama Produk <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama"
                                   value="<?php echo e(old('nama', $product->nama)); ?>"
                                   class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-[#CC8650] focus:ring-2 focus:ring-[#CC8650] focus:ring-opacity-20 transition-all <?php $__errorArgs = ['nama'];
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
                                <p class="text-xs text-red-600 mt-2 flex items-center">
                                    <i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i>
                                    <?php echo e($message); ?>

                                </p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i data-lucide="layers" class="w-4 h-4 inline mr-1 text-[#AE8B56]"></i>
                                Kategori Produk <span class="text-red-500">*</span>
                            </label>
                            <select name="category_id"
                                    class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 bg-white focus:border-[#CC8650] focus:ring-2 focus:ring-[#CC8650] focus:ring-opacity-20 transition-all <?php $__errorArgs = ['category_id'];
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
                                    <option value="<?php echo e($category->id); ?>"
                                            <?php echo e(old('category_id', $product->category_id) == $category->id ? 'selected' : ''); ?>>
                                        <?php echo e($category->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-xs text-red-600 mt-2 flex items-center">
                                    <i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i>
                                    <?php echo e($message); ?>

                                </p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i data-lucide="file-text" class="w-4 h-4 inline mr-1 text-[#AE8B56]"></i>
                            Deskripsi Produk <span class="text-red-500">*</span>
                        </label>
                        <textarea name="deskripsi"
                                  class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-[#CC8650] focus:ring-2 focus:ring-[#CC8650] focus:ring-opacity-20 transition-all <?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                  rows="4"
                                  required><?php echo e(old('deskripsi', $product->deskripsi)); ?></textarea>
                        <?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-xs text-red-600 mt-2 flex items-center">
                                <i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i>
                                <?php echo e($message); ?>

                            </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                
                <div class="mb-8">
                    <div class="flex items-center mb-6">
                        <div class="w-1 h-8 bg-gradient-to-b from-[#CC8650] to-[#D4BA98] rounded-full mr-4"></div>
                        <h3 class="text-xl font-bold text-gray-800">Harga Produk</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i data-lucide="dollar-sign" class="w-4 h-4 inline mr-1 text-[#AE8B56]"></i>
                                Harga Normal <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                                <input type="number" name="harga_normal"
                                       value="<?php echo e(old('harga_normal', $product->harga_normal)); ?>"
                                       class="w-full border-2 border-gray-200 rounded-xl pl-12 pr-4 py-3 focus:border-[#CC8650] focus:ring-2 focus:ring-[#CC8650] focus:ring-opacity-20 transition-all <?php $__errorArgs = ['harga_normal'];
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
                                <p class="text-xs text-red-600 mt-2 flex items-center">
                                    <i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i>
                                    <?php echo e($message); ?>

                                </p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i data-lucide="dollar-sign" class="w-4 h-4 inline mr-1 text-[#AE8B56]"></i>
                                Harga Reseller <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                                <input type="number" name="harga_reseller"
                                       value="<?php echo e(old('harga_reseller', $product->harga_reseller)); ?>"
                                       class="w-full border-2 border-gray-200 rounded-xl pl-12 pr-4 py-3 focus:border-[#CC8650] focus:ring-2 focus:ring-[#CC8650] focus:ring-opacity-20 transition-all <?php $__errorArgs = ['harga_reseller'];
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
                                <p class="text-xs text-red-600 mt-2 flex items-center">
                                    <i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i>
                                    <?php echo e($message); ?>

                                </p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                
                <div class="mb-8">
                    <div class="flex items-center mb-6">
                        <div class="w-1 h-8 bg-gradient-to-b from-[#CC8650] to-[#D4BA98] rounded-full mr-4"></div>
                        <h3 class="text-xl font-bold text-gray-800">Varian & Ukuran Produk</h3>
                    </div>

                    <?php
                        $hasVariants = $product->variants && $product->variants->count() > 0;
                        // siapkan data varian + size untuk JS
                        $initialVariants = $product->variants->map(function($variant) {
                            return [
                                'warna' => $variant->warna,
                                'sizes' => $variant->sizes->map(function($vs) {
                                    return [
                                        'id'   => $vs->size_id,
                                        'stok' => $vs->stok,
                                    ];
                                })->values()->toArray(),
                            ];
                        })->values()->toArray();
                    ?>

                    <div class="bg-gradient-to-r from-[#F0E7DB] to-[#EBE6E6] rounded-xl p-4 mb-6">
                        <label class="flex items-center gap-3">
                            <input type="checkbox"
                                   id="useVariants"
                                   name="use_variants"
                                   value="1"
                                   class="w-5 h-5 text-[#CC8650] focus:ring-[#CC8650] rounded"
                                   <?php echo e((old('use_variants', $hasVariants)) ? 'checked' : ''); ?>>
                            <span class="text-gray-700 font-medium">Produk ini memiliki varian warna</span>
                        </label>
                    </div>

                    
                    <div id="variantSection" class="bg-gray-50 border-2 border-gray-200 rounded-xl p-6 mb-6 hidden">
                        <div class="flex items-center mb-4">
                            <i data-lucide="palette" class="w-5 h-5 mr-2 text-[#AE8B56]"></i>
                            <h4 class="text-lg font-semibold text-gray-800">Varian Warna</h4>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">
                            Tambahkan varian warna, lalu atur ukuran dan stoknya.
                        </p>

                        <table class="w-full text-sm mb-4">
                            <thead>
                                <tr class="text-gray-600 font-semibold text-left border-b-2 border-gray-200">
                                    <th class="pb-3">Warna</th>
                                    <th class="pb-3">Ukuran & Stok</th>
                                    <th class="pb-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="variantList"></tbody>
                        </table>

                        <button type="button"
                                id="addVariantBtn"
                                class="inline-flex items-center px-5 py-2.5 bg-[#AE8B56] text-white rounded-xl hover:shadow-lg font-semibold transition-all">
                            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                            Tambah Varian
                        </button>
                    </div>

                    
                    <div id="defaultSizeSection" class="bg-gray-50 border-2 border-gray-200 rounded-xl p-6 mb-6 hidden">
                        <div class="flex items-center mb-4">
                            <i data-lucide="ruler" class="w-5 h-5 mr-2 text-[#AE8B56]"></i>
                            <h4 class="text-lg font-semibold text-gray-800">Ukuran Standar</h4>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">
                            Pilih ukuran jika produk <span class="font-semibold">tidak</span> memiliki varian warna.
                        </p>
                        <div id="defaultSizeList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"></div>
                    </div>
                </div>

                
                <div class="mb-8">
                    <div class="flex items-center mb-6">
                        <div class="w-1 h-8 bg-gradient-to-b from-[#CC8650] to-[#D4BA98] rounded-full mr-4"></div>
                        <h3 class="text-xl font-bold text-gray-800">Gambar Produk</h3>
                    </div>

                    
                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            <i data-lucide="image" class="w-4 h-4 inline mr-1 text-[#AE8B56]"></i>
                            Gambar Sampul (Cover)
                        </label>
                        <div class="flex items-start gap-6">
                            <div class="flex-shrink-0">
                                <?php if($product->gambar): ?>
                                    <img src="<?php echo e(asset('storage/' . $product->gambar)); ?>"
                                         width="120"
                                         class="rounded-xl border-2 border-gray-200 shadow-sm"
                                         alt="Cover">
                                <?php else: ?>
                                    <div class="w-32 h-32 bg-gray-100 rounded-xl border-2 border-dashed border-gray-300 flex items-center justify-center">
                                        <i data-lucide="image" class="w-8 h-8 text-gray-400"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex-1">
                                <input type="file" name="gambar"
                                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-[#CC8650] focus:ring-2 focus:ring-[#CC8650] focus:ring-opacity-20 transition-all <?php $__errorArgs = ['gambar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <small class="text-xs text-gray-500 mt-2 block">
                                    Kosongkan jika tidak ingin mengubah gambar sampul.
                                </small>
                                <?php $__errorArgs = ['gambar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-xs text-red-600 mt-2 flex items-center">
                                        <i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i>
                                        <?php echo e($message); ?>

                                    </p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>

                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            <i data-lucide="images" class="w-4 h-4 inline mr-1 text-[#AE8B56]"></i>
                            Galeri Foto
                        </label>

                        <div class="mb-6">
                            <div class="flex flex-wrap gap-4 mb-4">
                                <?php $__empty_1 = true; $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="relative group">
                                        <img src="<?php echo e(asset('storage/' . $image->path)); ?>"
                                             class="w-24 h-24 object-cover rounded-xl border-2 border-gray-200 shadow-sm group-hover:border-red-300 transition-all"
                                             alt="Gallery Image">
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 rounded-xl transition-all flex items-center justify-center">
                                            <div class="opacity-0 group-hover:opacity-100 transition-all">
                                                <input type="checkbox"
                                                       name="delete_images[]"
                                                       value="<?php echo e($image->id); ?>"
                                                       id="delete_img_<?php echo e($image->id); ?>"
                                                       class="hidden">
                                                <label for="delete_img_<?php echo e($image->id); ?>"
                                                       class="bg-red-500 text-white px-3 py-1 rounded-lg text-xs cursor-pointer hover:bg-red-600 transition-all">
                                                    Hapus
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="text-center w-full py-8 text-gray-500">
                                        <i data-lucide="images" class="w-12 h-12 mx-auto mb-2 opacity-50"></i>
                                        <p>Belum ada foto galeri.</p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tambah Foto Galeri Baru (Opsional)
                                </label>
                                <input type="file"
                                       name="gallery[]"
                                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-[#CC8650] focus:ring-2 focus:ring-[#CC8650] focus:ring-opacity-20 transition-all <?php $__errorArgs = ['gallery.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       accept="image/*"
                                       multiple>
                                <small class="text-xs text-gray-500 mt-2 block">
                                    Tahan Ctrl/Cmd untuk pilih banyak foto. Format: JPG, PNG, JPEG. Maksimal 5MB per gambar.
                                </small>
                                <?php $__errorArgs = ['gallery.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-xs text-red-600 mt-2 flex items-center">
                                        <i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i>
                                        <?php echo e($message); ?>

                                    </p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>
                </div>

                
                <input type="hidden" name="variants" id="variantsData">
                <input type="hidden" name="default_sizes" id="defaultSizesData">

                
                <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                    <a href="<?php echo e(route('admin.products')); ?>"
                       class="inline-flex items-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-semibold transition-all">
                        <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                        Kembali
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-[#CC8650] to-[#D4BA98] text-white rounded-xl hover:shadow-lg font-semibold transition-all">
                        <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    
    <div id="sizeModal"
         class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50 p-4">
        <div class="bg-white p-6 rounded-2xl w-full max-w-md shadow-xl">
            <div class="flex items-center mb-4">
                <i data-lucide="ruler" class="w-6 h-6 mr-2 text-[#AE8B56]"></i>
                <h3 class="text-xl font-bold text-gray-800">Atur Ukuran & Stok</h3>
            </div>

            <div id="modalSizeList" class="max-h-64 overflow-y-auto pr-2 space-y-3"></div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                <button type="button" id="closeModal"
                        class="inline-flex items-center px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-semibold transition-all">
                    <i data-lucide="x" class="w-4 h-4 mr-2"></i>
                    Batal
                </button>
                <button type="button" id="saveSizeModal"
                        class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-[#CC8650] to-[#D4BA98] text-white rounded-xl hover:shadow-lg font-semibold transition-all">
                    <i data-lucide="check" class="w-4 h-4 mr-2"></i>
                    Simpan
                </button>
            </div>
        </div>
    </div>

    

    
    
    
    <script>
    // 1. DATA DARI SERVER (PENTING!)
    // Ambil semua ukuran master
    let sizes = <?php echo json_encode(\App\Models\Size::select('id','name')->get()); ?>;

// Semua variant + size
let variants = <?php echo json_encode(
    $product->variants->map(function($v){
        return [
            'id'    => $v->id,
            'warna' => $v->warna,
            'sizes' => $v->sizes->map(function($s){
                return [
                    'id'   => $s->size_id,
                    'stok' => $s->stok
                ];
            })
        ];
    })
); ?>;

// Default sizes (tanpa varian)
let defaultSizes = <?php echo json_encode(
    $product->defaultSizes->map(function($s){
        return [
            'id'   => $s->size_id,
            'stok' => $s->stok
        ];
    })
); ?>;

    let editingVariant = -1;

    const useVariantsCheckbox = document.getElementById("useVariants");
    const variantSection      = document.getElementById("variantSection");
    const defaultSizeSection  = document.getElementById("defaultSizeSection");
    const variantList         = document.getElementById("variantList");
    const defaultSizeList     = document.getElementById("defaultSizeList");
    const variantsInput       = document.getElementById("variantsData");
    const defaultSizesInput   = document.getElementById("defaultSizesData");
    const form                = document.getElementById("productEditForm");

    // ====== INITIAL MODE (ON LOAD) ======
    function applyVariantModeOnLoad() {
        // Cek apakah produk ini pakai varian atau tidak
        // Jika variants array ada isinya, berarti pakai varian.
        // Atau cek checkbox state (yang sudah diset di Blade `old('use_variants', ...)`)
        const use = useVariantsCheckbox.checked;
        
        variantSection.classList.toggle("hidden", !use);
        defaultSizeSection.classList.toggle("hidden", use);
        
        renderVariants();
        renderDefaultSizes();
        updateHiddenJson(); // Pastikan input hidden terisi data awal
    }
    
    // Jalankan saat halaman selesai dimuat
    document.addEventListener('DOMContentLoaded', applyVariantModeOnLoad);

    // Toggle mode: varian warna / default size
    useVariantsCheckbox.addEventListener("change", function () {
        const use = this.checked;
        variantSection.classList.toggle("hidden", !use);
        defaultSizeSection.classList.toggle("hidden", use);
    });

    // ====== VARIANTS ======
    document.getElementById("addVariantBtn").onclick = function () {
        variants.push({ warna: "", sizes: [] });
        renderVariants();
    };

    // Update Warna
    window.updateVariantColor = function(index, value) {
        variants[index].warna = value;
        updateHiddenJson();
    };

    function renderVariants() {
        let html = "";
        variants.forEach((v, i) => {
            // Hitung total stok untuk info
            let totalStok = 0;
            if (v.sizes && v.sizes.length > 0) {
                totalStok = v.sizes.reduce((acc, curr) => acc + parseInt(curr.stok || 0), 0);
            }

            html += `
                <tr class="border-b border-gray-100 hover:bg-white transition-all">
                    <td class="py-4 align-top">
                        <input type="text"
                            class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 focus:border-[#CC8650] focus:ring-2 focus:ring-[#CC8650] focus:ring-opacity-20 transition-all"
                            placeholder="Contoh: Hitam"
                            value="${v.warna || ''}"
                            oninput="updateVariantColor(${i}, this.value)">
                    </td>
                    <td class="py-4 align-top">
                        <div class="text-xs text-gray-500 mb-1">
                            Terpilih: <b>${v.sizes ? v.sizes.length : 0}</b> ukuran | Total Stok: <b>${totalStok}</b>
                        </div>
                        <button class="inline-flex items-center px-4 py-2 bg-[#AE8B56] text-white rounded-lg hover:shadow-md text-xs font-semibold transition-all" type="button"
                            onclick="openSizeModal(${i})">
                            <i data-lucide="ruler" class="w-3 h-3 mr-1.5"></i>
                            Atur Ukuran & Stok
                        </button>
                    </td>
                    <td class="py-4 align-top">
                        <button type="button"
                            class="inline-flex items-center px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs font-semibold transition-all"
                            onclick="deleteVariant(${i})">
                            <i data-lucide="trash-2" class="w-3 h-3 mr-1"></i>
                            Hapus
                        </button>
                    </td>
                </tr>
            `;
        });

        variantList.innerHTML = html;
        
        // Re-init icons karena konten baru ditambahkan
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        
        updateHiddenJson();
    }

    window.deleteVariant = function(index) {
        if (confirm('Hapus varian ini?')) {
            variants.splice(index, 1);
            renderVariants();
        }
    }

    // ====== MODAL SIZE ======
    window.openSizeModal = function (index) {
        editingVariant = index;
        let html = "";

        sizes.forEach(size => {
            // Cek apakah ukuran ini sudah dipilih di varian ini
            let found = (variants[index].sizes || []).find(s => s.id == size.id);
            let stok  = found ? found.stok : "";
            let isChecked = found ? "checked" : "";
            let displayStyle = found ? "block" : "none";

            html += `
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-white transition-all mb-2">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" ${isChecked}
                            onchange="toggleSize(${index}, ${size.id}, this.checked)"
                            class="w-4 h-4 text-[#CC8650] focus:ring-[#CC8650] rounded cursor-pointer">
                        <span class="font-medium text-gray-700 select-none">${size.name}</span>
                    </div>
                    <input type="number" value="${stok}" placeholder="0"
                        class="w-24 border-2 border-gray-200 rounded-lg px-3 py-1 text-center focus:border-[#CC8650] focus:ring-2 focus:ring-[#CC8650] focus:ring-opacity-20 transition-all"
                        id="modal-stok-${size.id}"
                        style="display: ${displayStyle}"
                        oninput="updateSizeStok(${index}, ${size.id}, this.value)">
                </div>
            `;
        });

        document.getElementById("modalSizeList").innerHTML = html;
        
        const modal = document.getElementById("sizeModal");
        modal.classList.remove("hidden");
        modal.classList.add("flex");
    };

    function closeSizeModal() {
        const modal = document.getElementById("sizeModal");
        modal.classList.add("hidden");
        modal.classList.remove("flex");
        renderVariants(); // Update tampilan stok di tabel
    }

    document.getElementById("closeModal").onclick = closeSizeModal;
    document.getElementById("saveSizeModal").onclick = closeSizeModal;

    window.toggleSize = function (variantIndex, sizeId, checked) {
        let stokInput = document.getElementById(`modal-stok-${sizeId}`);
        
        // Pastikan array sizes ada
        if (!variants[variantIndex].sizes) variants[variantIndex].sizes = [];

        if (checked) {
            // Tambah jika belum ada
            if (!variants[variantIndex].sizes.find(s => s.id == sizeId)) {
                variants[variantIndex].sizes.push({ id: sizeId, stok: 0 });
            }
            stokInput.style.display = 'block';
            stokInput.focus();
        } else {
            // Hapus
            variants[variantIndex].sizes = variants[variantIndex].sizes.filter(s => s.id != sizeId);
            stokInput.style.display = 'none';
            stokInput.value = '';
        }
        updateHiddenJson();
    };

    window.updateSizeStok = function (variantIndex, sizeId, value) {
        let item = variants[variantIndex].sizes.find(s => s.id == sizeId);
        if (item) {
            item.stok = parseInt(value || 0);
        }
        updateHiddenJson();
    };

    // ====== DEFAULT SIZES (TANPA VARIAN WARNA) ======
    function renderDefaultSizes() {
        let html = "";

        sizes.forEach(size => {
            let found = defaultSizes.find(s => s.id == size.id);
            let stok  = found ? found.stok : "";
            let isChecked = found ? "checked" : "";
            let displayStyle = found ? "block" : "none";

            html += `
                <div class="bg-white border-2 border-gray-200 rounded-xl p-4 hover:border-[#CC8650] transition-all">
                    <div class="flex items-center justify-between mb-3">
                        <span class="font-semibold text-gray-700">${size.name}</span>
                        <input type="checkbox"
                            ${isChecked}
                            onchange="toggleDefaultSize(${size.id}, this.checked)"
                            class="w-5 h-5 text-[#CC8650] focus:ring-[#CC8650] rounded cursor-pointer">
                    </div>
                    <div id="default-stok-container-${size.id}" style="display: ${displayStyle}">
                        <label class="text-xs text-gray-500 mb-1 block">Stok:</label>
                        <input type="number" 
                            placeholder="0" 
                            class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 focus:border-[#CC8650] focus:ring-2 focus:ring-[#CC8650] focus:ring-opacity-20 transition-all"
                            value="${stok}"
                            oninput="updateDefaultSize(${size.id}, this.value)">
                    </div>
                </div>
            `;
        });

        defaultSizeList.innerHTML = html;
        updateHiddenJson();
    }

    window.toggleDefaultSize = function (sizeId, checked) {
        let container = document.getElementById(`default-stok-container-${sizeId}`);
        let input = container.querySelector('input');

        if (checked) {
            if (!defaultSizes.find(s => s.id == sizeId)) {
                defaultSizes.push({ id: sizeId, stok: 0 });
            }
            container.style.display = "block";
            input.focus();
        } else {
            defaultSizes = defaultSizes.filter(s => s.id != sizeId);
            container.style.display = "none";
            input.value = "";
        }

        updateHiddenJson();
    };

    window.updateDefaultSize = function (sizeId, value) {
        let item = defaultSizes.find(s => s.id == sizeId);
        if (item) {
            item.stok = parseInt(value || 0);
        }
        updateHiddenJson();
    };

    // Update input hidden JSON untuk dikirim ke server
    function updateHiddenJson() {
        variantsInput.value     = JSON.stringify(variants);
        defaultSizesInput.value = JSON.stringify(defaultSizes);
    }

    // ====== VALIDASI FORM SEBELUM SUBMIT ======
    form.addEventListener('submit', function (e) {
        let errors = [];
        const clientErrorBox  = document.getElementById('clientErrorBox');
        const clientErrorList = document.getElementById('clientErrorList');
        
        // Reset error
        clientErrorList.innerHTML = '';
        clientErrorBox.classList.add('hidden');

        // Pastikan JSON terupdate
        updateHiddenJson();

        const useVariants = useVariantsCheckbox.checked;

        if (useVariants) {
            if (variants.length === 0) {
                errors.push('Tambahkan minimal 1 varian warna.');
            }

            variants.forEach((v, index) => {
                if (!v.warna || v.warna.trim() === '') {
                    errors.push(`Varian #${index + 1}: warna wajib diisi.`);
                }
                if (!v.sizes || v.sizes.length === 0) {
                    errors.push(`Varian "${v.warna || ('#'+(index+1))}" wajib memiliki minimal 1 ukuran.`);
                } else {
                    v.sizes.forEach(s => {
                        let sizeName = sizes.find(sz => sz.id == s.id)?.name || 'Unknown';
                        if (s.stok === "" || parseInt(s.stok) < 0) {
                            errors.push(`Stok untuk ukuran ${sizeName} pada varian "${v.warna}" tidak valid.`);
                        }
                    });
                }
            });
        } else {
            if (defaultSizes.length === 0) {
                const confirmNoStock = confirm('Anda belum memilih ukuran sama sekali. Produk ini tidak akan memiliki stok. Lanjutkan?');
                if (!confirmNoStock) {
                    e.preventDefault();
                    return;
                }
            } else {
                defaultSizes.forEach(s => {
                    let sizeName = sizes.find(sz => sz.id == s.id)?.name || 'Unknown';
                    if (s.stok === "" || parseInt(s.stok) < 0) {
                        errors.push(`Stok untuk ukuran ${sizeName} tidak valid.`);
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
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aulia\dataD\KULIAH\PJBL\dishine\resources\views/admin/products_edit.blade.php ENDPATH**/ ?>