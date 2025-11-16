<div class="grid grid-cols-7 items-center py-4 border-b bg-white">

    {{-- Checkbox + gambar + nama --}}
    <div class="col-span-3 flex items-center space-x-4">
        <input type="checkbox" checked class="w-5 h-5">

        <img src="{{ asset('storage/' . $item->product->gambar) }}" 
             class="w-20 h-20 object-cover rounded">

        <span class="font-semibold text-[#3c2f2f]">
            {{ $item->product->nama }}
        </span>
    </div>

    {{-- Harga --}}
    <div class="text-center text-[#3c2f2f]">
        Rp {{ number_format($item->product->harga_normal, 0, ',', '.') }}
    </div>

    {{-- Qty --}}
    <div class="text-center">

        <form action="{{ route('cart.update', $item->id) }}" 
            method="POST"
            class="inline-flex items-center space-x-1">
            @csrf
            @method('PUT')

            <button type="button" 
                    class="px-2 py-1 border rounded minus-btn"
                    data-target="qty-{{ $item->id }}">-</button>

            <input id="qty-{{ $item->id }}" 
                type="number" name="quantity"
                value="{{ $item->quantity }}" 
                min="1"
                class="w-12 text-center border rounded"
                onchange="this.form.submit()">

            <button type="button" 
                    class="px-2 py-1 border rounded plus-btn"
                    data-target="qty-{{ $item->id }}">+</button>
        </form>

    </div>


    {{-- Subtotal + hapus --}}
    <div class="text-center">
        <strong>
            Rp {{ number_format($item->product->harga_normal * $item->quantity, 0, ',', '.') }}
        </strong>
    </div>

    <div class="text-center">
        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button class="px-3 text-[#b48a60] hover:text-[#a07850]">
            <i data-lucide="trash" class="w-5 h-5"></i>
        </button>
        </form>
    </div>
</div>
