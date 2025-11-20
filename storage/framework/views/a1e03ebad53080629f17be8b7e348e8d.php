<?php $__env->startSection('title', 'Checkout - Dishine'); ?>

<?php $__env->startSection('content'); ?>


<script>
    window.checkoutData = {
        subtotal: <?php echo e($total); ?>,
        totalWeight: <?php echo e($totalWeight); ?>,
        adminFee: 2000
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

            <!-- BAGIAN 1: ALAMAT -->
            <div class="bg-white rounded-lg border p-6 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="font-bold text-lg text-gray-900">Alamat Pengiriman</h3>
                    <button type="button" id="toggleAlamatBtn" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        <?php echo e(Auth::user()->alamat ? 'Ubah Alamat' : 'Isi Alamat Baru'); ?>

                    </button>
                </div>
                
                <!-- Display Alamat -->
                <div id="alamatDisplay" class="<?php echo e(Auth::user()->alamat ? '' : 'hidden'); ?> space-y-2">
                    <p class="font-semibold text-gray-900">
                        <?php echo e(Auth::user()->nama); ?> (<?php echo e(Auth::user()->no_hp); ?>)
                    </p>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        <?php echo e(Auth::user()->alamat ?? 'Belum ada alamat tersimpan.'); ?>

                    </p>
                </div>

                <!-- Form Alamat -->
                <div id="alamatForm" class="<?php echo e(Auth::user()->alamat ? 'hidden' : ''); ?> mt-4 space-y-4 border-t pt-4">
                    <!-- Provinsi & Kota -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold mb-2 text-gray-700 text-sm">Provinsi</label>
                            <select id="provinsi" class="w-full border border-gray-300 rounded px-3 py-2 text-sm bg-white">
                                <option value="">Loading...</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold mb-2 text-gray-700 text-sm">Kota/Kabupaten</label>
                            <select id="kota" class="w-full border border-gray-300 rounded px-3 py-2 text-sm bg-gray-50" disabled>
                                <option value="">Pilih Provinsi Dulu</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Manual Input -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold mb-2 text-gray-700 text-sm">Kecamatan (Manual)</label>
                            <input type="text" id="kecamatan" class="w-full border border-gray-300 rounded px-3 py-2 text-sm" placeholder="Contoh: Cibinong">
                        </div>
                        <div>
                            <label class="block font-semibold mb-2 text-gray-700 text-sm">Kode Pos</label>
                            <input type="text" id="kode_pos" class="w-full border border-gray-300 rounded px-3 py-2 text-sm" placeholder="Contoh: 16915">
                        </div>
                    </div>

                    <!-- Detail Jalan -->
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700 text-sm">Detail Jalan</label>
                        <textarea id="alamat_lengkap" class="w-full border border-gray-300 rounded px-3 py-2 text-sm" rows="3" placeholder="Contoh: Jl. Mawar No. 5A"></textarea>
                    </div>

                    <div class="flex gap-2 justify-end">
                        <button type="button" id="batalAlamatBtn" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded text-sm">Batal</button>
                        <button type="button" id="simpanAlamatBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">Simpan Alamat</button>
                    </div>
                </div>
            </div>

            <!-- BAGIAN 2: PRODUK -->
            <div class="bg-white rounded-lg border p-6 shadow-sm">
                <h3 class="font-bold text-lg mb-4 text-gray-900">Produk Dipesan</h3>
                <div class="space-y-4">
                    <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($item->product): ?>
                            <?php
                                $price = $isReseller ? $item->product->harga_reseller : $item->product->harga_normal;
                                
                                // PERBAIKAN NAMA KOLOM: Cek 'nama' atau 'nama_produk' atau 'name'
                                $productName = $item->product->nama ?? $item->product->nama_produk ?? $item->product->name ?? 'Produk Tanpa Nama';
                                
                                // Cek gambar
                                $gambar = $item->product->gambar ?? $item->product->image ?? null;
                            ?>
                            <div class="flex items-center justify-between border-b pb-4 last:border-0 last:pb-0">
                                <div class="flex items-center space-x-4">
                                    
                                    <?php if($gambar): ?>
                                        <img src="<?php echo e(asset('storage/' . $gambar)); ?>" class="w-16 h-16 object-cover rounded border" alt="<?php echo e($productName); ?>">
                                    <?php else: ?>
                                        <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center text-xs text-gray-500">No Img</div>
                                    <?php endif; ?>
                                    
                                    <div>
                                        
                                        <h4 class="font-semibold text-gray-800"><?php echo e($productName); ?></h4>
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
                
                <!-- Info Berat -->
                <div class="text-right text-sm text-gray-500 mt-4 border-t pt-2">
                    Total Berat: <strong><?php echo e(number_format($totalWeight)); ?> gram</strong>
                </div>
            </div>

            <!-- BAGIAN 3: PENGIRIMAN -->
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

            <!-- BAGIAN 4: PEMBAYARAN -->
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

            <!-- BAGIAN 5: RINGKASAN TOTAL -->
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

                <!-- HIDDEN INPUTS -->
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
    // Cek data dari PHP
    if (typeof window.checkoutData === 'undefined') return;

    const { subtotal, totalWeight, adminFee } = window.checkoutData;
    let currentOngkir = 0;

    const formAlamat = document.getElementById('alamatForm');
    const displayAlamat = document.getElementById('alamatDisplay');
    const destinationInput = document.getElementById('destination_id');

    // 1. Preview Gambar
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

    // 2. Toggle Alamat
    const toggleBtn = document.getElementById('toggleAlamatBtn');
    if(toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            formAlamat.classList.remove('hidden');
            displayAlamat.classList.add('hidden');
            loadProvinces();
        });
    }

    const batalBtn = document.getElementById('batalAlamatBtn');
    if(batalBtn) {
        batalBtn.addEventListener('click', function() {
            if ("<?php echo e(Auth::user()->alamat); ?>") {
                formAlamat.classList.add('hidden');
                displayAlamat.classList.remove('hidden');
            } else {
                alert("Alamat wajib diisi.");
            }
        });
    }

    // 3. Load Provinsi
    async function loadProvinces() {
        const select = document.getElementById('provinsi');
        if(select.options.length > 1) return; 
        try {
            const res = await fetch('/api/ongkir/provinces');
            const data = await res.json();
            select.innerHTML = '<option value="">Pilih Provinsi</option>';
            data.forEach(item => {
                select.innerHTML += `<option value="${item.id}">${item.name}</option>`;
            });
        } catch (err) { console.error(err); }
    }

    // 4. Load Kota
    document.getElementById('provinsi').addEventListener('change', async function() {
        const id = this.value;
        const kota = document.getElementById('kota');
        kota.innerHTML = '<option value="">Loading...</option>';
        kota.disabled = true;
        kota.style.backgroundColor = "#f9fafb"; 
        if(!id) return;
        try {
            const res = await fetch(`/api/ongkir/cities/${id}`);
            const data = await res.json();
            kota.innerHTML = '<option value="">Pilih Kota/Kab</option>';
            kota.disabled = false;
            kota.style.backgroundColor = "#ffffff";
            data.forEach(item => {
                kota.innerHTML += `<option value="${item.city_id}">${item.type} ${item.city_name}</option>`;
            });
        } catch (err) { console.error(err); }
    });

    // 5. Simpan Alamat
    document.getElementById('simpanAlamatBtn').addEventListener('click', async function() {
        const prov = document.getElementById('provinsi');
        const kota = document.getElementById('kota');
        const kec = document.getElementById('kecamatan').value;
        const zip = document.getElementById('kode_pos').value;
        const detail = document.getElementById('alamat_lengkap').value;

        if(!prov.value || !kota.value || !kec || !zip || !detail) {
            alert("Mohon lengkapi semua kolom.");
            return;
        }

        const btn = this;
        btn.disabled = true;
        btn.textContent = "Menyimpan...";

        const provTxt = prov.options[prov.selectedIndex].text;
        const kotaTxt = kota.options[kota.selectedIndex].text;
        const fullAddress = `${detail}, Kec. ${kec}, ${kotaTxt}, ${provTxt}, ${zip}`;

        try {
            const res = await fetch('/update-alamat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    address_string: fullAddress,
                    province_id: prov.value,
                    city_id: kota.value,
                    postal_code: zip
                })
            });

            if (!res.ok) throw new Error("Gagal");
            
            displayAlamat.innerHTML = `
                <p class="font-semibold text-gray-900"><?php echo e(Auth::user()->nama); ?> (<?php echo e(Auth::user()->no_hp); ?>)</p>
                <p class="text-gray-600 text-sm leading-relaxed">${fullAddress}</p>
            `;
            formAlamat.classList.add('hidden');
            displayAlamat.classList.remove('hidden');
            destinationInput.value = kota.value;
            
            // Reset Ongkir
            document.getElementById('kurir').value = "";
            document.getElementById('layanan_ongkir').innerHTML = '<option value="">-- Pilih Kurir Dulu --</option>';
            updateTotal(0);

            alert("Alamat tersimpan!");
        } catch (err) {
            alert("Gagal menyimpan alamat.");
        } finally {
            btn.disabled = false;
            btn.textContent = "Simpan Alamat";
        }
    });

    // 6. Cek Ongkir
    document.getElementById('kurir').addEventListener('change', async function() {
        const kurir = this.value;
        const dest = destinationInput.value;
        const lay = document.getElementById('layanan_ongkir');

        if(!dest) {
            alert("Mohon simpan alamat dulu.");
            this.value = "";
            return;
        }
        if(!kurir) return;

        lay.innerHTML = '<option>Menghitung...</option>';
        lay.disabled = true;

        try {
            const res = await fetch('/api/ongkir/cost', {
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
                lay.innerHTML = '<option value="">-- Pilih Layanan --</option>';
                // Handle data array
                const results = Array.isArray(json.data) ? json.data : (json.data[0]?.costs || []);

                if(results.length > 0) {
                    results.forEach(item => {
                        const serviceName = item.service;
                        const costVal = item.cost[0].value;
                        const etd = item.cost[0].etd;
                        lay.innerHTML += `<option value="${costVal}|${serviceName}">${serviceName} (${etd} Hari) - Rp ${new Intl.NumberFormat('id-ID').format(costVal)}</option>`;
                    });
                    lay.disabled = false;
                } else {
                    lay.innerHTML = '<option>Tidak ada layanan</option>';
                }
            } else {
                lay.innerHTML = '<option>Gagal Cek Ongkir</option>';
            }
        } catch (err) {
            console.error(err);
            lay.innerHTML = '<option>Error Sistem</option>';
        }
    });

    // 7. Update Total
    document.getElementById('layanan_ongkir').addEventListener('change', function() {
        if(this.value) {
            const [harga, nama] = this.value.split('|');
            updateTotal(parseInt(harga));
            document.getElementById('layanan_name').value = nama;
        } else {
            updateTotal(0);
        }
    });

    function updateTotal(ongkir) {
        currentOngkir = ongkir;
        document.getElementById('ongkir_value').value = ongkir;
        document.getElementById('ongkir_display').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(ongkir);
        document.getElementById('total_display').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal + adminFee + ongkir);
    }

    // 8. Submit
    const checkoutForm = document.getElementById('checkoutForm');
    if(checkoutForm) {
        checkoutForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            if(currentOngkir === 0) {
                alert("Pilih pengiriman dulu.");
                return;
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
                    alert("Pesanan Berhasil!");
                    window.location.href = '/orders'; 
                } else {
                    alert(json.message || "Gagal memproses pesanan.");
                }
            } catch (err) {
                alert("Kesalahan jaringan.");
            } finally {
                btn.textContent = "Buat Pesanan";
                btn.disabled = false;
            }
        });
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/checkout/index.blade.php ENDPATH**/ ?>