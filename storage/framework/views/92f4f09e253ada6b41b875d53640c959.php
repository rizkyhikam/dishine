<?php $__env->startSection('content'); ?>
<h1 class="text-3xl font-bold text-gray-800 mb-6">Manajemen Produk</h1>


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
        <h2 class="text-xl font-semibold text-gray-800">Tambah Produk Baru</h2>
        <p class="text-gray-500 text-sm mt-1">Isi data di bawah ini untuk menambahkan produk baru.</p>
    </div>

    <div class="p-6">

        <form id="productCreateForm" action="<?php echo e(route('admin.products.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            
            <h3 class="text-lg font-semibold text-gray-700 mb-3">1. Informasi Produk</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="nama"
                        value="<?php echo e(old('nama')); ?>"
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Produk <span class="text-red-500">*</span></label>
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
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Produk <span class="text-red-500">*</span></label>
                <textarea name="deskripsi"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-200 <?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    rows="3" required><?php echo e(old('deskripsi')); ?></textarea>
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Normal <span class="text-red-500">*</span></label>
                    <input type="number" name="harga_normal"
                           value="<?php echo e(old('harga_normal')); ?>"
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Reseller <span class="text-red-500">*</span></label>
                    <input type="number" name="harga_reseller"
                        value="<?php echo e(old('harga_reseller')); ?>"
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

            <label class="flex items-center gap-2 mb-4">
                <input type="checkbox" id="useVariants" name="use_variants" value="1"
                       class="w-4 h-4" <?php echo e(old('use_variants') ? 'checked' : ''); ?>>
                <span class="text-gray-700">Produk ini memiliki varian warna</span>
            </label>

            
            <div id="variantSection" class="hidden bg-gray-50 border rounded-lg p-4 mb-6">
                <p class="text-sm text-gray-600 mb-3">Tambahkan varian warna, lalu atur ukuran dan stoknya.</p>

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

            
            <div id="defaultSizeSection" class="bg-gray-50 border rounded-lg p-4 mb-6">
                <p class="text-sm text-gray-600 mb-3">
                    Pilih ukuran jika produk <span class="font-semibold">tidak</span> memiliki varian warna.
                </p>
                <div id="defaultSizeList"></div>
            </div>

            
            <h3 class="text-lg font-semibold text-gray-700 mb-3">4. Gambar Produk</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Sampul <span class="text-red-500">*</span></label>
                    <input type="file" name="gambar"
                        class="w-full border rounded-lg px-3 py-2 <?php $__errorArgs = ['gambar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Galeri Foto</label>
                    <input type="file" name="gallery[]" multiple
                        class="w-full border rounded-lg px-3 py-2 <?php $__errorArgs = ['gallery.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
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

            
            <button type="submit"
                class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-500">
                Simpan Produk
            </button>

        </form>
    </div>
</div>


<div id="sizeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
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
    let variants = [];      // [{warna: 'Hitam', sizes: [{id: 1, stok: 10}, ...]}]
    let defaultSizes = [];  // [{id: 1, stok: 5}, ...]
    let editingVariant = -1;

    const useVariantsCheckbox = document.getElementById("useVariants");
    const variantSection      = document.getElementById("variantSection");
    const defaultSizeSection  = document.getElementById("defaultSizeSection");
    const variantList         = document.getElementById("variantList");
    const defaultSizeList     = document.getElementById("defaultSizeList");
    const variantsInput       = document.getElementById("variantsData");
    const defaultSizesInput   = document.getElementById("defaultSizesData");
    const form                = document.getElementById("productCreateForm");

    // Toggle mode: varian warna / default size
    useVariantsCheckbox.addEventListener("change", function () {
        const use = this.checked;
        variantSection.classList.toggle("hidden", !use);
        defaultSizeSection.classList.toggle("hidden", use);
    });

    // Tambah varian baru
    document.getElementById("addVariantBtn").onclick = function () {
        variants.push({ warna: "", sizes: [] });
        renderVariants();
    };

    // Fungsi update warna (PENTING: Ini yang diperbaiki)
    window.updateVariantColor = function(index, value) {
        variants[index].warna = value;
        updateHiddenJson();
    };

    // Render tabel varian
    function renderVariants() {
        let html = "";
        variants.forEach((v, i) => {
            html += `
                <tr>
                    <td class="p-2">
                        <input type="text" 
                            class="border px-2 py-1 rounded w-full"
                            placeholder="Contoh: Hitam"
                            value="${v.warna}"
                            oninput="updateVariantColor(${i}, this.value)"> <!-- Pakai oninput & fungsi khusus -->
                    </td>

                    <td class="p-2">
                        <button class="bg-gray-200 px-3 py-1 rounded text-xs hover:bg-gray-300 transition" type="button"
                            onclick="openSizeModal(${i})">
                            Atur ukuran (${v.sizes.length} dipilih)
                        </button>
                    </td>

                    <td class="p-2">
                        <button type="button" class="bg-red-500 text-white px-3 py-1 rounded text-xs hover:bg-red-600 transition"
                            onclick="deleteVariant(${i})">Hapus</button>
                    </td>
                </tr>
            `;
        });

        variantList.innerHTML = html;
        updateHiddenJson();
    }

    window.deleteVariant = function(index) {
        variants.splice(index, 1);
        renderVariants();
    }

    // Buka modal ukuran untuk varian tertentu
    window.openSizeModal = function (index) {
        editingVariant = index;
        let html = "";

        sizes.forEach(size => {
            let found = variants[index].sizes.find(s => s.id === size.id);
            let stok  = found ? found.stok : "";

            html += `
                <div class="flex items-center gap-2 mb-2">
                    <input type="checkbox" ${found ? "checked" : ""}
                        onchange="toggleSize(${index}, ${size.id}, this.checked)">
                    <span class="w-20 font-medium">${size.name}</span>
                    <input type="number" value="${stok}" placeholder="Stok"
                        class="border px-2 py-1 rounded w-24 focus:ring-2 focus:ring-blue-200 outline-none"
                        style="display: ${found ? 'block' : 'none'}"
                        id="modal-stok-${index}-${size.id}"
                        onchange="updateSizeStok(${index}, ${size.id}, this.value)">
                </div>
            `;
        });

        document.getElementById("modalSizeList").innerHTML = html;
        document.getElementById("sizeModal").classList.remove("hidden");
        document.getElementById("sizeModal").classList.add("flex"); // Pastikan flex agar tengah
    };

    document.getElementById("closeModal").onclick = () => {
        document.getElementById("sizeModal").classList.add("hidden");
        document.getElementById("sizeModal").classList.remove("flex");
    };

    document.getElementById("saveSizeModal").onclick = () => {
        document.getElementById("sizeModal").classList.add("hidden");
        document.getElementById("sizeModal").classList.remove("flex");
        renderVariants(); // Re-render untuk update jumlah ukuran
    };

    // Toggle ukuran di dalam varian
    window.toggleSize = function (variantIndex, sizeId, checked) {
        let stokInput = document.getElementById(`modal-stok-${variantIndex}-${sizeId}`);
        
        if (checked) {
            if (!variants[variantIndex].sizes.find(s => s.id === sizeId)) {
                variants[variantIndex].sizes.push({ id: sizeId, stok: 0 });
            }
            stokInput.style.display = 'block';
            stokInput.focus();
        } else {
            variants[variantIndex].sizes =
                variants[variantIndex].sizes.filter(s => s.id !== sizeId);
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

    // Render default sizes (tanpa varian warna)
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
                    
                    <span class="w-20 font-medium">${size.name}</span>

                    <input 
                        type="number" 
                        placeholder="Stok" 
                        class="border rounded px-2 py-1 w-24 focus:ring-2 focus:ring-blue-200 outline-none"
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

    // Update input hidden JSON setiap ada perubahan
    function updateHiddenJson() {
        variantsInput.value     = JSON.stringify(variants);
        defaultSizesInput.value = JSON.stringify(defaultSizes);
        
        // Debugging (bisa dihapus nanti)
        console.log('Variants JSON:', variantsInput.value);
    }

    // ===============================
    // VALIDASI CLIENT-SIDE SEBELUM SUBMIT
    // ===============================
    form.addEventListener('submit', function (e) {
        let errors = [];
        const clientErrorBox  = document.getElementById('clientErrorBox');
        const clientErrorList = document.getElementById('clientErrorList');
        clientErrorList.innerHTML = '';
        clientErrorBox.classList.add('hidden');

        // Pastikan JSON terupdate sebelum submit
        updateHiddenJson();

        const useVariants   = useVariantsCheckbox.checked;

        // Validasi ukuran & stok
        if (useVariants) {
            // Harus ada minimal 1 varian
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
                        // Cari nama size untuk pesan error yg lebih jelas
                        let sizeName = sizes.find(sz => sz.id === s.id)?.name || 'Unknown';
                        if (s.stok === "" || s.stok < 0) {
                            errors.push(`Stok untuk ukuran ${sizeName} pada varian "${v.warna}" wajib diisi.`);
                        }
                    });
                }
            });

        } else {
            // Tidak pakai varian warna -> cek defaultSizes
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

        // Kalau ada error -> tampilkan dan block submit
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




<div class="bg-white rounded-lg shadow-lg mb-6 overflow-hidden">
    <div class="card-body p-6">
        <form action="<?php echo e(route('admin.products')); ?>" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div class="md:col-span-3">
                    <label for="search_nama" class="block text-sm font-medium text-gray-700 mb-1">
                        Cari Nama Produk
                    </label>
                    <input type="text" name="search_nama" id="search_nama"
                           class="w-full border border-gray-300 rounded px-3 py-2 text-sm"
                           placeholder="Ketik nama produk..."
                           value="<?php echo e($filters['search_nama'] ?? ''); ?>">
                </div>

                <div class="flex space-x-2">
                    <button type="submit"
                        class="w-full bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700 text-sm font-medium flex items-center justify-center">
                        Cari
                    </button>
                    <a href="<?php echo e(route('admin.products')); ?>"
                       class="w-full bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 text-sm font-medium flex items-center justify-center">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>




<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="card-header bg-gray-50 border-b border-gray-200 px-6 py-4">
        <h2 class="text-lg font-semibold text-gray-700">Daftar Produk</h2>
    </div>
    <div class="card-body overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga Normal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga Reseller</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gambar (Sampul)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo e($index + 1); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900"><?php echo e($product->nama); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e($product->category->name ?? 'N/A'); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            Rp<?php echo e(number_format($product->harga_normal, 0, ',', '.')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            Rp<?php echo e(number_format($product->harga_reseller, 0, ',', '.')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e($product->total_stok); ?>

                        </td>
                        <td class="px-6 py-4">
                            <?php if($product->gambar): ?>
                                <img src="<?php echo e(asset('storage/' . $product->gambar)); ?>" width="60" class="rounded-md">
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="<?php echo e(route('admin.products.edit', $product->id)); ?>"
                               class="text-indigo-600 hover:text-indigo-900 font-medium">
                                Edit
                            </a>

                            <form action="<?php echo e(route('admin.products.delete', $product->id)); ?>"
                                  method="POST" class="inline"
                                  onsubmit="return confirm('Hapus produk ini?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button class="text-red-600 hover:text-red-900 font-medium ml-3">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="text-center py-10 text-gray-500">
                            <?php if(request()->has('search_nama')): ?>
                                Tidak ada produk yang cocok dengan pencarian Anda.
                            <?php else: ?>
                                Belum ada produk.
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/admin/products.blade.php ENDPATH**/ ?>