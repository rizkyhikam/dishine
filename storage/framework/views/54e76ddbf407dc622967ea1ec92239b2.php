<?php $__env->startSection('title', 'Checkout - Dishine'); ?>

<?php $__env->startSection('content'); ?>


<script>
    window.checkoutData = {
        subtotal: <?php echo e($total); ?>,
        totalWeight: <?php echo e($totalWeight); ?>,
        adminFee: <?php echo e($adminFee); ?>,
        // Data User untuk Logika Otomatis
        userAddress: `<?php echo e(Auth::user()->alamat ?? ''); ?>`,
        userCityId: "<?php echo e(Auth::user()->city_id ?? ''); ?>",
        userProvinceId: "<?php echo e(Auth::user()->province_id ?? ''); ?>" 
    };
</script>

<div class="container mx-auto px-4 py-8 max-w-6xl">
    
    <div class="mb-8">
        <div class="flex items-center space-x-4">
            <div class="bg-gradient-to-r from-[#CC8650] to-[#D4BA98] p-4 rounded-2xl shadow-lg">
                <i data-lucide="shopping-cart" class="w-8 h-8 text-white"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold text-gray-800">Checkout</h1>
                <p class="text-gray-600 mt-1">Lengkapi informasi pengiriman dan pembayaran</p>
            </div>
        </div>
    </div>

    <?php if($cartItems->isEmpty()): ?>
        <div class="bg-white rounded-2xl shadow-md p-8 text-center">
            <div class="bg-[#F0E7DB] p-6 rounded-full inline-flex mb-4">
                <i data-lucide="shopping-cart" class="w-16 h-16 text-[#AE8B56]"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-700 mb-2">Keranjang Belanja Kosong</h3>
            <p class="text-gray-500 mb-4">Silakan tambahkan produk ke keranjang terlebih dahulu</p>
            <a href="<?php echo e(route('katalog')); ?>" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#CC8650] to-[#D4BA98] text-white rounded-xl font-semibold hover:shadow-lg transition-all">
                <i data-lucide="store" class="w-4 h-4 mr-2"></i>
                Mulai Belanja
            </a>
        </div>
    <?php else: ?>

    <form id="checkoutForm" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            
            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-[#CC8650] to-[#D4BA98] px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                                    <i data-lucide="map-pin" class="w-5 h-5 text-white"></i>
                                </div>
                                <h3 class="text-xl font-bold text-white">Alamat Pengiriman</h3>
                            </div>
                            <button type="button" id="toggleAlamatBtn" class="text-white bg-white bg-opacity-20 hover:bg-opacity-30 px-4 py-2 rounded-xl font-semibold text-sm transition-all">
                                <?php echo e(Auth::user()->alamat ? 'Ubah Alamat' : 'Isi Alamat Baru'); ?>

                            </button>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div id="alamatDisplay" class="<?php echo e(Auth::user()->alamat ? '' : 'hidden'); ?> space-y-3">
                            <div class="flex items-center space-x-3">
                                <div class="bg-[#CC8650] bg-opacity-10 p-2 rounded-lg">
                                    <i data-lucide="user" class="w-4 h-4 text-[#CC8650]"></i>
                                </div>
                                <p class="font-semibold text-gray-900">
                                    <?php echo e(Auth::user()->nama); ?> (<?php echo e(Auth::user()->no_hp); ?>)
                                </p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="bg-[#AE8B56] bg-opacity-10 p-2 rounded-lg mt-1">
                                    <i data-lucide="home" class="w-4 h-4 text-[#AE8B56]"></i>
                                </div>
                                <p class="text-gray-600 leading-relaxed">
                                    <?php echo e(Auth::user()->alamat ?? 'Belum ada alamat tersimpan.'); ?>

                                </p>
                            </div>
                        </div>

                        <div id="alamatForm" class="<?php echo e(Auth::user()->alamat ? 'hidden' : ''); ?> mt-4 space-y-4 border-t pt-4">
                            <div class="bg-gradient-to-r from-yellow-50 to-amber-50 border border-yellow-200 text-yellow-800 text-sm p-4 rounded-xl mb-3 flex items-center space-x-3">
                                <div class="bg-yellow-100 p-2 rounded-lg">
                                    <i data-lucide="info" class="w-4 h-4 text-yellow-600"></i>
                                </div>
                                <span>Silakan lengkapi/update alamat pengiriman Anda.</span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Provinsi</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i data-lucide="map" class="w-5 h-5 text-gray-400"></i>
                                        </div>
                                        <select name="province_id" id="province_id" class="w-full border-2 border-gray-200 rounded-xl pl-10 pr-4 py-3 bg-white focus:border-[#CC8650] focus:ring focus:ring-[#CC8650] focus:ring-opacity-20 transition-all">
                                            <option value="">-- Pilih Provinsi --</option>
                                            <?php $__currentLoopData = $provinces; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $pId = $prov['province_id'] ?? $prov['id'] ?? null;
                                                    $pName = $prov['province'] ?? $prov['name'] ?? '';
                                                    $isSelected = (Auth::user()->province_id == $pId) ? 'selected' : '';
                                                ?>
                                                <?php if($pId): ?>
                                                    <option value="<?php echo e($pId); ?>" data-name="<?php echo e($pName); ?>" <?php echo e($isSelected); ?>>
                                                        <?php echo e($pName); ?>

                                                    </option>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <input type="hidden" name="provinsi_name" id="provinsi_name">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Kota/Kabupaten</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i data-lucide="building" class="w-5 h-5 text-gray-400"></i>
                                        </div>
                                        <select name="city_id" id="city_id" class="w-full border-2 border-gray-200 rounded-xl pl-10 pr-4 py-3 bg-gray-50 focus:border-[#CC8650] focus:ring focus:ring-[#CC8650] focus:ring-opacity-20 transition-all" disabled>
                                            <option value="">Pilih Provinsi Dulu</option>
                                        </select>
                                    </div>
                                    <input type="hidden" name="kota_name" id="kota_name">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Detail Jalan</label>
                                <div class="relative">
                                    <div class="absolute top-3 left-3 pointer-events-none">
                                        <i data-lucide="navigation" class="w-5 h-5 text-gray-400"></i>
                                    </div>
                                    <textarea name="detail_alamat" id="detail_alamat" class="w-full border-2 border-gray-200 rounded-xl pl-10 pr-4 py-3 focus:border-[#CC8650] focus:ring focus:ring-[#CC8650] focus:ring-opacity-20 transition-all" rows="3" placeholder="Contoh: Jl. Mawar No. 5A, Kec. Cibinong"><?php echo e(Auth::user()->alamat); ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-[#CC8650] to-[#D4BA98] px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                                <i data-lucide="package" class="w-5 h-5 text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white">Produk Dipesan</h3>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-4">
                            <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($item->product): ?>
                                    <?php
                                        $price = $isReseller ? $item->product->harga_reseller : $item->product->harga_normal;
                                        $pName = $item->product->nama ?? $item->product->nama_produk ?? 'Produk';
                                        $img = $item->product->gambar ?? null;
                                    ?>
                                    <div class="flex items-center justify-between border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                                        <div class="flex items-center space-x-4">
                                            <?php if($img): ?>
                                                <img src="<?php echo e(asset('storage/' . $img)); ?>" class="w-16 h-16 object-cover rounded-xl border-2 border-[#D4BA98]" alt="<?php echo e($pName); ?>">
                                            <?php else: ?>
                                                <div class="w-16 h-16 bg-gray-200 rounded-xl flex items-center justify-center">
                                                    <i data-lucide="image-off" class="w-6 h-6 text-gray-400"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <h4 class="font-semibold text-gray-800"><?php echo e($pName); ?></h4>
                                                
                                                
                                                <?php
                                                    $detailInfo = [];
                                                    if ($item->variantSize) {
                                                        if ($item->variantSize->productVariant && $item->variantSize->productVariant->warna) {
                                                            $detailInfo[] = "Warna: " . $item->variantSize->productVariant->warna;
                                                        }
                                                        if ($item->variantSize->size && $item->variantSize->size->name) {
                                                            $detailInfo[] = "Size: " . $item->variantSize->size->name;
                                                        }
                                                    }
                                                ?>
                                                <?php if(count($detailInfo) > 0): ?>
                                                    <p class="text-xs text-gray-500 mb-1">
                                                        <?php echo e(implode(' | ', $detailInfo)); ?>

                                                    </p>
                                                <?php endif; ?>

                                                <p class="text-sm text-gray-500">
                                                    <?php echo e($item->quantity); ?> x Rp <?php echo e(number_format($price, 0, ',', '.')); ?>

                                                </p>
                                            </div>
                                        </div>
                                        <div class="font-semibold text-gray-900 text-lg">
                                            Rp <?php echo e(number_format($price * $item->quantity, 0, ',', '.')); ?>

                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div class="text-right text-sm text-gray-500 mt-4 border-t border-gray-100 pt-4">
                            <div class="flex items-center justify-end space-x-2">
                                <i data-lucide="weight" class="w-4 h-4 text-[#AE8B56]"></i>
                                <span>Total Berat: <strong><?php echo e(number_format($totalWeight)); ?> gram</strong></span>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-[#CC8650] to-[#D4BA98] px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                                <i data-lucide="truck" class="w-5 h-5 text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white">Pengiriman</h3>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Kurir</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i data-lucide="package" class="w-5 h-5 text-gray-400"></i>
                                    </div>
                                    <select name="kurir" id="kurir" class="w-full border-2 border-gray-200 rounded-xl pl-10 pr-4 py-3 bg-white focus:border-[#CC8650] focus:ring focus:ring-[#CC8650] focus:ring-opacity-20 transition-all" required>
                                        <option value="">-- Pilih Kurir --</option>
                                        <option value="jne">JNE</option>
                                        <option value="pos">POS Indonesia</option>
                                        <option value="tiki">TIKI</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Layanan</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i data-lucide="settings" class="w-5 h-5 text-gray-400"></i>
                                    </div>
                                    <select id="layanan_ongkir" class="w-full border-2 border-gray-200 rounded-xl pl-10 pr-4 py-3 bg-gray-50 focus:border-[#CC8650] focus:ring focus:ring-[#CC8650] focus:ring-opacity-20 transition-all" disabled required>
                                        <option value="">-- Pilih Kurir Dulu --</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-[#CC8650] to-[#D4BA98] px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                                <i data-lucide="credit-card" class="w-5 h-5 text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white">Pembayaran</h3>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div class="bg-gradient-to-br from-[#F0E7DB] to-[#EBE6E6] p-4 rounded-xl border border-[#D4BA98]">
                                <div class="flex items-center space-x-3 mb-2">
                                    <div class="bg-[#CC8650] bg-opacity-10 p-2 rounded-lg">
                                        <i data-lucide="landmark" class="w-4 h-4 text-[#CC8650]"></i>
                                    </div>
                                    <span class="font-bold text-gray-800">BCA</span>
                                </div>
                                <p class="text-lg font-semibold text-gray-700">872947210</p>
                                <p class="text-sm text-gray-500">a.n. Dishine</p>
                            </div>
                            <div class="bg-gradient-to-br from-[#F0E7DB] to-[#EBE6E6] p-4 rounded-xl border border-[#D4BA98]">
                                <div class="flex items-center space-x-3 mb-2">
                                    <div class="bg-[#AE8B56] bg-opacity-10 p-2 rounded-lg">
                                        <i data-lucide="landmark" class="w-4 h-4 text-[#AE8B56]"></i>
                                    </div>
                                    <span class="font-bold text-gray-800">Mandiri</span>
                                </div>
                                <p class="text-lg font-semibold text-gray-700">692723813</p>
                                <p class="text-sm text-gray-500">a.n. Dishine</p>
                            </div>
                        </div>

                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-3">
                                Upload Bukti Transfer <span class="text-red-500">*</span>
                            </label>
                            <div class="border-2 border-dashed border-[#D4BA98] rounded-xl p-6 text-center hover:border-[#CC8650] transition-all bg-[#F0E7DB] bg-opacity-30 cursor-pointer"
                                onclick="document.getElementById('bukti_transfer').click()">
                                <input type="file" name="bukti_transfer" id="bukti_transfer" class="hidden" accept="image/*" required>
                                <i data-lucide="upload" class="w-12 h-12 text-[#AE8B56] mx-auto mb-3"></i>
                                <p class="text-gray-700 font-semibold">Klik untuk upload bukti transfer</p>
                                <p class="text-gray-500 text-sm mt-1">Format: JPG, PNG (Maks. 2MB)</p>
                            </div>
                            <div id="preview-container" class="text-center mt-4 hidden">
                                <img id="preview-image" class="rounded-xl border-2 border-[#D4BA98] max-w-xs mx-auto">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="space-y-6">
                
                <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-[#CC8650] to-[#D4BA98] px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                                <i data-lucide="receipt" class="w-5 h-5 text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white">Ringkasan Pembayaran</h3>
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Subtotal Produk</span>
                                <span class="font-semibold text-gray-800">Rp <?php echo e(number_format($total, 0, ',', '.')); ?></span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Ongkos Kirim</span>
                                <span id="ongkir_display" class="font-semibold text-[#CC8650]">Rp 0</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Biaya Admin</span>
                                <span class="font-semibold text-gray-800">Rp <?php echo e(number_format($adminFee, 0, ',', '.')); ?></span>
                            </div>
                        </div>

                        <hr class="border-gray-200">

                        <div class="flex justify-between items-center text-lg">
                            <span class="font-bold text-gray-800">Total Bayar</span>
                            <span id="total_display" class="font-bold text-[#CC8650] text-xl">Rp <?php echo e(number_format($total + $adminFee, 0, ',', '.')); ?></span>
                        </div>

                        
                        <input type="hidden" id="ongkir_value" name="ongkir_value" value="0">
                        <input type="hidden" id="layanan_name" name="layanan_selected_name" value="">
                        <input type="hidden" id="destination_id" name="destination" value="<?php echo e(Auth::user()->city_id ?? ''); ?>">
                        
                        
                        <button type="submit" id="submitBtn" class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white py-4 rounded-xl font-bold text-lg hover:shadow-lg transform hover:-translate-y-0.5 transition-all flex items-center justify-center space-x-2 mt-4">
                            <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                            <span>Buat Pesanan</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <?php endif; ?>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Init Data
    if (typeof window.checkoutData === 'undefined') return;
    const { subtotal, totalWeight, adminFee, userAddress, userCityId, userProvinceId } = window.checkoutData;
    
    // DOM Elements
    const provinceSelect = document.getElementById('province_id');
    const citySelect = document.getElementById('city_id');
    const destinationInput = document.getElementById('destination_id');
    const kurirSelect = document.getElementById('kurir');
    const layananSelect = document.getElementById('layanan_ongkir');
    
    const formAlamat = document.getElementById('alamatForm');
    const displayAlamat = document.getElementById('alamatDisplay');
    const toggleBtn = document.getElementById('toggleAlamatBtn');
    const provinsiNameInput = document.getElementById('provinsi_name');
    const kotaNameInput = document.getElementById('kota_name');

    // =================================================================
    // 1. LOGIKA INIT (Saat halaman pertama kali dibuka)
    // =================================================================
    
    if (userCityId && userAddress) {
        // Jika user sudah punya data, set hidden input
        if(destinationInput) destinationInput.value = userCityId;
        
        // Tampilkan alamat teks, sembunyikan form
        if(displayAlamat) displayAlamat.classList.remove('hidden');
        if(formAlamat) formAlamat.classList.add('hidden');
        
        // Ubah teks tombol toggle
        if(toggleBtn) toggleBtn.innerText = "Ubah Alamat";
    } else {
        // Jika user baru, form terbuka otomatis
        if(displayAlamat) displayAlamat.classList.add('hidden');
        if(formAlamat) formAlamat.classList.remove('hidden');
        if(toggleBtn) toggleBtn.innerText = "Batal";
    }

    // =================================================================
    // 2. FUNGSI HELPER: Load Cities
    // =================================================================
    async function loadCities(provId, preselectCityId = null) {
        if(!provId) return;

        // Update hidden nama provinsi
        const provOption = provinceSelect.querySelector(`option[value="${provId}"]`);
        if(provOption && provinsiNameInput) provinsiNameInput.value = provOption.getAttribute('data-name');

        // Reset Kota Dropdown
        citySelect.innerHTML = '<option value="">Loading...</option>';
        citySelect.disabled = true;
        citySelect.style.backgroundColor = "#f9fafb"; 

        try {
            const res = await fetch(`/shipping/city/${provId}`);
            const json = await res.json();
            
            if(json.success && json.data) {
                citySelect.innerHTML = '<option value="">Pilih Kota/Kab</option>';
                citySelect.disabled = false;
                citySelect.style.backgroundColor = "#ffffff";
                
                json.data.forEach(item => {
                    const cId = item.city_id || item.id;
                    const cName = item.city_name || item.name;
                    const cType = item.type || '';
                    
                    // Cek apakah ini kota user saat ini?
                    const isSelected = (preselectCityId && cId == preselectCityId) ? 'selected' : '';
                    
                    citySelect.innerHTML += `<option value="${cId}" data-name="${cType} ${cName}" ${isSelected}>
                        ${cType} ${cName}
                    </option>`;
                });

                // Jika ada preselect, update destination dan nama kota langsung
                if(preselectCityId) {
                    citySelect.dispatchEvent(new Event('change'));
                }

            } else {
                alert("Gagal memuat kota.");
            }
        } catch (err) { 
            console.error(err);
            citySelect.innerHTML = '<option value="">Error</option>';
        }
    }

    // =================================================================
    // 3. EVENT LISTENER
    // =================================================================

    // A. Toggle Alamat (Klik Ubah Alamat)
    if(toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            const isFormHidden = formAlamat.classList.contains('hidden');
            
            if(isFormHidden) {
                // -> MASUK MODE EDIT
                formAlamat.classList.remove('hidden');
                displayAlamat.classList.add('hidden');
                this.innerText = "Batal Ubah";

                // Auto-load kota jika provinsi sudah terpilih
                if(provinceSelect.value) {
                    loadCities(provinceSelect.value, userCityId);
                }

            } else {
                // -> BATAL EDIT (Kembali ke tampilan teks)
                if(userAddress) {
                    formAlamat.classList.add('hidden');
                    displayAlamat.classList.remove('hidden');
                    this.innerText = "Ubah Alamat";
                    
                    // Kembalikan destination ke data lama di DB
                    destinationInput.value = userCityId;
                    // Reset ongkir ke nol karena user batal ubah
                    kurirSelect.value = "";
                    layananSelect.innerHTML = '<option value="">-- Pilih Kurir Dulu --</option>';
                    updateTotal(0);
                } else {
                    alert("Anda belum memiliki alamat, silakan isi form.");
                }
            }
        });
    }

    // B. Ganti Provinsi
    if (provinceSelect) {
        provinceSelect.addEventListener('change', function() {
            loadCities(this.value);
        });
    }

    // C. Ganti Kota -> Update Destination
    if (citySelect) {
        citySelect.addEventListener('change', function() {
            const cityId = this.value;
            destinationInput.value = cityId; 

            const selectedOption = this.options[this.selectedIndex];
            if(selectedOption && kotaNameInput) {
                kotaNameInput.value = selectedOption.getAttribute('data-name');
            }

            // Reset Ongkir karena lokasi berubah
            kurirSelect.value = "";
            layananSelect.innerHTML = '<option value="">-- Pilih Kurir Dulu --</option>';
            layananSelect.disabled = true;
            updateTotal(0);
        });
    }

    // D. Ganti Kurir -> Cek Ongkir
    if (kurirSelect) {
        kurirSelect.addEventListener('change', async function() {
            const kurir = this.value;
            const dest = destinationInput.value;

            if(!dest) {
                alert("Mohon pilih Kota/Kabupaten tujuan terlebih dahulu.");
                this.value = "";
                return;
            }
            if(!kurir) return;

            layananSelect.innerHTML = '<option>Menghitung...</option>';
            layananSelect.disabled = true;

            try {
                const res = await fetch('/shipping/cost', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        destination: dest,
                        courier: kurir,
                        weight: totalWeight
                    })
                });
                
                const json = await res.json();
                
                if(json.success && json.data) {
                    layananSelect.innerHTML = '<option value="">-- Pilih Layanan --</option>';
                    
                    let costs = [];
                    if(json.data[0] && json.data[0].costs) {
                        costs = json.data[0].costs;
                    }

                    if(costs.length > 0) {
                        costs.forEach(item => {
                            const serviceName = item.service; 
                            const costVal = item.cost;
                            const etd = item.etd;
                            
                            layananSelect.innerHTML += `<option value="${costVal}|${serviceName}">
                                ${serviceName} (${etd} Hari) - Rp ${new Intl.NumberFormat('id-ID').format(costVal)}
                            </option>`;
                        });
                        layananSelect.disabled = false;
                    } else {
                        layananSelect.innerHTML = '<option>Tidak ada layanan tersedia</option>';
                    }
                } else {
                    layananSelect.innerHTML = '<option>Gagal Cek Ongkir</option>';
                }
            } catch (err) {
                console.error(err);
                layananSelect.innerHTML = '<option>Error Sistem</option>';
            }
        });
    }

    // E. Pilih Layanan -> Update Total
    if (layananSelect) {
        layananSelect.addEventListener('change', function() {
            if(this.value) {
                const [harga, nama] = this.value.split('|');
                updateTotal(parseInt(harga));
                document.getElementById('layanan_name').value = nama;
            } else {
                updateTotal(0);
            }
        });
    }

    function updateTotal(ongkir) {
        document.getElementById('ongkir_value').value = ongkir;
        document.getElementById('ongkir_display').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(ongkir);
        document.getElementById('total_display').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal + adminFee + ongkir);
    }

    // F. Submit Form
    const checkoutForm = document.getElementById('checkoutForm');
    if(checkoutForm) {
        checkoutForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if(document.getElementById('ongkir_value').value == 0) {
                alert("Silakan pilih layanan pengiriman terlebih dahulu.");
                return;
            }

            if(!formAlamat.classList.contains('hidden')) {
                const detail = document.getElementById('detail_alamat').value;
                if(!detail) {
                    alert("Mohon isi detail jalan alamat pengiriman.");
                    return;
                }
            }
            
            const btn = document.getElementById('submitBtn');
            btn.innerHTML = '<i data-lucide="loader" class="w-5 h-5 animate-spin"></i><span>Memproses...</span>';
            btn.disabled = true;
            
            const formData = new FormData(this);
            
            try {
                const res = await fetch('<?php echo e(route("checkout.store")); ?>', {
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
                    body: formData
                });
                const json = await res.json();
                
                if(res.ok) {
                    alert("Pesanan Berhasil Dibuat!");
                    window.location.href = '/orders'; 
                } else {
                    alert(json.message || "Gagal memproses pesanan.");
                }
            } catch (err) {
                alert("Terjadi kesalahan jaringan.");
                console.error(err);
            } finally {
                btn.innerHTML = '<i data-lucide="shopping-bag" class="w-5 h-5"></i><span>Buat Pesanan</span>';
                btn.disabled = false;
                lucide.createIcons();
            }
        });
    }
    
    // G. Preview Bukti Transfer
    const buktiInput = document.getElementById('bukti_transfer');
    if(buktiInput) {
        buktiInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('preview-image').src = e.target.result;
                    document.getElementById('preview-container').classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // Initialize Lucide icons
    lucide.createIcons();
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aulia\dataD\KULIAH\PJBL\dishine\resources\views/checkout/index.blade.php ENDPATH**/ ?>