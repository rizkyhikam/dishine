@extends('layouts.app')

@section('content')
<div class="container mt-5 mb-5">
    <h2 class="text-center mb-4">💳 Checkout Pesanan</h2>

    <form id="checkoutForm" enctype="multipart/form-data">
        @csrf

        {{-- =========================
             ALAMAT PENGIRIMAN
        ========================== --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Provinsi</label>
            <select id="provinsi" class="form-select" required>
                <option value="">-- Pilih Provinsi --</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Kota / Kabupaten</label>
            <select id="kota" class="form-select" required>
                <option value="">-- Pilih Kota --</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Kecamatan</label>
            <select id="kecamatan" class="form-select" required>
                <option value="">-- Pilih Kecamatan --</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Alamat Lengkap</label>
            <textarea id="alamat_pengiriman" name="alamat_pengiriman" class="form-control" required></textarea>
        </div>

        {{-- =========================
             KURIR & ONGKIR
        ========================== --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Pilih Kurir</label>
            <select id="kurir" name="kurir" class="form-select" required>
                <option value="">-- Pilih Kurir --</option>
                <option value="jne">JNE</option>
                <option value="pos">POS Indonesia</option>
                <option value="tiki">TIKI</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Ongkir</label>
            <input type="text" id="ongkir" name="ongkir" class="form-control" readonly placeholder="Pilih kurir dulu...">
        </div>

        {{-- =========================
             BUKTI TRANSFER
        ========================== --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Upload Bukti Transfer</label>
            <input type="file" name="bukti_transfer" id="bukti_transfer" class="form-control" accept="image/*" required>
            <small class="text-muted">*Format: JPG/PNG, Maks 2MB</small>

            <div class="mt-3 text-center" id="preview-container" style="display:none;">
                <p class="fw-semibold">📸 Pratinjau Bukti Transfer:</p>
                <img id="preview-image" src="#" alt="Preview" class="img-thumbnail" style="max-width: 250px; border: 2px solid #ccc;">
            </div>
        </div>

        {{-- =========================
             TOTAL PEMBAYARAN
        ========================== --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Total Pembayaran</label>
            <input type="text" id="total" class="form-control" readonly placeholder="Rp 0">
        </div>

        <div class="d-grid mt-4">
            <button type="submit" class="btn btn-success btn-lg">
                ✅ Selesaikan Pesanan
            </button>
        </div>
    </form>
</div>

{{-- =========================
     SCRIPT JS INTERAKTIF
========================= --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const provinsiSelect = document.getElementById('provinsi');
    const kotaSelect = document.getElementById('kota');
    const kecamatanSelect = document.getElementById('kecamatan');
    const kurirSelect = document.getElementById('kurir');
    const ongkirInput = document.getElementById('ongkir');
    const totalInput = document.getElementById('total');
    const checkoutForm = document.getElementById('checkoutForm');
    const buktiTransfer = document.getElementById('bukti_transfer');
    const previewContainer = document.getElementById('preview-container');
    const previewImage = document.getElementById('preview-image');

    let totalProduk = 0;
    let ongkir = 0;

    // 🔹 Ambil total produk dari backend
    fetch('/api/cart/total')
        .then(res => res.json())
        .then(data => {
            totalProduk = data.total || 0;
            totalInput.value = `Rp ${totalProduk.toLocaleString('id-ID')}`;
        });

    // 🔹 Load provinsi
    fetch('/api/ongkir/provinces')
        .then(res => res.json())
        .then(data => {
            data.forEach(prov => {
                provinsiSelect.innerHTML += `<option value="${prov.province_id}">${prov.province}</option>`;
            });
        });

    // 🔹 Load kota setelah pilih provinsi
    provinsiSelect.addEventListener('change', async () => {
        kotaSelect.innerHTML = '<option value="">-- Memuat kota... --</option>';
        const res = await fetch(`/api/ongkir/cities/${provinsiSelect.value}`);
        const data = await res.json();
        kotaSelect.innerHTML = '<option value="">-- Pilih Kota --</option>';
        data.forEach(city => {
            kotaSelect.innerHTML += `<option value="${city.city_id}">${city.type} ${city.city_name}</option>`;
        });
    });

    // 🔹 Load kecamatan setelah pilih kota
    kotaSelect.addEventListener('change', async () => {
        kecamatanSelect.innerHTML = '<option value="">-- Memuat kecamatan... --</option>';
        const res = await fetch(`/api/ongkir/districts/${kotaSelect.value}`);
        const data = await res.json();
        kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
        data.forEach(d => {
            kecamatanSelect.innerHTML += `<option value="${d.subdistrict_id}">${d.subdistrict_name}</option>`;
        });
    });

    // 🚚 Hitung ongkir (origin: Bogor)
    async function hitungOngkir() {
        const destination = kecamatanSelect.value;
        const kurir = kurirSelect.value;
        if (!destination || !kurir) return;

        const res = await fetch('/api/ongkir/cost', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                origin: 2307, // ⚙️ ID Kecamatan Bogor
                destination,
                weight: 1000,
                courier: kurir
            })
        });

        const data = await res.json();
        if (data.success) {
            ongkir = data.cost;
            ongkirInput.value = `Rp ${ongkir.toLocaleString('id-ID')}`;
            totalInput.value = `Rp ${(totalProduk + ongkir).toLocaleString('id-ID')}`;
        } else {
            alert('❌ Gagal menghitung ongkir');
        }
    }

    kurirSelect.addEventListener('change', hitungOngkir);
    kecamatanSelect.addEventListener('change', hitungOngkir);

    // 🔹 Preview gambar bukti transfer
    buktiTransfer.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            previewContainer.style.display = 'block';
            previewImage.src = e.target.result;
        };
        reader.readAsDataURL(file);
    });

    // 🧾 Submit checkout form
    checkoutForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(checkoutForm);
        formData.append('ongkir', ongkir);

        try {
            const response = await fetch('/checkout/full', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value },
                body: formData
            });

            const data = await response.json();

            if (response.ok) {
                alert('✅ Pesanan berhasil dibuat dan bukti transfer diterima!');
                window.location.href = '/orders';
            } else {
                alert(data.message || 'Gagal memproses pesanan.');
            }
        } catch (error) {
            console.error(error);
            alert('Terjadi kesalahan saat checkout.');
        }
    });
});
</script>
@endsection
