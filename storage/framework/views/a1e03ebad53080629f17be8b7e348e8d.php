<style>
    #search-container {
        position: relative;
    }
    #search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 999;
        background: white;
        border: 1px solid #ddd;
        border-top: none;
        border-radius: 0 0 0.25rem 0.25rem;
        max-height: 300px;
        overflow-y: auto;
    }
    .result-item {
        padding: 0.75rem 1rem;
        cursor: pointer;
    }
    .result-item:hover {
        background: #f4f4f4;
    }
    .result-item small {
        color: #6c757d;
        display: block;
    }
</style>

<?php $__env->startSection('content'); ?>
<div class="container py-5">

    <h2 class="fw-bold mb-4">Checkout</h2>

    <div class="row">

        
        <div class="col-lg-8">

            <div class="card mb-4 p-4">
                <h4 class="fw-semibold mb-3">📍 Detail Pengiriman</h4>

                
                <label class="fw-semibold">Cari Lokasi (Kecamatan / Kelurahan / Kode Pos)</label>
                <div id="search-container">
                    <input type="text" id="search-input" class="form-control" 
                           placeholder="Ketik nama lokasi (min. 3 huruf)..." required>
                    
                    <div id="search-results" class="shadow-sm" style="display:none;"></div>
                </div>

                
                <input type="hidden" id="destination_id">
                
                <small class="form-text text-muted mb-3">
                    Contoh: "Beji Depok", "Sukaraja Bogor", atau "40111"
                </small>

                
                <label class="fw-semibold">Alamat Lengkap</label>
                <textarea id="alamat_pengiriman" class="form-control mb-3" 
                          placeholder="Nama jalan, nomor rumah, RT/RW, patokan..." required></textarea>
            </div>

            
            <div class="card p-4 mb-4">
                
                <h4 class="fw-semibold mb-3">🛒 Produk Dipesan</h4>
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga Satuan</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="fw-semibold"><?php echo e($item->product->nama); ?></td>
                                <td>Rp <?php echo e(number_format($item->product->harga_normal, 0, ',', '.')); ?></td>
                                <td><?php echo e($item->quantity); ?></td>
                                <td>Rp <?php echo e(number_format($item->product->harga_normal * $item->quantity, 0, ',', '.')); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            
            <div class="card p-4 mb-4">
                
                <h4 class="fw-semibold mb-3">💰 Metode Pembayaran</h4>
                <p class="mb-1">BCA : <b>872947210</b></p>
                <p class="mb-1">Mandiri : <b>52374233</b></p>
                <p class="mb-1">Gopay : <b>0894758342</b></p>
                <p class="mb-3">Dana : <b>0893675432</b></p>
                <label class="fw-semibold">Upload Bukti Pembayaran</label>
                <input type="file" id="bukti_transfer" class="form-control" accept="image/*" required>
                <div id="preview-container" class="text-center mt-3" style="display:none;">
                    <img id="preview-image" class="img-thumbnail" style="max-width: 200px;">
                </div>
            </div>
        </div>

        
        <div class="col-lg-4">
            <div class="card p-4 mb-4">

                
                <h5 class="fw-semibold mb-3">🚚 Pilih Ekspedisi</h5>

                <label class="fw-semibold">Kurir</label>
                <select id="kurir" class="form-select mb-3" disabled>
                    <option value="">-- Pilih Lokasi Dulu --</option>
                    <option value="jne">JNE</option>
                    <option value="pos">POS Indonesia</option>
                    <option value="tiki">TIKI</option>
                </select>

                
                <label class="fw-semibold">Layanan Pengiriman</label>
                <select id="layanan_ongkir" class="form-select mb-4" disabled>
                    <option value="">-- Pilih Kurir Dulu --</option>
                </select>

                <h5 class="fw-semibold mb-3">📦 Rincian Pembayaran</h5>

                <p class="d-flex justify-content-between">
                    <span>Subtotal Pesanan:</span>
                    <span>Rp <?php echo e(number_format($total, 0, ',', '.')); ?></span>
                </p>

                <p class="d-flex justify-content-between">
                    <span>Ongkir:</span>
                    <span id="ongkir_label">Rp 0</span>
                </p>

                <hr>

                <h4 class="d-flex justify-content-between">
                    <b>Total:</b>
                    <b id="total_label">Rp <?php echo e(number_format($total, 0, ',', '.')); ?></b>
                </h4>
                
                
                <input type="hidden" id="ongkir_value" value="0">
                <input type="hidden" id="layanan_selected_name" value="">

                
                <button id="submitCheckout" class="btn btn-dark w-100 mt-4">
                    Pesan Sekarang
                </button>

            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', () => {

    const subtotal = <?php echo e($total); ?>;
    let ongkir = 0;

    const searchInput = document.getElementById("search-input");
    const searchResults = document.getElementById("search-results");
    const destinationId = document.getElementById("destination_id");
    
    const kurirSelect = document.getElementById("kurir");
    const layananSelect = document.getElementById("layanan_ongkir");
    
    const ongkirLabel = document.getElementById("ongkir_label");
    const ongkirValue = document.getElementById("ongkir_value");
    const totalLabel = document.getElementById("total_label");
    const layananName = document.getElementById("layanan_selected_name");

    // ================== FUNGSI DEBOUNCE ==================
    // Ini untuk mencegah API dipanggil setiap kali user mengetik
    // Akan ada jeda 500ms setelah user berhenti mengetik
    let debounceTimer;
    function debounce(func, delay) {
        return function(...args) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                func.apply(this, args);
            }, delay);
        };
    }

    // ================== FUNGSI CARI LOKASI (BARU) ==================
    async function cariLokasi() {
        let keyword = searchInput.value;

        if (keyword.length < 3) {
            searchResults.style.display = 'none';
            return;
        }

        searchResults.style.display = 'block';
        searchResults.innerHTML = `<div class="result-item">Mencari...</div>`;

        try {
            const res = await fetch(`/api/ongkir/search-destination?q=${keyword}`);
            const data = await res.json();

            searchResults.innerHTML = ''; // Kosongkan hasil

            if (data.length === 0) {
                searchResults.innerHTML = `<div class="result-item">Lokasi tidak ditemukan</div>`;
                return;
            }

            data.forEach(lokasi => {
                // Tampilkan hasil di dropdown
                let item = document.createElement('div');
                item.className = 'result-item';
                // Tampilkan nama lengkap lokasi
                item.innerHTML = `
                    <b>${lokasi.subdistrict_name}, ${lokasi.district_name}</b>
                    <small>${lokasi.city_name}, ${lokasi.province_name} (${lokasi.postal_code})</small>
                `;
                // Tambahkan event klik ke setiap item
                item.addEventListener('click', () => {
                    pilihLokasi(lokasi);
                });
                searchResults.appendChild(item);
            });

        } catch (error) {
            console.error('Error cariLokasi:', error);
            searchResults.innerHTML = `<div class="result-item text-danger">Gagal memuat data</div>`;
        }
    }

    // ================== FUNGSI SAAT LOKASI DIPILIH ==================
    function pilihLokasi(lokasi) {
        // Isi kotak input dengan nama lokasi
        searchInput.value = `${lokasi.subdistrict_name}, ${lokasi.city_name}`;
        // Simpan ID destinasi di input tersembunyi
        destinationId.value = lokasi.subdistrict_id;
        
        // Sembunyikan hasil pencarian
        searchResults.style.display = 'none';

        // Aktifkan dropdown kurir
        kurirSelect.disabled = false;
        kurirSelect.value = '';
        layananSelect.innerHTML = `<option value="">-- Pilih Kurir Dulu --</option>`;
        layananSelect.disabled = true;

        // Reset ongkir
        updateTotal(0);
    }

    // ================== FUNGSI CARI HARGA (BARU) ==================
    async function cariHarga() {
        if (!kurirSelect.value || !destinationId.value) {
            return;
        }

        layananSelect.disabled = true;
        layananSelect.innerHTML = `<option value="">Loading...</option>`;

        try {
            const res = await fetch("/api/ongkir/search-price", {
                method: "POST",
                headers: { 
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>" // Jangan lupa CSRF token
                },
                body: JSON.stringify({
                    destination_id: parseInt(destinationId.value),
                    weight: 1000, // Ganti dengan berat total jika ada
                    courier: kurirSelect.value
                })
            });
            
            const data = await res.json();

            if (data.error) {
                throw new Error(data.error);
            }

            layananSelect.innerHTML = `<option value="">-- Pilih Layanan --</option>`;

            if (data.length === 0) {
                layananSelect.innerHTML = `<option value="">Layanan tidak tersedia</option>`;
                return;
            }

            // 'data' sekarang berisi array 'costs' dari kurir yg dipilih
            data.forEach(layanan => {
                let cost = layanan.cost[0].value;
                let etd = layanan.cost[0].etd;
                let serviceName = layanan.service;
                // Format harga
                let formattedCost = `Rp ${cost.toLocaleString('id-ID')}`;
                
                // Value-nya akan berisi: HARGA|NAMA LAYANAN
                let optionValue = `${cost}|${serviceName}`; 

                layananSelect.innerHTML += `
                    <option value="${optionValue}">
                        ${serviceName} (${formattedCost}) - (Estimasi ${etd} hari)
                    </option>
                `;
            });

            layananSelect.disabled = false;

        } catch (error) {
            console.error('Error cariHarga:', error);
            layananSelect.innerHTML = `<option value="">Gagal memuat layanan</option>`;
        }
    }

    // ================== FUNGSI UPDATE TOTAL HARGA ==================
    function updateTotal(biayaOngkir) {
        ongkir = parseInt(biayaOngkir);
        
        ongkirLabel.innerText = `Rp ${ongkir.toLocaleString('id-ID')}`;
        ongkirValue.value = ongkir; // Simpan di hidden input
        
        let total = subtotal + ongkir;
        totalLabel.innerText = `Rp ${total.toLocaleString('id-ID')}`;
    }

    // ==================== EVENT LISTENERS (BARU) ===================

    // Saat user mengetik di kotak pencarian
    searchInput.addEventListener("input", debounce(cariLokasi, 500));

    // Saat user memilih kurir (JNE/POS/TIKI)
    kurirSelect.addEventListener("change", cariHarga);
    
    // Saat user memilih layanan (REG/OKE/YES)
    layananSelect.addEventListener("change", () => {
        let selected = layananSelect.value;
        if (!selected) {
            updateTotal(0);
            layananName.value = '';
            return;
        }

        // Pecah value "HARGA|NAMA LAYANAN"
        let parts = selected.split('|');
        let harga = parseInt(parts[0]);
        let nama = parts[1];
        
        updateTotal(harga);
        layananName.value = nama; // Simpan nama layanan
    });

    // ==================== PREVIEW GAMBAR ===================
    // ... (Kode ini sama, tidak diubah) ...
    document.getElementById("bukti_transfer").addEventListener("change", function(){
        let file = this.files[0];
        if (!file) return;
        let reader = new FileReader();
        reader.onload = e => {
            document.getElementById("preview-container").style.display = "block";
            document.getElementById("preview-image").src = e.target.result;
        };
        reader.readAsDataURL(file);
    });

    // ==================== SUBMIT CHECKOUT ===================
    // Perlu di-update untuk mengirim data baru
    document.getElementById("submitCheckout").addEventListener("click", async () => {
        
        // Validasi Sederhana
        if (!destinationId.value) {
            alert("Lokasi pengiriman belum dipilih. Silakan cari dan pilih lokasi Anda.");
            return;
        }
        if (ongkirValue.value == 0 || !layananName.value) {
            alert("Layanan pengiriman belum dipilih.");
            return;
        }
        if (!document.getElementById("bukti_transfer").files[0]) {
             alert("Bukti transfer belum di-upload.");
            return;
        }

        let formData = new FormData();
        formData.append("alamat_pengiriman", document.getElementById("alamat_pengiriman").value);
        formData.append("kurir", kurirSelect.value); // 'jne', 'pos', 'tiki'
        formData.append("layanan_kurir", layananName.value); // 'REG', 'OKE', 'YES'
        formData.append("ongkir", ongkirValue.value); // '10000'
        formData.append("destination_id", destinationId.value); // ID dari Komerce
        formData.append("bukti_transfer", document.getElementById("bukti_transfer").files[0]);

        // Tampilkan loading
        document.getElementById("submitCheckout").disabled = true;
        document.getElementById("submitCheckout").innerText = "Memproses...";

        try {
            const res = await fetch("/checkout/full", { // Panggil Controller Anda
                method: "POST",
                headers: { "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>" },
                body: formData
            });

            const data = await res.json();

            if (res.ok) {
                alert("Pesanan berhasil dibuat!");
                window.location.href = "/orders"; // Arahkan ke halaman pesanan
            } else {
                alert(data.message || "Gagal membuat pesanan. Cek kembali data Anda.");
            }
        } catch (error) {
            console.error('Submit Error:', error);
            alert("Terjadi kesalahan. Silakan coba lagi.");
        } finally {
            document.getElementById("submitCheckout").disabled = false;
            document.getElementById("submitCheckout").innerText = "Pesan Sekarang";
        }
    });

});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/checkout/index.blade.php ENDPATH**/ ?>