@extends('layouts.app')

{{-- 
    TAMBAHKAN STYLE INI UNTUK DAFTAR AUTOCOMPLETE 
    Bisa ditaruh di file .css Anda atau di sini
--}}
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

@section('content')
<div class="container py-5">

    <h2 class="fw-bold mb-4">Checkout</h2>

    <div class="row">

        {{-- =======================
            ALAMAT PENGIRIMAN
        ======================== --}}
        <div class="col-lg-8">

            <div class="card mb-4 p-4">
                <h4 class="fw-semibold mb-3">📍 Detail Pengiriman</h4>

                {{-- KOTAK PENCARIAN BARU --}}
                <label class="fw-semibold">Cari Lokasi (Kecamatan / Kelurahan / Kode Pos)</label>
                <div id="search-container">
                    <input type="text" id="search-input" class="form-control" 
                           placeholder="Ketik nama lokasi (min. 3 huruf)..." required>
                    {{-- Ini tempat hasil pencarian muncul --}}
                    <div id="search-results" class="shadow-sm" style="display:none;"></div>
                </div>

                {{-- Input tersembunyi untuk menyimpan ID destinasi --}}
                <input type="hidden" id="destination_id">
                
                <small class="form-text text-muted mb-3">
                    Contoh: "Beji Depok", "Sukaraja Bogor", atau "40111"
                </small>

                {{-- Alamat lengkap --}}
                <label class="fw-semibold">Alamat Lengkap</label>
                <textarea id="alamat_pengiriman" class="form-control mb-3" 
                          placeholder="Nama jalan, nomor rumah, RT/RW, patokan..." required></textarea>
            </div>

            {{-- =======================
                 PRODUK DI CART
            ======================== --}}
            <div class="card p-4 mb-4">
                {{-- ... (Bagian ini sama, tidak perlu diubah) ... --}}
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
                        @foreach ($cartItems as $item)
                            <tr>
                                <td class="fw-semibold">{{ $item->product->nama }}</td>
                                <td>Rp {{ number_format($item->product->harga_normal, 0, ',', '.') }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>Rp {{ number_format($item->product->harga_normal * $item->quantity, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- =======================
                 METODE PEMBAYARAN
            ======================== --}}
            <div class="card p-4 mb-4">
                {{-- ... (Bagian ini sama, tidak perlu diubah) ... --}}
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

        {{-- =======================
             SIDEBAR RINGKASAN
        ======================== --}}
        <div class="col-lg-4">
            <div class="card p-4 mb-4">

                {{-- KURIR --}}
                <h5 class="fw-semibold mb-3">🚚 Pilih Ekspedisi</h5>

                <label class="fw-semibold">Kurir</label>
                <select id="kurir" class="form-select mb-3" disabled>
                    <option value="">-- Pilih Lokasi Dulu --</option>
                    <option value="jne">JNE</option>
                    <option value="pos">POS Indonesia</option>
                    <option value="tiki">TIKI</option>
                </select>

                {{-- LAYANAN (ONGKIR) --}}
                <label class="fw-semibold">Layanan Pengiriman</label>
                <select id="layanan_ongkir" class="form-select mb-4" disabled>
                    <option value="">-- Pilih Kurir Dulu --</option>
                </select>

                <h5 class="fw-semibold mb-3">📦 Rincian Pembayaran</h5>

                <p class="d-flex justify-content-between">
                    <span>Subtotal Pesanan:</span>
                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                </p>

                <p class="d-flex justify-content-between">
                    <span>Ongkir:</span>
                    <span id="ongkir_label">Rp 0</span>
                </p>

                <hr>

                <h4 class="d-flex justify-content-between">
                    <b>Total:</b>
                    <b id="total_label">Rp {{ number_format($total, 0, ',', '.') }}</b>
                </h4>
                
                {{-- Hidden input untuk menyimpan ongkir --}}
                <input type="hidden" id="ongkir_value" value="0">
                <input type="hidden" id="layanan_selected_name" value="">

                {{-- BUTTON --}}
                <button id="submitCheckout" class="btn btn-dark w-100 mt-4">
                    Pesan Sekarang
                </button>

            </div>
        </div>
    </div>
</div>

{{-- =======================
     SCRIPT (REVISI TOTAL)
======================== --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ================== VARIABEL GLOBAL ==================
    const subtotal = {{ $total }};
    const biayaLayanan = 2000;
    let ongkir = 0;
    let selectedDistrictId = null; // Ini akan menyimpan ID kecamatan

    // Elemen DOM
    const kurirSelect = document.getElementById('kurir');
    const layananSelect = document.getElementById('layanan_ongkir');
    const form = document.getElementById('checkoutForm');
    
    const provinsiSelect = document.getElementById('provinsi');
    const kotaSelect = document.getElementById('kota');
    const kecamatanSelect = document.getElementById('kecamatan');
    const kodePosInput = document.getElementById('kode_pos');
    const alamatLengkapTextarea = document.getElementById('alamat_lengkap');
    const destinationInput = document.querySelector('input[name="destination"]');
    
    const alamatDisplay = document.getElementById('alamatDisplay');
    const alamatForm = document.getElementById('alamatForm');

    // ================== FUNGSI UPDATE TOTAL ==================
    function updateTotal(biayaOngkir) {
        ongkir = parseInt(biayaOngkir);
        document.getElementById('ongkir_label').textContent = `Rp ${ongkir.toLocaleString('id-ID')}`;
        document.getElementById('ongkir_value').value = ongkir;
        let total = subtotal + ongkir + biayaLayanan;
        document.getElementById('total_label').textContent = `Rp ${total.toLocaleString('id-ID')}`;
    }

    // ================== PREVIEW GAMBAR ==================
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
        alamatDisplay.classList.toggle('hidden');
        alamatForm.classList.toggle('hidden');
        if (!alamatForm.classList.contains('hidden')) {
            loadProvinces();
        }
    });

    document.getElementById('batalAlamatBtn').addEventListener('click', function() {
        alamatDisplay.classList.remove('hidden');
        alamatForm.classList.add('hidden');
    });

    // ================== LOAD DATA WILAYAH (LOGIKA DIPERBAIKI) ==================
    
    // 1. Load Provinsi
    async function loadProvinces() {
        try {
            const response = await fetch('/api/ongkir/provinces');
            const provinces = await response.json();
            
            provinsiSelect.innerHTML = '<option value="">Pilih Provinsi</option>';
            provinces.forEach(province => {
                provinsiSelect.innerHTML += `<option value="${province.province_id}">${province.province}</option>`;
            });
        } catch (error) { console.error('Error loading provinces:', error); }
    }

    // 2. Load Kota (saat Provinsi dipilih)
    provinsiSelect.addEventListener('change', async function() {
        const provinceId = this.value;
        kotaSelect.disabled = true;
        kecamatanSelect.disabled = true;
        kotaSelect.innerHTML = '<option value="">Loading...</option>';
        kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
        kodePosInput.value = '';
        if (!provinceId) {
            kotaSelect.innerHTML = '<option value="">Pilih Kota</option>';
            return;
        }

        try {
            const response = await fetch(`/api/ongkir/cities/${provinceId}`);
            const cities = await response.json();
            
            kotaSelect.innerHTML = '<option value="">Pilih Kota</option>';
            cities.forEach(city => {
                kotaSelect.innerHTML += `<option value="${city.city_id}">${city.city_name}</option>`;
            });
            kotaSelect.disabled = false;
        } catch (error) { 
            console.error('Error loading cities:', error); 
            kotaSelect.innerHTML = '<option value="">Gagal memuat</option>';
        }
    });

    // 3. Load KECAMATAN (saat Kota dipilih)
    kotaSelect.addEventListener('change', async function() {
        const cityId = this.value;
        kecamatanSelect.disabled = true;
        kecamatanSelect.innerHTML = '<option value="">Loading...</option>';
        kodePosInput.value = '';
        if (!cityId) {
            kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
            return;
        }

        try {
            const response = await fetch(`/api/ongkir/districts/${cityId}`); 
            const districts = await response.json();
            
            kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
            districts.forEach(district => {
                kecamatanSelect.innerHTML += `<option value="${district.district_id}" data-postal="${district.zip_code}">${district.district_name}</option>`;
            });
            kecamatanSelect.disabled = false;
        } catch (error) { 
            console.error('Error loading districts:', error); 
            kecamatanSelect.innerHTML = '<option value="">Gagal memuat</option>';
        }
    });

    // 4. Saat Kecamatan dipilih
    kecamatanSelect.addEventListener('change', async function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (!this.value) {
            kodePosInput.value = '';
            selectedDistrictId = null;
            destinationInput.value = '';
            return;
        }

        const districtId = this.value;
        const kodePos = selectedOption.getAttribute('data-postal');

        kodePosInput.value = kodePos || 'N/A';
        selectedDistrictId = districtId; 
        destinationInput.value = districtId; 
    });

    // ================== SIMPAN ALAMAT ==================
    document.getElementById('simpanAlamatBtn').addEventListener('click', async function() {
        const provinsi = provinsiSelect.options[provinsiSelect.selectedIndex]?.textContent;
        const kota = kotaSelect.options[kotaSelect.selectedIndex]?.textContent;
        const kecamatan = kecamatanSelect.options[kecamatanSelect.selectedIndex]?.textContent;
        const alamatLengkap = alamatLengkapTextarea.value;
        const kodePos = kodePosInput.value;

        if (!provinsi || !kota || !kecamatan || !alamatLengkap || !kodePos || !selectedDistrictId) {
            alert('Harap lengkapi semua data alamat (Provinsi, Kota, Kecamatan, dan Alamat Lengkap).');
            return;
        }

        const alamatFinal = `${alamatLengkap}, ${kecamatan}, ${kota}, ${provinsi} ${kodePos}`;

        try {
            const response = await fetch('/api/update-alamat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ alamat: alamatFinal })
            });

            if (response.ok) {
                document.getElementById('alamatDisplay').querySelector('p:last-child').textContent = alamatFinal;
                alamatDisplay.classList.remove('hidden');
                alamatForm.classList.add('hidden');
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
        let destinationId = selectedDistrictId || destinationInput.value;

        if (!kurir || !destinationId) {
            if (!destinationId) alert('Harap pilih alamat (Kecamatan) Anda terlebih dahulu.');
            layananSelect.innerHTML = '<option value="">-- Pilih Layanan --</option>';
            layananSelect.disabled = true;
            return;
        }

        layananSelect.disabled = true;
        layananSelect.innerHTML = '<option value="">Loading...</option>';

        try {
            const response = await fetch('/api/ongkir/cost', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    destination: destinationId, // ID Kecamatan
                    kurir: kurir,
                    weight: 1000 // Berat default 1kg
                })
            });

            const data = await response.json();

            if (data.success) {
                layananSelect.innerHTML = '<option value="">-- Pilih Layanan --</option>';
                
                if (data.data.length === 0) {
                     layananSelect.innerHTML = '<option value="">Layanan tidak tersedia</option>';
                }

                data.data.forEach(layanan => {
                    const option = document.createElement('option');
                    option.value = `${layanan.cost}|${layanan.service}`;
                    option.textContent = `${layanan.service} (${layanan.description}) - Rp ${layanan.cost.toLocaleString('id-ID')}`;
                    layananSelect.appendChild(option);
                });

                layananSelect.disabled = false;
            } else {
                throw new Error(data.message || 'Gagal mengambil data ongkir');
            }
        } catch (error) {
            console.error('Error:', error);
            layananSelect.innerHTML = '<option value="">Gagal memuat layanan</option>';
        }
    });

    // ================== SAAT LAYANAN DIPILIH ==================
    layananSelect.addEventListener('change', function() {
        if (this.value) {
            const [cost, service] = this.value.split('|');
            updateTotal(parseInt(cost));
            document.getElementById('layanan_selected_name').value = service;
        } else {
            updateTotal(0);
        }
    });

    // ================== SUBMIT CHECKOUT (DIPERBAIKI) ==================
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // --- PERBAIKAN BARU ---
        // Cek apakah user sedang mengedit alamat tapi belum disimpan
        if (!alamatForm.classList.contains('hidden')) {
            alert('Alamat Anda sedang diubah. Klik "Simpan Alamat" atau "Batal" terlebih dahulu sebelum memesan.');
            return;
        }
        // --- AKHIR PERBAIKAN ---

        // Validasi (sama seperti sebelumnya)
        const buktiTransfer = document.getElementById('bukti_transfer').files[0];
        const layananSelected = document.getElementById('layanan_selected_name').value;
        const ongkirValue = document.getElementById('ongkir_value').value;
        const kurir = document.getElementById('kurir').value;
        const destinationId = selectedDistrictId || destinationInput.value;

        if (!layananSelected || ongkirValue === '0' || !kurir) {
            alert('Silakan pilih layanan pengiriman.');
            return;
        }
        if (!buktiTransfer) {
            alert('Bukti transfer belum diupload.');
            return;
        }
        if (!destinationId) {
             alert('Alamat (Kecamatan) tujuan belum dipilih.');
            return;
        }

        const formData = new FormData(this);
        const submitBtn = document.getElementById('submitCheckout');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Memproses...';

        try {
            const response = await fetch('{{ route("checkout.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json' 
                },
                body: formData
            });

            const result = await response.json();

            if (response.ok) {
                alert('Pesanan berhasil dibuat!');
                window.location.href = '{{ route("orders.view") }}';
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

@endsection