<?php $__env->startSection('title', 'Checkout - Dishine'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8 max-w-4xl"> <!-- Ubah max-w-6xl jadi max-w-4xl -->
    <!-- Header -->
    <h1 class="text-3xl font-bold mb-8 text-[#3c2f2f]">Checkout</h1>

    <form id="checkoutForm" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <!-- Hapus flex row, langsung pakai column -->
        <div class="flex flex-col gap-8">

        <!-- Customer & Address -->
        <div class="bg-white rounded-lg border p-6">
            <div class="flex justify-between items-start mb-4">
                <h3 class="font-bold text-lg text-gray-900">Rumah</h3>
                <button type="button" id="toggleAlamatBtn" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Ubah
                </button>
            </div>
            
            <!-- Tampilan Alamat Saat Ini -->
            <div id="alamatDisplay" class="space-y-2">
                <p class="font-semibold text-gray-900">
                    <?php echo e(Auth::user()->nama); ?> (<?php echo e(Auth::user()->no_hp); ?>)
                </p>
                <p class="text-gray-600 text-sm leading-relaxed">
                    <?php echo e(Auth::user()->alamat); ?>

                </p>
            </div>

            <!-- Form Ubah Alamat (Awalnya Disembunyikan) -->
            <div id="alamatForm" class="hidden mt-4 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">Provinsi</label>
                        <select name="provinsi" id="provinsi" class="w-full border border-gray-300 rounded px-3 py-2">
                            <option value="">Pilih Provinsi</option>
                            <!-- Options akan diisi via JavaScript -->
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">Kota/Kabupaten</label>
                        <select name="kota" id="kota" class="w-full border border-gray-300 rounded px-3 py-2" disabled>
                            <option value="">Pilih Kota</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">Kecamatan</label>
                        <select name="kecamatan" id="kecamatan" class="w-full border border-gray-300 rounded px-3 py-2" disabled>
                            <option value="">Pilih Kecamatan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">Kode Pos</label>
                        <input type="text" name="kode_pos" id="kode_pos" class="w-full border border-gray-300 rounded px-3 py-2" readonly>
                    </div>
                </div>

                <div>
                    <label class="block font-semibold mb-2 text-gray-700">Alamat Lengkap</label>
                    <textarea name="alamat_lengkap" id="alamat_lengkap" 
                            class="w-full border border-gray-300 rounded px-3 py-2" 
                            rows="3" 
                            placeholder="Nama jalan, nomor rumah, RT/RW, patokan..."></textarea>
                </div>

                <div class="flex gap-2">
                    <button type="button" id="simpanAlamatBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                        Simpan Alamat
                    </button>
                    <button type="button" id="batalAlamatBtn" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>

            <!-- Products Ordered -->
            <?php echo $__env->make('checkout.partials.products', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <!-- Shipping Method -->
            <div class="bg-white rounded-lg border p-6">
                <h3 class="font-bold text-lg mb-4 text-gray-900">Ekspedisi:</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">Kurir</label>
                        <select name="kurir" id="kurir" 
                                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" 
                                required>
                            <option value="">-- Pilih Kurir --</option>
                            <option value="jne">JNE</option>
                            <option value="pos">POS Indonesia</option>
                            <option value="tiki">TIKI</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">Layanan Pengiriman</label>
                        <select id="layanan_ongkir" 
                                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" 
                                required>
                            <option value="">-- Pilih Layanan --</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="bg-white rounded-lg border p-6">
                <h3 class="font-bold text-lg mb-4 text-gray-900">Metode Pembayaran:</h3>
                
                <div class="space-y-1 mb-6">
                    <p class="text-gray-700"><span class="font-semibold">BCA :</span> 872947210</p>
                    <p class="text-gray-700"><span class="font-semibold">Mandiri :</span> 692723813</p>
                    <p class="text-gray-700"><span class="font-semibold">Gopay :</span> 0898765432</p>
                    <p class="text-gray-700"><span class="font-semibold">Dana :</span> 0898765432</p>
                </div>

                <div>
                    <label class="block font-semibold mb-2 text-gray-700">Upload Bukti Pembayaran</label>
                    <input type="file" name="bukti_transfer" id="bukti_transfer" 
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                           accept="image/*" required>
                    <div id="preview-container" class="mt-3 text-center hidden">
                        <img id="preview-image" class="max-w-xs mx-auto rounded shadow">
                    </div>
                </div>
            </div>

            <!-- Ringkasan Pembayaran - Sekarang di bawah -->
            <div class="bg-white rounded-lg border p-6">
                <h3 class="font-bold text-lg mb-4 text-gray-900">Rincian Pembayaran</h3>
                
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-700">Subtotal Pesanan</span>
                        <span class="text-gray-700">Rp <?php echo e(number_format($total, 0, ',', '.')); ?></span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-700">Subtotal Pengiriman</span>
                        <span id="ongkir_label" class="text-gray-700">Rp 0</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-700">Biaya Layanan</span>
                        <span class="text-gray-700">Rp 2.000</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-700">Diskon Reseller</span>
                        <span class="text-gray-700">Rp 0</span>
                    </div>
                </div>
                
                <hr class="my-4 border-gray-300">
                
                <div class="flex justify-between items-center font-bold text-lg">
                    <span class="text-gray-900">Total</span>
                    <span id="total_label" class="text-gray-900">Rp <?php echo e(number_format($total + 2000, 0, ',', '.')); ?></span>
                </div>

                <!-- Hidden inputs untuk data ongkir -->
                <input type="hidden" id="ongkir_value" name="ongkir_value" value="0">
                <input type="hidden" id="layanan_selected_name" name="layanan_selected_name" value="">
                <input type="hidden" name="destination" value="501">
                
                <button type="submit" id="submitCheckout" 
                        class="w-full bg-gray-900 hover:bg-gray-800 text-white font-semibold py-3 px-4 rounded mt-6 transition duration-200">
                    Pesan
                </button>
            </div>
        </div>
    </form> 
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const subtotal = <?php echo e($total); ?>;
    const biayaLayanan = 2000;
    let ongkir = 0;

    const kurirSelect = document.getElementById('kurir');
    const layananSelect = document.getElementById('layanan_ongkir');
    const form = document.getElementById('checkoutForm');

    // Update total function
    function updateTotal(biayaOngkir) {
        ongkir = parseInt(biayaOngkir);
        
        document.getElementById('ongkir_label').textContent = `Rp ${ongkir.toLocaleString('id-ID')}`;
        document.getElementById('ongkir_value').value = ongkir;
        
        let total = subtotal + ongkir + biayaLayanan;
        document.getElementById('total_label').textContent = `Rp ${total.toLocaleString('id-ID')}`;
    }

    // Preview gambar bukti transfer
    document.getElementById('bukti_transfer').addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;
        
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-container').classList.remove('hidden');
            document.getElementById('preview-image').src = e.target.result;
        };
        reader.readAsDataURL(file);
    });

    // ================== TOGGLE FORM ALAMAT ==================
    document.getElementById('toggleAlamatBtn').addEventListener('click', function() {
        const display = document.getElementById('alamatDisplay');
        const formAlamat = document.getElementById('alamatForm');
        
        display.classList.toggle('hidden');
        formAlamat.classList.toggle('hidden');
        
        // Load provinsi jika form dibuka
        if (!formAlamat.classList.contains('hidden')) {
            loadProvinces();
        }
    });

    // Batal ubah alamat
    document.getElementById('batalAlamatBtn').addEventListener('click', function() {
        document.getElementById('alamatDisplay').classList.remove('hidden');
        document.getElementById('alamatForm').classnel.add('hidden');
    });

    // ================== LOAD DATA WILAYAH ==================
    async function loadProvinces() {
        try {
            const response = await fetch('/api/ongkir/provinces'); // ✅
            const provinces = await response.json();
            
            const provinsiSelect = document.getElementById('provinsi');
            provinsiSelect.innerHTML = '<option value="">Pilih Provinsi</option>';
            
            provinces.forEach(province => {
                const option = document.createElement('option');
                option.value = province.province_id;
                option.textContent = province.province;
                provinsiSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error loading provinces:', error);
        }
    }

    // Load kota ketika provinsi dipilih
    document.getElementById('provinsi').addEventListener('change', async function() {
        const provinceId = this.value;
        const kotaSelect = document.getElementById('kota');
        
        if (!provinceId) {
            kotaSelect.disabled = true;
            kotaSelect.innerHTML = '<option value="">Pilih Kota</option>';
            return;
        }

        try {
            const response = await fetch(`/api/ongkir/cities/${provinceId}`); // ✅
            const cities = await response.json();
            
            kotaSelect.innerHTML = '<option value="">Pilih Kota</option>';
            cities.forEach(city => {
                const option = document.createElement('option');
                option.value = city.city_id;
                option.textContent = `${city.type} ${city.city_name}`;
                kotaSelect.appendChild(option);
            });
            
            kotaSelect.disabled = false;
        } catch (error) {
            console.error('Error loading cities:', error);
        }
    });

    // Load kecamatan ketika kota dipilih
    document.getElementById('kota').addEventListener('change', async function() {
        const cityId = this.value;
        const kecamatanSelect = document.getElementById('kecamatan');
        
        if (!cityId) {
            kecamatanSelect.disabled = true;
            kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
            return;
        }

        try {
            const response = await fetch(`/api/ongkir/sub-districts/${cityId}`); // ✅
            const subdistricts = await response.json();
            
            kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
            subdistricts.forEach(subdistrict => {
                const option = document.createElement('option');
                option.value = subdistrict.subdistrict_id;
                option.textContent = subdistrict.subdistrict_name;
                option.setAttribute('data-postal', subdistrict.postal_code);
                kecamatanSelect.appendChild(option);
            });
            
            kecamatanSelect.disabled = false;
        } catch (error) {
            console.error('Error loading subdistricts:', error);
        }
    });

    // Auto-fill kode pos ketika kecamatan dipilih
    document.getElementById('kecamatan').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const kodePos = selectedOption.getAttribute('data-postal');
        document.getElementById('kode_pos').value = kodePos || '';
    });

    // Simpan alamat
    document.getElementById('simpanAlamatBtn').addEventListener('click', async function() {
        const provinsiSelect = document.getElementById('provinsi');
        const kotaSelect = document.getElementById('kota');
        const kecamatanSelect = document.getElementById('kecamatan');
        const alamatLengkap = document.getElementById('alamat_lengkap').value;

        const provinsi = provinsiSelect.options[provinsiSelect.selectedIndex]?.textContent;
        const kota = kotaSelect.options[kotaSelect.selectedIndex]?.textContent;
        const kecamatan = kecamatanSelect.options[kecamatanSelect.selectedIndex]?.textContent;
        const kodePos = document.getElementById('kode_pos').value;

        // Validasi
        if (!provinsi || !kota || !kecamatan || !alamatLengkap) {
            alert('Harap lengkapi semua data alamat');
            return;
        }

        // Format alamat lengkap
        const alamatFinal = `${alamatLengkap}, ${kecamatan}, ${kota}, ${provinsi} ${kodePos}`;

        // Simpan ke database via AJAX
        try {
            const response = await fetch('/api/update-alamat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({
                    alamat: alamatFinal
                })
            });

            const result = await response.json();

            if (response.ok) {
                // Update tampilan
                document.getElementById('alamatDisplay').querySelector('p:last-child').textContent = alamatFinal;
                document.getElementById('alamatDisplay').classList.remove('hidden');
                document.getElementById('alamatForm').classList.add('hidden');
                
                alert('Alamat berhasil diperbarui');
            } else {
                alert('Gagal menyimpan alamat');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        }
    });

    // ================== CEK ONGKIR ==================
    kurirSelect.addEventListener('change', async function() {
        const kurir = this.value;

        if (!kurir) {
            layananSelect.innerHTML = '<option value="">-- Pilih Layanan --</option>';
            layananSelect.disabled = true;
            return;
        }

        layananSelect.disabled = true;
        layananSelect.innerHTML = '<option value="">Loading...</option>';

        try {
            const response = await fetch('/api/ongkir/cost', { // ✅
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({
                    destination: '501', // Default Bogor
                    kurir: kurir,
                    weight: 1000 // Berat default 1kg
                })
            });

            const data = await response.json();

            if (data.success) {
                layananSelect.innerHTML = '<option value="">-- Pilih Layanan --</option>';
                
                data.data.forEach(layanan => {
                    const option = document.createElement('option');
                    option.value = `${layanan.cost}|${layanan.service}`;
                    option.textContent = `${layanan.service} - Rp ${layanan.cost.toLocaleString('id-ID')}`;
                    layananSelect.appendChild(option);
                });

                layananSelect.disabled = false;
            } else {
                throw new Error(data.message || 'Gagal mengambil data ongkir');
            }
        } catch (error) {
            console.error('Error:', error);
            layananSelect.innerHTML = '<option value="">Gagal memuat layanan</option>';
            // Fallback ke harga default
            layananSelect.innerHTML = `
                <option value="">-- Pilih Layanan --</option>
                <option value="12000|REG">REG - Rp 12.000</option>
                <option value="15000|EXPRESS">EXPRESS - Rp 15.000</option>
            `;
            layananSelect.disabled = false;
        }
    });

    // Saat layanan ongkir dipilih
    layananSelect.addEventListener('change', function() {
        if (this.value) {
            const [cost, service] = this.value.split('|');
            updateTotal(parseInt(cost));
            document.getElementById('layanan_selected_name').value = service;
        } else {
            updateTotal(0);
        }
    });

    // ================== SUBMIT CHECKOUT ==================
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const buktiTransfer = document.getElementById('bukti_transfer').files[0];
        const layananSelected = document.getElementById('layanan_selected_name').value;
        const ongkirValue = document.getElementById('ongkir_value').value;
        const kurir = document.getElementById('kurir').value;

        // Validasi
        if (!layananSelected || ongkirValue === '0' || !kurir) {
            alert('Silakan pilih layanan pengiriman.');
            return;
        }

        if (!buktiTransfer) {
            alert('Bukti transfer belum diupload.');
            return;
        }

        // Prepare form data
        const formData = new FormData(this);

        // Show loading
        const submitBtn = document.getElementById('submitCheckout');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Memproses...';

        try {
            const response = await fetch('<?php echo e(route("checkout.store")); ?>', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (response.ok) {
                alert('Pesanan berhasil dibuat!');
                window.location.href = '<?php echo e(route("orders.view")); ?>';
            } else {
                alert(result.message || 'Terjadi kesalahan. Silakan coba lagi.');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan. Silakan coba lagi.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Pesan';
        }
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/checkout/index.blade.php ENDPATH**/ ?>