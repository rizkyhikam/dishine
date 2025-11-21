<?php $__env->startSection('title', 'Checkout - Dishine'); ?>

<?php $__env->startSection('content'); ?>


<script>
    window.checkoutData = {
        subtotal: <?php echo e($total); ?>,
        totalWeight: <?php echo e($totalWeight); ?>,
        adminFee: <?php echo e($adminFee); ?>,
        userAddress: "<?php echo e(Auth::user()->alamat ?? ''); ?>" 
    };
</script>

<div class="container mx-auto px-4 py-8 max-w-4xl">
    <h1 class="text-3xl font-bold mb-8 text-[#3c2f2f]">Checkout</h1>

    <?php if($cartItems->isEmpty()): ?>
        <div class="text-center py-10 bg-white rounded border">
            <p class="text-gray-500">Keranjang belanja kosong.</p>
            <a href="<?php echo e(route('katalog')); ?>" class="text-blue-600 hover:underline">Belanja Dulu</a>
        </div>
    <?php else: ?>

    <form id="checkoutForm" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="flex flex-col gap-8">

            <div class="bg-white rounded-lg border p-6 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="font-bold text-lg text-gray-900">Alamat Pengiriman</h3>
                    <button type="button" id="toggleAlamatBtn" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        <?php echo e(Auth::user()->alamat ? 'Ubah Alamat' : 'Isi Alamat Baru'); ?>

                    </button>
                </div>
                
                <div id="alamatDisplay" class="<?php echo e(Auth::user()->alamat ? '' : 'hidden'); ?> space-y-2">
                    <p class="font-semibold text-gray-900">
                        <?php echo e(Auth::user()->nama); ?> (<?php echo e(Auth::user()->no_hp); ?>)
                    </p>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        <?php echo e(Auth::user()->alamat ?? 'Belum ada alamat tersimpan.'); ?>

                    </p>
                </div>

                <div id="alamatForm" class="<?php echo e(Auth::user()->alamat ? 'hidden' : ''); ?> mt-4 space-y-4 border-t pt-4">
                    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 text-sm p-3 rounded mb-3">
                        <i class="fas fa-info-circle"></i> Silakan lengkapi alamat pengiriman Anda.
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold mb-2 text-gray-700 text-sm">Provinsi</label>
                            <select name="province_id" id="province_id" class="w-full border border-gray-300 rounded px-3 py-2 text-sm bg-white">
                                <option value="">-- Pilih Provinsi --</option>
                                <?php $__currentLoopData = $provinces; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        // Antisipasi perbedaan key (province_id vs id)
                                        $pId = $prov['province_id'] ?? $prov['id'] ?? null;
                                        $pName = $prov['province'] ?? $prov['name'] ?? '';
                                    ?>
                                    <?php if($pId): ?>
                                        <option value="<?php echo e($pId); ?>" data-name="<?php echo e($pName); ?>">
                                            <?php echo e($pName); ?>

                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <input type="hidden" name="provinsi_name" id="provinsi_name">
                        </div>
                        <div>
                            <label class="block font-semibold mb-2 text-gray-700 text-sm">Kota/Kabupaten</label>
                            <select name="city_id" id="city_id" class="w-full border border-gray-300 rounded px-3 py-2 text-sm bg-gray-50" disabled>
                                <option value="">Pilih Provinsi Dulu</option>
                            </select>
                            <input type="hidden" name="kota_name" id="kota_name">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700 text-sm">Detail Jalan (Kecamatan, Kelurahan, Jalan, No Rumah)</label>
                        <textarea name="detail_alamat" id="detail_alamat" class="w-full border border-gray-300 rounded px-3 py-2 text-sm" rows="3" placeholder="Contoh: Jl. Mawar No. 5A, Kec. Cibinong"></textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border p-6 shadow-sm">
                <h3 class="font-bold text-lg mb-4 text-gray-900">Produk Dipesan</h3>
                <div class="space-y-4">
                    <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($item->product): ?>
                            <?php
                                $price = $isReseller ? $item->product->harga_reseller : $item->product->harga_normal;
                                $pName = $item->product->nama ?? $item->product->nama_produk ?? 'Produk';
                                $img = $item->product->gambar ?? null;
                            ?>
                            <div class="flex items-center justify-between border-b pb-4 last:border-0 last:pb-0">
                                <div class="flex items-center space-x-4">
                                    <?php if($img): ?>
                                        <img src="<?php echo e(asset('storage/' . $img)); ?>" class="w-16 h-16 object-cover rounded border" alt="<?php echo e($pName); ?>">
                                    <?php else: ?>
                                        <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center text-xs text-gray-500">No Img</div>
                                    <?php endif; ?>
                                    <div>
                                        <h4 class="font-semibold text-gray-800"><?php echo e($pName); ?></h4>
                                        <p class="text-sm text-gray-500">
                                            <?php echo e($item->quantity); ?> x Rp <?php echo e(number_format($price, 0, ',', '.')); ?>

                                        </p>
                                    </div>
                                </div>
                                <div class="font-semibold text-gray-900">
                                    Rp <?php echo e(number_format($price * $item->quantity, 0, ',', '.')); ?>

                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="text-right text-sm text-gray-500 mt-4 border-t pt-2">
                    Total Berat: <strong><?php echo e(number_format($totalWeight)); ?> gram</strong>
                </div>
            </div>

            <div class="bg-white rounded-lg border p-6 shadow-sm">
                <h3 class="font-bold text-lg mb-4 text-gray-900">Pengiriman</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">Pilih Kurir</label>
                        <select name="kurir" id="kurir" class="w-full border border-gray-300 rounded px-3 py-2 bg-white" required>
                            <option value="">-- Pilih Kurir --</option>
                            <option value="jne">JNE</option>
                            <option value="pos">POS Indonesia</option>
                            <option value="tiki">TIKI</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">Layanan</label>
                        <select id="layanan_ongkir" class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-50" disabled required>
                            <option value="">-- Pilih Kurir Dulu --</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border p-6 shadow-sm">
                <h3 class="font-bold text-lg mb-4 text-gray-900">Pembayaran</h3>
                <div class="mb-4 p-4 bg-gray-50 rounded border">
                    <p class="text-gray-700 text-sm mb-1"><span class="font-bold">BCA:</span> 872947210 (Dishine)</p>
                    <p class="text-gray-700 text-sm"><span class="font-bold">Mandiri:</span> 692723813 (Dishine)</p>
                </div>
                <div>
                    <label class="block font-semibold mb-2 text-gray-700">Upload Bukti Transfer</label>
                    <input type="file" name="bukti_transfer" id="bukti_transfer" class="w-full text-sm border rounded p-2" accept="image/*" required>
                    <div id="preview-container" class="mt-3 hidden">
                        <img id="preview-image" class="h-32 object-contain border rounded">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border p-6 shadow-sm">
                <h3 class="font-bold text-lg mb-4 text-gray-900">Ringkasan</h3>
                <div class="space-y-2 text-sm mb-4">
                    <div class="flex justify-between">
                        <span>Subtotal Produk</span>
                        <span class="font-medium">Rp <?php echo e(number_format($total, 0, ',', '.')); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Ongkos Kirim</span>
                        <span id="ongkir_display" class="font-medium text-blue-600">Rp 0</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Biaya Admin</span>
                        <span>Rp <?php echo e(number_format($adminFee, 0, ',', '.')); ?></span>
                    </div>
                </div>
                <div class="flex justify-between items-center border-t pt-4 mb-6">
                    <span class="font-bold text-lg">Total Bayar</span>
                    <span id="total_display" class="font-bold text-xl">Rp <?php echo e(number_format($total + $adminFee, 0, ',', '.')); ?></span>
                </div>

                <input type="hidden" id="ongkir_value" name="ongkir_value" value="0">
                <input type="hidden" id="layanan_name" name="layanan_selected_name" value="">
                <input type="hidden" id="destination_id" name="destination" value="<?php echo e(Auth::user()->city_id ?? ''); ?>">
                
                <button type="submit" id="submitBtn" class="w-full bg-[#AE8B56] hover:bg-[#8f7246] text-white font-bold py-3 px-4 rounded">
                    Buat Pesanan
                </button>
            </div>
        </div>
    </form>
    <?php endif; ?>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Init Data
    if (typeof window.checkoutData === 'undefined') return;
    const { subtotal, totalWeight, adminFee, userAddress } = window.checkoutData;
    
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

    // --- 1. Logic Toggle Alamat ---
    if(toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            const isHidden = formAlamat.classList.contains('hidden');
            if(isHidden) {
                formAlamat.classList.remove('hidden');
                displayAlamat.classList.add('hidden');
                this.innerText = "Batal Ubah";
            } else {
                if(userAddress) {
                    formAlamat.classList.add('hidden');
                    displayAlamat.classList.remove('hidden');
                    this.innerText = "Ubah Alamat";
                } else {
                    alert("Anda belum memiliki alamat, silakan isi form.");
                }
            }
        });
    }

    // --- 2. Fetch Cities (AJAX) ---
    if (provinceSelect) {
        provinceSelect.addEventListener('change', async function() {
            const id = this.value;
            
            // Simpan Nama Provinsi ke hidden input
            const selectedOption = this.options[this.selectedIndex];
            if(selectedOption && provinsiNameInput) {
                provinsiNameInput.value = selectedOption.getAttribute('data-name');
            }

            citySelect.innerHTML = '<option value="">Loading...</option>';
            citySelect.disabled = true;
            citySelect.style.backgroundColor = "#f9fafb"; 

            if(!id) return;

            try {
                // URL Route: /shipping/city/{id}
                const res = await fetch(`/shipping/city/${id}`);
                const json = await res.json();
                
                if(json.success && json.data) {
                    citySelect.innerHTML = '<option value="">Pilih Kota/Kab</option>';
                    citySelect.disabled = false;
                    citySelect.style.backgroundColor = "#ffffff";
                    
                    json.data.forEach(item => {
                        // RajaOngkir fields: city_id, type (Kab/Kota), city_name
                        const cId = item.city_id || item.id;
                        const cName = item.city_name || item.name;
                        const cType = item.type || '';
                        
                        citySelect.innerHTML += `<option value="${cId}" data-name="${cType} ${cName}">
                            ${cType} ${cName}
                        </option>`;
                    });
                } else {
                    alert("Gagal memuat kota.");
                }
            } catch (err) { 
                console.error(err);
                citySelect.innerHTML = '<option value="">Error</option>';
            }
        });
    }

    // --- 3. Update Destination & Reset Ongkir ---
    if (citySelect) {
        citySelect.addEventListener('change', function() {
            const cityId = this.value;
            destinationInput.value = cityId; // Update ID Kota Tujuan untuk Ongkir

            // Simpan Nama Kota ke hidden input
            const selectedOption = this.options[this.selectedIndex];
            if(selectedOption && kotaNameInput) {
                kotaNameInput.value = selectedOption.getAttribute('data-name');
            }

            // Reset Pilihan Ongkir
            kurirSelect.value = "";
            layananSelect.innerHTML = '<option value="">-- Pilih Kurir Dulu --</option>';
            layananSelect.disabled = true;
            updateTotal(0);
        });
    }

    // --- 4. Fetch Ongkir (AJAX POST) ---
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
                // URL Route: /shipping/cost
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
                
                // Logika Parsing Data Baru (Sesuai Controller & Service baru)
                // Structure: { success: true, data: [ { code: 'JNE', costs: [...] } ] }
                if(json.success && json.data) {
                    layananSelect.innerHTML = '<option value="">-- Pilih Layanan --</option>';
                    
                    // Ambil array costs dari index pertama data
                    let costs = [];
                    if(json.data[0] && json.data[0].costs) {
                        costs = json.data[0].costs;
                    }

                    if(costs.length > 0) {
                        costs.forEach(item => {
                            // Data item sudah rapi: { service, description, cost, etd }
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
                    console.error("Ongkir Error:", json);
                }
            } catch (err) {
                console.error(err);
                layananSelect.innerHTML = '<option>Error Sistem</option>';
            }
        });
    }

    // --- 5. Update Total Bayar ---
    if (layananSelect) {
        layananSelect.addEventListener('change', function() {
            if(this.value) {
                const [harga, nama] = this.value.split('|');
                updateTotal(parseInt(harga));
                // Simpan nama layanan ke hidden input
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

    // --- 6. Submit Form ---
    const checkoutForm = document.getElementById('checkoutForm');
    if(checkoutForm) {
        checkoutForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Validasi Ongkir Wajib
            if(document.getElementById('ongkir_value').value == 0) {
                alert("Silakan pilih layanan pengiriman terlebih dahulu.");
                return;
            }

            // Validasi Detail Alamat (Jika sedang edit/baru)
            if(!formAlamat.classList.contains('hidden')) {
                const detail = document.getElementById('detail_alamat').value;
                if(!detail) {
                    alert("Mohon isi detail jalan alamat pengiriman.");
                    return;
                }
            }
            
            const btn = document.getElementById('submitBtn');
            btn.textContent = "Memproses...";
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
                    alert("Pesanan Berhasil Dibuat! Mohon tunggu verifikasi admin.");
                    window.location.href = '/orders'; // Redirect ke halaman list order/history
                } else {
                    alert(json.message || "Gagal memproses pesanan.");
                }
            } catch (err) {
                alert("Terjadi kesalahan jaringan.");
                console.error(err);
            } finally {
                btn.textContent = "Buat Pesanan";
                btn.disabled = false;
            }
        });
    }
    
    // --- 7. Preview Image Bukti Transfer ---
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
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/checkout/index.blade.php ENDPATH**/ ?>