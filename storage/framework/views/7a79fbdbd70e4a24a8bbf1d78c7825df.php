<?php $__env->startSection('content'); ?>
<!-- Header Section -->
<div class="mb-8">
    <div class="flex items-center space-x-4">
        <div class="bg-gradient-to-r from-[#CC8650] to-[#D4BA98] p-4 rounded-2xl shadow-lg">
            <i data-lucide="package" class="w-8 h-8 text-white"></i>
        </div>
        <div>
            <h1 class="text-4xl font-bold text-gray-800">Manajemen Produk</h1>
            <p class="text-gray-600 mt-1">Kelola produk dan stok toko Anda</p>
        </div>
    </div>
</div>


<?php if(session('success')): ?>
    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 flex items-center shadow-sm">
        <div class="bg-green-100 p-2 rounded-lg mr-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
        </div>
        <div>
            <p class="font-semibold">Berhasil!</p>
            <p class="text-sm"><?php echo e(session('success')); ?></p>
        </div>
    </div>
<?php endif; ?>

<?php if($errors->any()): ?>
    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6 shadow-sm">
        <div class="flex items-start">
            <div class="bg-red-100 p-2 rounded-lg mr-3">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-600"></i>
            </div>
            <div class="flex-1">
                <p class="font-semibold mb-2">Ada kesalahan pada input:</p>
                <ul class="list-disc list-inside space-y-1 text-sm">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>
    </div>
<?php endif; ?>

<div id="clientErrorBox" class="hidden bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6 shadow-sm">
    <div class="flex items-start">
        <div class="bg-red-100 p-2 rounded-lg mr-3">
            <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600"></i>
        </div>
        <div class="flex-1">
            <p class="font-semibold mb-2">Ada kesalahan pada form:</p>
            <ul id="clientErrorList" class="list-disc list-inside space-y-1 text-sm"></ul>
        </div>
    </div>
</div>


<div class="bg-white rounded-2xl shadow-md mb-8 overflow-hidden">
    <div class="bg-gradient-to-r from-[#CC8650] to-[#D4BA98] px-8 py-6">
        <div class="flex items-center space-x-3">
            <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                <i data-lucide="plus-circle" class="w-6 h-6 text-white"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-white">Tambah Produk Baru</h2>
                <p class="text-white text-sm opacity-90 mt-1">Isi data di bawah ini untuk menambahkan produk baru.</p>
            </div>
        </div>
    </div>

    <div class="p-8">
        <form id="productCreateForm" action="<?php echo e(route('admin.products.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            
            <div class="mb-8">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="bg-[#CC8650] bg-opacity-10 p-2 rounded-lg">
                        <i data-lucide="info" class="w-5 h-5 text-[#CC8650]"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">1. Informasi Produk</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Nama Produk <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="tag" class="w-5 h-5 text-gray-400"></i>
                            </div>
                            <input type="text" name="nama" value="<?php echo e(old('nama')); ?>"
                                class="w-full border-2 border-gray-200 rounded-xl pl-10 pr-4 py-3 focus:border-[#CC8650] focus:ring focus:ring-[#CC8650] focus:ring-opacity-20 transition-all <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="Contoh: Blouse Casual" required>
                        </div>
                        <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-xs text-red-600 mt-1 flex items-center">
                                <i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i><?php echo e($message); ?>

                            </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Kategori Produk <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="layers" class="w-5 h-5 text-gray-400"></i>
                            </div>
                            <select name="category_id"
                                class="w-full border-2 border-gray-200 rounded-xl pl-10 pr-4 py-3 bg-white focus:border-[#CC8650] focus:ring focus:ring-[#CC8650] focus:ring-opacity-20 transition-all <?php $__errorArgs = ['category_id'];
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
                        </div>
                        <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-xs text-red-600 mt-1 flex items-center">
                                <i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i><?php echo e($message); ?>

                            </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                
                <div class="mt-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Deskripsi Produk <span class="text-red-500">*</span>
                    </label>
                    <textarea name="deskripsi"
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-[#CC8650] focus:ring focus:ring-[#CC8650] focus:ring-opacity-20 transition-all <?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        rows="4" placeholder="Deskripsikan produk Anda secara detail..." required><?php echo e(old('deskripsi')); ?></textarea>
                    <?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-xs text-red-600 mt-1 flex items-center">
                            <i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i><?php echo e($message); ?>

                        </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            
            <div class="mb-8">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="bg-[#D4BA98] bg-opacity-30 p-2 rounded-lg">
                        <i data-lucide="dollar-sign" class="w-5 h-5 text-[#AE8B56]"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">2. Harga Produk</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Harga Normal <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 font-semibold">Rp</span>
                            </div>
                            <input type="number" name="harga_normal" value="<?php echo e(old('harga_normal')); ?>"
                                class="w-full border-2 border-gray-200 rounded-xl pl-12 pr-4 py-3 focus:border-[#CC8650] focus:ring focus:ring-[#CC8650] focus:ring-opacity-20 transition-all <?php $__errorArgs = ['harga_normal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="150000" required>
                        </div>
                        <?php $__errorArgs = ['harga_normal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-xs text-red-600 mt-1 flex items-center">
                                <i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i><?php echo e($message); ?>

                            </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Harga Reseller <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 font-semibold">Rp</span>
                            </div>
                            <input type="number" name="harga_reseller" value="<?php echo e(old('harga_reseller')); ?>"
                                class="w-full border-2 border-gray-200 rounded-xl pl-12 pr-4 py-3 focus:border-[#CC8650] focus:ring focus:ring-[#CC8650] focus:ring-opacity-20 transition-all <?php $__errorArgs = ['harga_reseller'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="120000" required>
                        </div>
                        <?php $__errorArgs = ['harga_reseller'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-xs text-red-600 mt-1 flex items-center">
                                <i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i><?php echo e($message); ?>

                            </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            
            <div class="mb-8">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="bg-[#AE8B56] bg-opacity-10 p-2 rounded-lg">
                        <i data-lucide="palette" class="w-5 h-5 text-[#AE8B56]"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">3. Varian & Ukuran Produk</h3>
                </div>

                <label class="flex items-center gap-3 mb-4 bg-[#F0E7DB] p-4 rounded-xl cursor-pointer hover:bg-[#EBE6E6] transition-all">
                    <input type="checkbox" id="useVariants" name="use_variants" value="1"
                        class="w-5 h-5 text-[#CC8650] rounded focus:ring-[#CC8650]" <?php echo e(old('use_variants') ? 'checked' : ''); ?>>
                    <div class="flex items-center space-x-2">
                        <i data-lucide="check-square" class="w-5 h-5 text-[#AE8B56]"></i>
                        <span class="text-gray-800 font-semibold">Produk ini memiliki varian warna</span>
                    </div>
                </label>

                
                <div id="variantSection" class="hidden bg-gradient-to-br from-[#F0E7DB] to-[#EBE6E6] border-2 border-[#D4BA98] rounded-2xl p-6 mb-6">
                    <p class="text-sm text-gray-700 mb-4 flex items-center">
                        <i data-lucide="info" class="w-4 h-4 mr-2 text-[#AE8B56]"></i>
                        Tambahkan varian warna, lalu atur ukuran dan stoknya.
                    </p>

                    <div class="bg-white rounded-xl overflow-hidden shadow-sm mb-4">
                        <table class="w-full text-sm">
                            <thead class="bg-[#CC8650] text-white">
                                <tr>
                                    <th class="px-4 py-3 text-left font-bold">Warna</th>
                                    <th class="px-4 py-3 text-left font-bold">Ukuran & Stok</th>
                                    <th class="px-4 py-3 text-left font-bold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="variantList" class="divide-y divide-gray-200"></tbody>
                        </table>
                    </div>

                    <button type="button" id="addVariantBtn"
                        class="bg-gradient-to-r from-[#CC8650] to-[#D4BA98] text-white px-6 py-3 rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all font-semibold flex items-center space-x-2">
                        <i data-lucide="plus" class="w-5 h-5"></i>
                        <span>Tambah Varian</span>
                    </button>
                </div>

                
                <div id="defaultSizeSection" class="bg-gradient-to-br from-[#F0E7DB] to-[#EBE6E6] border-2 border-[#D4BA98] rounded-2xl p-6">
                    <p class="text-sm text-gray-700 mb-4 flex items-center">
                        <i data-lucide="ruler" class="w-4 h-4 mr-2 text-[#AE8B56]"></i>
                        Pilih ukuran jika produk <span class="font-bold mx-1">tidak</span> memiliki varian warna.
                    </p>
                    <div id="defaultSizeList" class="space-y-3"></div>
                </div>
            </div>

            
            <div class="mb-8">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="bg-[#D0B682] bg-opacity-20 p-2 rounded-lg">
                        <i data-lucide="image" class="w-5 h-5 text-[#8B6F47]"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">4. Gambar Produk</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Gambar Sampul <span class="text-red-500">*</span>
                        </label>
                        <div class="border-2 border-dashed border-[#D4BA98] rounded-xl p-4 hover:border-[#CC8650] transition-all bg-[#F0E7DB] bg-opacity-30">
                            <input type="file" name="gambar"
                                class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#CC8650] file:text-white hover:file:bg-[#AE8B56] <?php $__errorArgs = ['gambar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        </div>
                        <?php $__errorArgs = ['gambar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-xs text-red-600 mt-1 flex items-center">
                                <i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i><?php echo e($message); ?>

                            </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Galeri Foto <span class="text-gray-500 text-xs">(Opsional)</span>
                        </label>
                        <div class="border-2 border-dashed border-[#D4BA98] rounded-xl p-4 hover:border-[#CC8650] transition-all bg-[#F0E7DB] bg-opacity-30">
                            <input type="file" name="gallery[]" multiple
                                class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#AE8B56] file:text-white hover:file:bg-[#8B6F47] <?php $__errorArgs = ['gallery.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        </div>
                        <?php $__errorArgs = ['gallery.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-xs text-red-600 mt-1 flex items-center">
                                <i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i><?php echo e($message); ?>

                            </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            
            <input type="hidden" name="variants" id="variantsData">
            <input type="hidden" name="default_sizes" id="defaultSizesData">

            
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="<?php echo e(route('admin.products')); ?>" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 font-semibold transition-all flex items-center space-x-2">
                    <i data-lucide="x" class="w-5 h-5"></i>
                    <span>Batal</span>
                </a>
                <button type="submit"
                    class="px-8 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all font-bold flex items-center space-x-2">
                    <i data-lucide="save" class="w-5 h-5"></i>
                    <span>Simpan Produk</span>
                </button>
            </div>
        </form>
    </div>
</div>


<div id="sizeModal" class="fixed inset-0 bg-black bg-opacity-60 hidden justify-center items-center z-50">
    <div class="bg-white p-6 rounded-2xl w-[450px] shadow-2xl">
        <div class="flex items-center space-x-3 mb-5">
            <div class="bg-[#CC8650] bg-opacity-10 p-2 rounded-lg">
                <i data-lucide="ruler" class="w-6 h-6 text-[#CC8650]"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800">Atur Ukuran & Stok</h3>
        </div>

        <div id="modalSizeList" class="max-h-80 overflow-y-auto pr-2 space-y-3"></div>

        <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
            <button type="button" id="closeModal"
                class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 rounded-xl font-semibold transition-all flex items-center space-x-2">
                <i data-lucide="x" class="w-4 h-4"></i>
                <span>Batal</span>
            </button>

            <button type="button" id="saveSizeModal"
                class="px-5 py-2.5 bg-gradient-to-r from-[#CC8650] to-[#D4BA98] hover:shadow-lg rounded-xl text-white font-semibold transition-all flex items-center space-x-2">
                <i data-lucide="check" class="w-4 h-4"></i>
                <span>Simpan</span>
            </button>
        </div>
    </div>
</div>


<div class="bg-white rounded-2xl shadow-md mb-6 overflow-hidden">
    <div class="bg-gradient-to-r from-[#CC8650] to-[#D4BA98] px-8 py-6">
        <div class="flex items-center space-x-3 mb-4">
            <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                <i data-lucide="filter" class="w-5 h-5 text-white"></i>
            </div>
            <h2 class="text-xl font-bold text-white">Filter & Pencarian Produk</h2>
        </div>
        
        <form action="<?php echo e(route('admin.products')); ?>" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div class="md:col-span-3">
                    <label for="search_nama" class="block text-sm font-semibold text-white mb-2">
                        Cari Nama Produk
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="search" class="w-5 h-5 text-gray-400"></i>
                        </div>
                        <input type="text" name="search_nama" id="search_nama"
                            class="w-full border-2 border-white border-opacity-30 bg-white rounded-xl pl-10 pr-4 py-3 text-sm focus:border-white focus:ring focus:ring-white focus:ring-opacity-30 transition-all"
                            placeholder="Ketik nama produk..." value="<?php echo e($filters['search_nama'] ?? ''); ?>">
                    </div>
                </div>

                <div class="flex space-x-2">
                    <button type="submit"
                        class="flex-1 bg-white text-[#CC8650] px-4 py-3 rounded-xl hover:shadow-lg font-bold text-sm flex items-center justify-center transition-all">
                        <i data-lucide="search" class="w-4 h-4 mr-2"></i>
                        Cari
                    </button>
                    <a href="<?php echo e(route('admin.products')); ?>"
                        class="flex-1 bg-[#8B6F47] text-white px-4 py-3 rounded-xl hover:bg-[#725a38] font-semibold text-sm flex items-center justify-center transition-all">
                        <i data-lucide="x" class="w-4 h-4 mr-1"></i>
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="bg-white rounded-2xl shadow-md overflow-hidden">
    <div class="bg-gradient-to-r from-[#CC8650] to-[#D4BA98] px-8 py-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                    <i data-lucide="package-search" class="w-6 h-6 text-white"></i>
                </div>
                <h2 class="text-2xl font-bold text-white">Daftar Produk</h2>
            </div>
            <span class="bg-white bg-opacity-20 text-white px-4 py-2 rounded-full text-sm font-semibold">
                <?php echo e($products->total()); ?> Produk
            </span>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">No</th>
                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Produk</th>
                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Kategori</th>
                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Harga</th>
                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Stok</th>
                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gradient-to-r hover:from-[#F0E7DB] hover:to-transparent transition-all duration-150">
                        <td class="px-8 py-5 whitespace-nowrap">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-[#CC8650] bg-opacity-10 text-[#CC8650] font-bold">
                                <?php echo e($index + 1); ?>

                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center space-x-4">
                                <?php if($product->gambar): ?>
                                    <img src="<?php echo e(asset('storage/' . $product->gambar)); ?>" 
                                        class="w-16 h-16 rounded-xl object-cover shadow-md border-2 border-[#D4BA98]">
                                <?php else: ?>
                                    <div class="w-16 h-16 rounded-xl bg-gray-200 flex items-center justify-center">
                                        <i data-lucide="image-off" class="w-6 h-6 text-gray-400"></i>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <p class="font-bold text-gray-900"><?php echo e($product->nama); ?></p>
                                    <p class="text-xs text-gray-500 line-clamp-1"><?php echo e(Str::limit($product->deskripsi, 40)); ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-[#D4BA98] text-white">
                                <i data-lucide="tag" class="w-3 h-3 mr-1"></i>
                                <?php echo e($product->category->name ?? 'N/A'); ?>

                            </span>
                        </td>
                        <td class="px-8 py-5 whitespace-nowrap">
                            <div class="space-y-1">
                                <div class="flex items-center text-sm">
                                    <span class="text-gray-500 text-xs mr-2">Normal:</span>
                                    <span class="font-bold text-gray-900">Rp<?php echo e(number_format($product->harga_normal, 0, ',', '.')); ?></span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <span class="text-gray-500 text-xs mr-2">Reseller:</span>
                                    <span class="font-bold text-[#CC8650]">Rp<?php echo e(number_format($product->harga_reseller, 0, ',', '.')); ?></span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <div class="bg-[#AE8B56] bg-opacity-10 p-2 rounded-lg">
                                    <i data-lucide="package" class="w-4 h-4 text-[#AE8B56]"></i>
                                </div>
                                <span class="text-lg font-bold text-gray-900"><?php echo e($product->total_stok); ?></span>
                            </div>
                        </td>
                        <td class="px-8 py-5 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <a href="<?php echo e(route('admin.products.edit', $product->id)); ?>"
                                    class="inline-flex items-center px-4 py-2 bg-[#AE8B56] text-white text-sm font-semibold rounded-lg hover:bg-[#8B6F47] transition-all">
                                    <i data-lucide="edit" class="w-4 h-4 mr-1"></i>
                                    Edit
                                </a>

                                <form action="<?php echo e(route('admin.products.delete', $product->id)); ?>"
                                    method="POST" class="inline"
                                    onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button class="inline-flex items-center px-4 py-2 bg-red-50 text-red-600 text-sm font-semibold rounded-lg hover:bg-red-100 transition-all border border-red-200">
                                        <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center py-16">
                            <div class="flex flex-col items-center">
                                <?php if(request()->has('search_nama')): ?>
                                    <div class="bg-[#F0E7DB] p-6 rounded-full mb-4">
                                        <i data-lucide="search-x" class="w-16 h-16 text-[#AE8B56]"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-700 mb-2">Tidak Ada Hasil</h3>
                                    <p class="text-gray-500 mb-4">Tidak ada produk yang cocok dengan pencarian Anda.</p>
                                    <a href="<?php echo e(route('admin.products')); ?>" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#CC8650] to-[#D4BA98] text-white rounded-xl font-semibold hover:shadow-lg transition-all">
                                        <i data-lucide="x" class="w-4 h-4 mr-2"></i>
                                        Hapus Filter
                                    </a>
                                <?php else: ?>
                                    <div class="bg-[#F0E7DB] p-6 rounded-full mb-4">
                                        <i data-lucide="package-x" class="w-16 h-16 text-[#AE8B56]"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-700 mb-2">Belum Ada Produk</h3>
                                    <p class="text-gray-500">Silakan tambahkan produk pertama Anda menggunakan form di atas.</p>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    
    <?php if($products->hasPages()): ?>
        <div class="bg-gradient-to-r from-[#EBE6E6] to-[#F0E7DB] px-8 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    Menampilkan <span class="font-bold text-[#CC8650]"><?php echo e($products->firstItem()); ?></span> - 
                    <span class="font-bold text-[#CC8650]"><?php echo e($products->lastItem()); ?></span> dari 
                    <span class="font-bold text-[#CC8650]"><?php echo e($products->total()); ?></span> produk
                </div>
                <div>
                    <?php echo e($products->links()); ?>

                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    // Data dari server
    let sizes = <?php echo json_encode(\App\Models\Size::all(), 15, 512) ?>;
    let variants = [];
    let defaultSizes = [];
    let editingVariant = -1;

    const useVariantsCheckbox = document.getElementById("useVariants");
    const variantSection = document.getElementById("variantSection");
    const defaultSizeSection = document.getElementById("defaultSizeSection");
    const variantList = document.getElementById("variantList");
    const defaultSizeList = document.getElementById("defaultSizeList");
    const variantsInput = document.getElementById("variantsData");
    const defaultSizesInput = document.getElementById("defaultSizesData");
    const form = document.getElementById("productCreateForm");

    // Toggle varian section
    useVariantsCheckbox.addEventListener("change", function () {
        const use = this.checked;
        variantSection.classList.toggle("hidden", !use);
        defaultSizeSection.classList.toggle("hidden", use);
    });

    // Tambah varian
    document.getElementById("addVariantBtn").onclick = function () {
        variants.push({ warna: "", sizes: [] });
        renderVariants();
    };

    // Update warna varian
    window.updateVariantColor = function(index, value) {
        variants[index].warna = value;
        updateHiddenJson();
    };

    // Render daftar varian
    function renderVariants() {
        let html = "";
        variants.forEach((v, i) => {
            html += `
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <input type="text" 
                            class="border-2 border-gray-200 px-3 py-2 rounded-lg w-full focus:border-[#CC8650] focus:ring focus:ring-[#CC8650] focus:ring-opacity-20 transition-all"
                            placeholder="Contoh: Hitam"
                            value="${v.warna}"
                            oninput="updateVariantColor(${i}, this.value)">
                    </td>

                    <td class="px-4 py-3">
                        <button class="bg-[#D4BA98] text-white px-4 py-2 rounded-lg text-sm hover:bg-[#CC8650] transition font-semibold flex items-center space-x-2" type="button"
                            onclick="openSizeModal(${i})">
                            <i data-lucide="settings" class="w-4 h-4"></i>
                            <span>Atur (${v.sizes.length})</span>
                        </button>
                    </td>

                    <td class="px-4 py-3">
                        <button type="button" class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-600 transition font-semibold flex items-center space-x-2"
                            onclick="deleteVariant(${i})">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                            <span>Hapus</span>
                        </button>
                    </td>
                </tr>
            `;
        });

        variantList.innerHTML = html;
        lucide.createIcons();
        updateHiddenJson();
    }

    // Hapus varian
    window.deleteVariant = function(index) {
        variants.splice(index, 1);
        renderVariants();
    }

    // Buka modal ukuran
    window.openSizeModal = function (index) {
        editingVariant = index;
        let html = "";

        sizes.forEach(size => {
            let found = variants[index].sizes.find(s => s.id === size.id);
            let stok = found ? found.stok : "";

            html += `
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <input type="checkbox" ${found ? "checked" : ""}
                        class="w-5 h-5 text-[#CC8650] rounded focus:ring-[#CC8650]"
                        onchange="toggleSize(${index}, ${size.id}, this.checked)">
                    <span class="w-24 font-bold text-gray-700">${size.name}</span>

                    <input type="number" value="${stok}" placeholder="Stok"
                        class="border-2 border-gray-200 px-3 py-2 rounded-lg w-28 focus:border-[#CC8650] focus:ring focus:ring-[#CC8650] focus:ring-opacity-20 transition-all"
                        style="display: ${found ? 'block' : 'none'}"
                        id="modal-stok-${index}-${size.id}"
                        onchange="updateSizeStok(${index}, ${size.id}, this.value)">
                </div>
            `;
        });

        document.getElementById("modalSizeList").innerHTML = html;
        document.getElementById("sizeModal").classList.remove("hidden");
        document.getElementById("sizeModal").classList.add("flex");
        lucide.createIcons();
    };

    // Tutup modal
    document.getElementById("closeModal").onclick = () => {
        document.getElementById("sizeModal").classList.add("hidden");
        document.getElementById("sizeModal").classList.remove("flex");
    };

    // Simpan modal
    document.getElementById("saveSizeModal").onclick = () => {
        document.getElementById("sizeModal").classList.add("hidden");
        document.getElementById("sizeModal").classList.remove("flex");
        renderVariants();
    };

    // Toggle ukuran di modal
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

    // Update stok ukuran
    window.updateSizeStok = function (variantIndex, sizeId, value) {
        let item = variants[variantIndex].sizes.find(s => s.id === sizeId);
        if (item) item.stok = parseInt(value || 0);
        updateHiddenJson();
    };

    // Render ukuran default
    function renderDefaultSizes() {
        let html = "";

        sizes.forEach(size => {
            let found = defaultSizes.find(s => s.id === size.id);
            let stok = found ? found.stok : "";

            html += `
                <div class="flex items-center gap-3 p-3 bg-white rounded-lg hover:bg-gray-50 transition">
                    <input type="checkbox"
                        ${found ? "checked" : ""}
                        class="w-5 h-5 text-[#CC8650] rounded focus:ring-[#CC8650]"
                        onchange="toggleDefaultSize(${size.id}, this.checked)">
                    <span class="w-24 font-bold text-gray-700">${size.name}</span>

                    <input type="number" placeholder="Stok" 
                        class="border-2 border-gray-200 px-3 py-2 rounded-lg w-28 focus:border-[#CC8650] focus:ring focus:ring-[#CC8650] focus:ring-opacity-20 transition-all"
                        value="${stok}"
                        onchange="updateDefaultSize(${size.id}, this.value)"
                        style="display: ${found ? 'block' : 'none'}"
                        id="stok-input-${size.id}">
                </div>
            `;
        });

        defaultSizeList.innerHTML = html;
        lucide.createIcons();
    }

    // Toggle ukuran default
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

    // Update ukuran default
    window.updateDefaultSize = function (sizeId, value) {
        let item = defaultSizes.find(s => s.id === sizeId);
        if (item) item.stok = parseInt(value || 0);
        updateHiddenJson();
    };

    // Update hidden JSON
    function updateHiddenJson() {
        variantsInput.value = JSON.stringify(variants);
        defaultSizesInput.value = JSON.stringify(defaultSizes);
    }

    // Validasi form submit
    form.addEventListener('submit', function (e) {
        let errors = [];
        const clientErrorBox = document.getElementById('clientErrorBox');
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
            lucide.createIcons();
        }
    });

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        renderDefaultSizes();
        lucide.createIcons();
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aulia\dataD\KULIAH\PJBL\dishine\resources\views/admin/products.blade.php ENDPATH**/ ?>