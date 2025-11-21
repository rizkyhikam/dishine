@extends('layouts.app')

@section('title', 'Checkout - Dishine')

@section('content')

{{-- 1. PASS DATA PHP KE JAVASCRIPT --}}
<script>
    window.checkoutData = {
        subtotal: {{ $total }},
        totalWeight: {{ $totalWeight }},
        adminFee: {{ $adminFee }},
        // Data User untuk Logika Otomatis
        userAddress: `{{ Auth::user()->alamat ?? '' }}`,
        userCityId: "{{ Auth::user()->city_id ?? '' }}",
        userProvinceId: "{{ Auth::user()->province_id ?? '' }}" 
    };
</script>

<div class="container mx-auto px-4 py-8 max-w-4xl">
    <h1 class="text-3xl font-bold mb-8 text-[#3c2f2f]">Checkout</h1>

    @if($cartItems->isEmpty())
        <div class="text-center py-10 bg-white rounded border">
            <p class="text-gray-500">Keranjang belanja kosong.</p>
            <a href="{{ route('katalog') }}" class="text-blue-600 hover:underline">Belanja Dulu</a>
        </div>
    @else

    <form id="checkoutForm" enctype="multipart/form-data">
        @csrf
        <div class="flex flex-col gap-8">

            <div class="bg-white rounded-lg border p-6 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="font-bold text-lg text-gray-900">Alamat Pengiriman</h3>
                    <button type="button" id="toggleAlamatBtn" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        {{ Auth::user()->alamat ? 'Ubah Alamat' : 'Isi Alamat Baru' }}
                    </button>
                </div>
                
                <div id="alamatDisplay" class="{{ Auth::user()->alamat ? '' : 'hidden' }} space-y-2">
                    <p class="font-semibold text-gray-900">
                        {{ Auth::user()->nama }} ({{ Auth::user()->no_hp }})
                    </p>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        {{ Auth::user()->alamat ?? 'Belum ada alamat tersimpan.' }}
                    </p>
                </div>

                <div id="alamatForm" class="{{ Auth::user()->alamat ? 'hidden' : '' }} mt-4 space-y-4 border-t pt-4">
                    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 text-sm p-3 rounded mb-3">
                        <i class="fas fa-info-circle"></i> Silakan lengkapi/update alamat pengiriman Anda.
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold mb-2 text-gray-700 text-sm">Provinsi</label>
                            <select name="province_id" id="province_id" class="w-full border border-gray-300 rounded px-3 py-2 text-sm bg-white">
                                <option value="">-- Pilih Provinsi --</option>
                                @foreach($provinces as $prov)
                                    @php
                                        $pId = $prov['province_id'] ?? $prov['id'] ?? null;
                                        $pName = $prov['province'] ?? $prov['name'] ?? '';
                                        // Pre-select Provinsi User
                                        $isSelected = (Auth::user()->province_id == $pId) ? 'selected' : '';
                                    @endphp
                                    @if($pId)
                                        <option value="{{ $pId }}" data-name="{{ $pName }}" {{ $isSelected }}>
                                            {{ $pName }}
                                        </option>
                                    @endif
                                @endforeach
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
                        <label class="block font-semibold mb-2 text-gray-700 text-sm">Detail Jalan</label>
                        <textarea name="detail_alamat" id="detail_alamat" class="w-full border border-gray-300 rounded px-3 py-2 text-sm" rows="3" placeholder="Contoh: Jl. Mawar No. 5A, Kec. Cibinong">{{ Auth::user()->alamat }}</textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border p-6 shadow-sm">
                <h3 class="font-bold text-lg mb-4 text-gray-900">Produk Dipesan</h3>
                <div class="space-y-4">
                    @foreach($cartItems as $item)
                        @if($item->product)
                            @php
                                $price = $isReseller ? $item->product->harga_reseller : $item->product->harga_normal;
                                $pName = $item->product->nama ?? $item->product->nama_produk ?? 'Produk';
                                $img = $item->product->gambar ?? null;
                            @endphp
                            <div class="flex items-center justify-between border-b pb-4 last:border-0 last:pb-0">
                                <div class="flex items-center space-x-4">
                                    @if($img)
                                        <img src="{{ asset('storage/' . $img) }}" class="w-16 h-16 object-cover rounded border" alt="{{ $pName }}">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center text-xs text-gray-500">No Img</div>
                                    @endif
                                    <div>
                                        <h4 class="font-semibold text-gray-800">{{ $pName }}</h4>
                                        
                                        {{-- >>> MENAMPILKAN VARIAN (WARNA/SIZE) <<< --}}
                                        @php
                                            $detailInfo = [];
                                            if ($item->variantSize) {
                                                if ($item->variantSize->productVariant && $item->variantSize->productVariant->warna) {
                                                    $detailInfo[] = "Warna: " . $item->variantSize->productVariant->warna;
                                                }
                                                if ($item->variantSize->size && $item->variantSize->size->name) {
                                                    $detailInfo[] = "Size: " . $item->variantSize->size->name;
                                                }
                                            }
                                        @endphp
                                        @if(count($detailInfo) > 0)
                                            <p class="text-xs text-gray-500 mb-1">
                                                {{ implode(' | ', $detailInfo) }}
                                            </p>
                                        @endif
                                        {{-- >>> END VARIAN <<< --}}

                                        <p class="text-sm text-gray-500">
                                            {{ $item->quantity }} x Rp {{ number_format($price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="font-semibold text-gray-900">
                                    Rp {{ number_format($price * $item->quantity, 0, ',', '.') }}
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="text-right text-sm text-gray-500 mt-4 border-t pt-2">
                    Total Berat: <strong>{{ number_format($totalWeight) }} gram</strong>
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
                        <span class="font-medium">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Ongkos Kirim</span>
                        <span id="ongkir_display" class="font-medium text-blue-600">Rp 0</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Biaya Admin</span>
                        <span>Rp {{ number_format($adminFee, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="flex justify-between items-center border-t pt-4 mb-6">
                    <span class="font-bold text-lg">Total Bayar</span>
                    <span id="total_display" class="font-bold text-xl">Rp {{ number_format($total + $adminFee, 0, ',', '.') }}</span>
                </div>

                <input type="hidden" id="ongkir_value" name="ongkir_value" value="0">
                <input type="hidden" id="layanan_name" name="layanan_selected_name" value="">
                <input type="hidden" id="destination_id" name="destination" value="{{ Auth::user()->city_id ?? '' }}">
                
                <button type="submit" id="submitBtn" class="w-full bg-[#AE8B56] hover:bg-[#8f7246] text-white font-bold py-3 px-4 rounded">
                    Buat Pesanan
                </button>
            </div>
        </div>
    </form>
    @endif
</div>

{{-- 2. SCRIPT JAVASCRIPT LOGIC --}}
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
                citySelect.disabled = false; // <--- INI MEMBUAT TOMBOL KOTA BISA DIPENCET
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

                // 🔥 FITUR PENTING: Auto-load kota jika provinsi sudah terpilih
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
            btn.textContent = "Memproses...";
            btn.disabled = true;
            
            const formData = new FormData(this);
            
            try {
                const res = await fetch('{{ route("checkout.store") }}', {
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
                btn.textContent = "Buat Pesanan";
                btn.disabled = false;
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
});
</script>
@endsection