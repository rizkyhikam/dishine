<!-- Products Ordered - Sesuai HIFI -->
<div class="bg-white rounded-lg border p-6 mb-6">
    <h3 class="font-bold text-lg mb-4 text-gray-900">Produk Dipesan</h3>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b">
                    <th class="text-left font-semibold text-gray-600 pb-3">Produk</th>
                    <th class="text-center font-semibold text-gray-600 pb-3">Harga Satuan</th>
                    <th class="text-center font-semibold text-gray-600 pb-3">Jumlah</th>
                    <th class="text-center font-semibold text-gray-600 pb-3">Sub Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $item)
                <tr class="border-b">
                    <td class="py-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-16 h-16 bg-gray-100 rounded flex items-center justify-center">
                                @if($item->product->gambar)
                                    <img src="{{ asset('storage/' . $item->product->gambar) }}" 
                                         alt="{{ $item->product->nama }}"
                                         class="w-16 h-16 object-cover rounded">
                                @else
                                    <span class="text-gray-400 font-semibold text-sm">
                                        {{ substr($item->product->nama, 0, 2) }}
                                    </span>
                                @endif
                            </div>
                            <span class="font-semibold text-gray-900">
                                {{ $item->product->nama }}
                            </span>
                        </div>
                    </td>
                    <td class="text-center text-gray-700 py-4">
                        Rp {{ number_format($item->product->harga_normal, 0, ',', '.') }}
                    </td>
                    <td class="text-center text-gray-700 py-4">
                        {{ $item->quantity }}
                    </td>
                    <td class="text-center font-semibold text-gray-900 py-4">
                        Rp {{ number_format($item->product->harga_normal * $item->quantity, 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>