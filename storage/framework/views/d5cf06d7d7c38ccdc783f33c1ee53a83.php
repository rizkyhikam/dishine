<?php $__env->startSection('content'); ?>
<h1 class="text-3xl font-bold text-gray-800 mb-6">
    Edit Produk: <?php echo e($product->nama); ?>

</h1>


<?php if(session('success')): ?>
    <div class="alert alert-success bg-green-100 text-green-700 p-4 rounded mb-4">
        <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>


<?php if($errors->any()): ?>
    <div class="mb-4 bg-red-100 text-red-700 p-4 rounded">
        <p class="font-semibold mb-2">Ada kesalahan pada input:</p>
        <ul class="list-disc list-inside text-sm">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>


<div id="clientErrorBox" class="hidden mb-4 bg-red-100 text-red-700 p-4 rounded">
    <p class="font-semibold mb-2">Ada kesalahan pada form:</p>
    <ul id="clientErrorList" class="list-disc list-inside text-sm"></ul>
</div>




<div class="bg-white rounded-xl shadow-md mb-8 overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50">
        <h2 class="text-xl font-semibold text-gray-800">Edit Detail Produk</h2>
        <p class="text-gray-500 text-sm mt-1">Perbarui data produk di bawah ini.</p>
    </div>

    <div class="p-6">
        <form id="productEditForm"
              action="<?php echo e(route('admin.products.update', $product->id)); ?>"
              method="POST"
              enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            
            <h3 class="text-lg font-semibold text-gray-700 mb-3">1. Informasi Produk</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Produk <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama"
                           value="<?php echo e(old('nama', $product->nama)); ?>"
                           class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-200 <?php $__errorArgs = ['nama'];
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Kategori Produk <span class="text-red-500">*</span>
                    </label>
                    <select name="category_id"
                            class="w-full border rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-200 <?php $__errorArgs = ['category_id'];
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
                        <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Deskripsi Produk <span class="text-red-500">*</span>
                </label>
                <textarea name="deskripsi"
                          class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-200 <?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                          rows="3"
                          required><?php echo e(old('deskripsi', $product->deskripsi)); ?></textarea>
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

            
            <h3 class="text-lg font-semibold text-gray-700 mb-3">2. Harga Produk</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Harga Normal <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="harga_normal"
                           value="<?php echo e(old('harga_normal', $product->harga_normal)); ?>"
                           class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-200 <?php $__errorArgs = ['harga_normal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           required>
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Harga Reseller <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="harga_reseller"
                           value="<?php echo e(old('harga_reseller', $product->harga_reseller)); ?>"
                           class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-200 <?php $__errorArgs = ['harga_reseller'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           required>
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

            
            <h3 class="text-lg font-semibold text-gray-700 mb-3">3. Varian & Ukuran Produk</h3>

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

            <label class="flex items-center gap-2 mb-4">
                <input type="checkbox"
                       id="useVariants"
                       name="use_variants"
                       value="1"
                       class="w-4 h-4"
                       <?php echo e(old('use_variants', $hasVariants) ? 'checked' : ''); ?>>
                <span class="text-gray-700">Produk ini memiliki varian warna</span>
            </label>

            
            <div id="variantSection" class="bg-gray-50 border rounded-lg p-4 mb-6 hidden">
                <p class="text-sm text-gray-600 mb-3">
                    Tambahkan varian warna, lalu atur ukuran dan stoknya.
                </p>

                <table class="w-full text-sm mb-3">
                    <thead>
                        <tr class="text-gray-600 font-semibold text-left">
                            <th>Warna</th>
                            <th>Ukuran & Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="variantList"></tbody>
                </table>

                <button type="button"
                        id="addVariantBtn"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-500">
                    + Tambah Varian
                </button>
            </div>

            
            <div id="defaultSizeSection" class="bg-gray-50 border rounded-lg p-4 mb-6 hidden">
                <p class="text-sm text-gray-600 mb-3">
                    Pilih ukuran jika produk <span class="font-semibold">tidak</span> memiliki varian warna.
                </p>
                <div id="defaultSizeList"></div>
            </div>

            
            <h3 class="text-lg font-semibold text-gray-700 mb-3">4. Gambar Produk</h3>

            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Gambar Sampul (Cover)
                </label>
                <div class="mb-2">
                    <?php if($product->gambar): ?>
                        <img src="<?php echo e(asset('storage/' . $product->gambar)); ?>"
                             width="150"
                             class="rounded-md border"
                             alt="Cover">
                    <?php else: ?>
                        <p class="text-xs text-gray-500">Tidak ada gambar sampul.</p>
                    <?php endif; ?>
                </div>
                <input type="file" name="gambar"
                       class="w-full border rounded-lg px-3 py-2 <?php $__errorArgs = ['gambar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <small class="text-xs text-gray-500">
                    Kosongkan jika tidak ingin mengubah gambar sampul.
                </small>
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

            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Galeri Foto
                </label>

                <div class="flex flex-wrap gap-3 mb-2">
                    <?php $__empty_1 = true; $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="relative w-24 h-24">
                            <img src="<?php echo e(asset('storage/' . $image->path)); ?>"
                                 class="w-full h-full object-cover rounded-md border"
                                 alt="Gallery Image">
                            <div class="absolute bottom-0 left-0 right-0 bg-black/60 p-1 text-center">
                                <input type="checkbox"
                                       name="delete_images[]"
                                       value="<?php echo e($image->id); ?>"
                                       id="delete_img_<?php echo e($image->id); ?>"
                                       class="form-checkbox h-4 w-4 text-red-600 border-gray-300 rounded">
                                <label for="delete_img_<?php echo e($image->id); ?>"
                                       class="ml-1 text-xs text-white">
                                    Hapus
                                </label>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-xs text-gray-500">Belum ada foto galeri.</p>
                    <?php endif; ?>
                </div>

                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Tambah Foto Galeri Baru (Opsional)
                </label>
                <input type="file"
                       name="gallery[]"
                       class="w-full border rounded-lg px-3 py-2 <?php $__errorArgs = ['gallery.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       accept="image/*"
                       multiple>
                <small class="text-xs text-gray-500">
                    Tahan Ctrl/Cmd untuk pilih banyak foto.
                </small>
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

            
            <input type="hidden" name="variants" id="variantsData">
            <input type="hidden" name="default_sizes" id="defaultSizesData">

            <hr class="my-6 border-gray-200">

            <button type="submit"
                    class="bg-gray-800 text-white px-5 py-2 rounded-md hover:bg-gray-700 text-sm font-medium">
                Simpan Perubahan
            </button>
            <a href="<?php echo e(route('admin.products')); ?>"
               class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2 rounded-md text-sm font-medium">
                Batal
            </a>
        </form>
    </div>
</div>


<div id="sizeModal"
     class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
    <div class="bg-white p-6 rounded-xl w-[400px] shadow-lg">
        <h3 class="text-xl font-semibold mb-4 text-gray-700">Atur Ukuran</h3>

        <div id="modalSizeList" class="max-h-64 overflow-y-auto pr-2"></div>

        <div class="flex justify-end gap-2 mt-5">
            <button type="button" id="closeModal"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg">
                Batal
            </button>
            <button type="button" id="saveSizeModal"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-500 rounded-lg text-white">
                Simpan
            </button>
        </div>
    </div>
</div>




<script>
    // Data dari server
    let sizes = <?php echo json_encode(\App\Models\Size::all(), 15, 512) ?>;
    let variants      = <?php echo json_encode($initialVariants, 15, 512) ?>; // dari PHP
    let defaultSizes  = []; // default: kosong (kalau nanti punya tabel default sizes, bisa diisi dari backend)
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
        const use = useVariantsCheckbox.checked;
        variantSection.classList.toggle("hidden", !use);
        defaultSizeSection.classList.toggle("hidden", use);
        renderVariants();
        renderDefaultSizes();
    }
    applyVariantModeOnLoad();

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

    function renderVariants() {
        let html = "";
        variants.forEach((v, i) => {
            html += `
                <tr>
                    <td>
                        <input type="text"
                            class="border px-2 py-1 rounded w-full"
                            placeholder="Contoh: Hitam"
                            value="${v.warna || ''}"
                            onchange="variants[${i}].warna = this.value">
                    </td>
                    <td>
                        <button class="bg-gray-200 px-3 py-1 rounded text-xs" type="button"
                            onclick="openSizeModal(${i})">Atur ukuran & stok</button>
                    </td>
                    <td>
                        <button type="button"
                            class="bg-red-500 text-white px-3 py-1 rounded text-xs"
                            onclick="deleteVariant(${i})">Hapus</button>
                    </td>
                </tr>
            `;
        });

        variantList.innerHTML = html;
        updateHiddenJson();
    }

    function deleteVariant(index) {
        variants.splice(index, 1);
        renderVariants();
    }

    // Buka modal ukuran untuk varian tertentu
    window.openSizeModal = function (index) {
        editingVariant = index;
        let html = "";

        sizes.forEach(size => {
            let found = (variants[index].sizes || []).find(s => s.id === size.id);
            let stok  = found ? found.stok : "";

            html += `
                <div class="flex items-center gap-2 mb-2">
                    <input type="checkbox" ${found ? "checked" : ""}
                        onchange="toggleSize(${index}, ${size.id}, this.checked)">
                    <span class="w-20">${size.name}</span>
                    <input type="number" value="${stok}" placeholder="stok"
                        class="border px-2 py-1 rounded w-20"
                        onchange="updateSizeStok(${index}, ${size.id}, this.value)">
                </div>
            `;
        });

        document.getElementById("modalSizeList").innerHTML = html;
        document.getElementById("sizeModal").classList.remove("hidden");
    };

    document.getElementById("closeModal").onclick = () =>
        document.getElementById("sizeModal").classList.add("hidden");
    document.getElementById("saveSizeModal").onclick = () =>
        document.getElementById("sizeModal").classList.add("hidden");

    window.toggleSize = function (variantIndex, sizeId, checked) {
        if (checked) {
            if (!variants[variantIndex].sizes) variants[variantIndex].sizes = [];
            if (!variants[variantIndex].sizes.find(s => s.id === sizeId)) {
                variants[variantIndex].sizes.push({ id: sizeId, stok: 0 });
            }
        } else {
            variants[variantIndex].sizes =
                (variants[variantIndex].sizes || []).filter(s => s.id !== sizeId);
        }
        updateHiddenJson();
    };

    window.updateSizeStok = function (variantIndex, sizeId, value) {
        let item = (variants[variantIndex].sizes || []).find(s => s.id === sizeId);
        if (item) item.stok = parseInt(value || 0);
        updateHiddenJson();
    };

    // ====== DEFAULT SIZES (TANPA VARIAN WARNA) ======
    function renderDefaultSizes() {
        let html = "";

        sizes.forEach(size => {
            let found = defaultSizes.find(s => s.id === size.id);
            let stok  = found ? found.stok : "";

            html += `
                <div class="flex items-center gap-3 mb-2">
                    <input type="checkbox"
                        ${found ? "checked" : ""}
                        onchange="toggleDefaultSize(${size.id}, this.checked)">

                    <span class="w-20">${size.name}</span>

                    <input 
                        type="number" 
                        placeholder="stok" 
                        class="border rounded px-2 py-1 w-20"
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

    window.toggleDefaultSize = function (sizeId, checked) {
        let input = document.getElementById('stok-input-' + sizeId);

        if (checked) {
            if (!defaultSizes.find(s => s.id === sizeId)) {
                defaultSizes.push({ id: sizeId, stok: 0 });
            }
            input.style.display = "block";
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

    // ====== SYNC HIDDEN FIELD JSON ======
    function updateHiddenJson() {
        variantsInput.value     = JSON.stringify(variants || []);
        defaultSizesInput.value = JSON.stringify(defaultSizes || []);
    }
    // initial sync
    updateHiddenJson();

    // ====== VALIDASI CLIENT-SIDE ======
    form.addEventListener('submit', function (e) {
        let errors = [];
        const clientErrorBox  = document.getElementById('clientErrorBox');
        const clientErrorList = document.getElementById('clientErrorList');
        clientErrorList.innerHTML = '';
        clientErrorBox.classList.add('hidden');

        const nama          = form.querySelector('input[name="nama"]').value.trim();
        const categoryId    = form.querySelector('select[name="category_id"]').value;
        const deskripsi     = form.querySelector('textarea[name="deskripsi"]').value.trim();
        const hargaNormal   = form.querySelector('input[name="harga_normal"]').value;
        const hargaReseller = form.querySelector('input[name="harga_reseller"]').value;
        const useVariants   = useVariantsCheckbox.checked;

        if (!nama) {
            errors.push('Nama produk wajib diisi.');
        }
        if (!categoryId) {
            errors.push('Kategori produk wajib dipilih.');
        }
        if (!deskripsi) {
            errors.push('Deskripsi produk wajib diisi.');
        }
        if (!hargaNormal || parseInt(hargaNormal) <= 0) {
            errors.push('Harga normal wajib diisi dan harus lebih dari 0.');
        }
        if (!hargaReseller || parseInt(hargaReseller) <= 0) {
            errors.push('Harga reseller wajib diisi dan harus lebih dari 0.');
        }

        if (useVariants) {
            if (!variants || variants.length === 0) {
                errors.push('Tambahkan minimal 1 varian warna.');
            }
            (variants || []).forEach((v, index) => {
                if (!v.warna || v.warna.trim() === '') {
                    errors.push(`Varian #${index + 1}: warna wajib diisi.`);
                }
                if (!v.sizes || v.sizes.length === 0) {
                    errors.push(`Varian "${v.warna || ('#'+(index+1))}" wajib memiliki minimal 1 ukuran.`);
                } else {
                    v.sizes.forEach(s => {
                        if (!s.stok || s.stok <= 0) {
                            errors.push(`Stok untuk ukuran ID ${s.id} pada varian "${v.warna || ('#'+(index+1))}" wajib diisi dan > 0.`);
                        }
                    });
                }
            });
        } else {
            if (defaultSizes.length === 0) {
                const lanjut = confirm('Anda belum memilih ukuran sama sekali. Lanjutkan tanpa ukuran?');
                if (!lanjut) {
                    e.preventDefault();
                    return;
                }
            } else {
                defaultSizes.forEach(s => {
                    if (!s.stok || s.stok <= 0) {
                        errors.push('Semua stok ukuran yang dicentang wajib diisi dan harus > 0.');
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

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/admin/products_edit.blade.php ENDPATH**/ ?>