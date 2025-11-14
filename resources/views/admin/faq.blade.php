@extends('layouts.admin')

@section('title', 'Kelola FAQ - Admin')

@section('content')
<div class="container mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold mb-8">Kelola FAQ</h1>
    <a href="#" class="btn-primary px-4 py-2 rounded mb-4" onclick="openModal()">Tambah FAQ</a>
    <table class="w-full bg-white shadow-md rounded">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-4">Pertanyaan</th>
                <th class="p-4">Jawaban</th>
                <th class="p-4">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($faqs as $faq)
            <tr>
                <td class="p-4">{{ $faq->pertanyaan }}</td>
                <td class="p-4">{{ Str::limit($faq->jawaban, 50) }}</td>
                <td class="p-4">
                    <button class="btn-primary px-4 py-2 rounded" onclick="editFaq({{ $faq->id }})">Edit</button>
                    <button class="bg-red-500 text-white px-4 py-2 rounded ml-2" onclick="deleteFaq({{ $faq->id }})">Hapus</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal Tambah/Edit FAQ -->
    <div id="faqModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 w-96">
            <h2 class="text-xl font-bold mb-4" id="modalTitle">Tambah FAQ</h2>
            <form id="faqForm" action="/admin/faqs" method="POST">
                @csrf
                <input type="hidden" id="faqId" name="id">
                <label class="block mb-2">Pertanyaan:</label>
                <input type="text" name="pertanyaan" id="pertanyaan" class="w-full p-2 border rounded mb-4" required>
                <label class="block mb-2">Jawaban:</label>
                <textarea name="jawaban" id="jawaban" class="w-full p-2 border rounded mb-4" required></textarea>
                <button type="submit" class="btn-primary px-4 py-2 rounded">Simpan</button>
                <button type="button" onclick="closeModal()" class="ml-2 px-4 py-2 border rounded">Batal</button>
            </form>
        </div>
    </div>
</div>
<script>
function openModal() {
    document.getElementById('faqModal').classList.remove('hidden');
    document.getElementById('modalTitle').textContent = 'Tambah FAQ';
    document.getElementById('faqForm').action = '/admin/faqs';
    document.getElementById('faqId').value = '';
    document.getElementById('pertanyaan').value = '';
    document.getElementById('jawaban').value = '';
}

function editFaq(id) {
    fetch(`/admin/faqs/${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('faqModal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = 'Edit FAQ';
            document.getElementById('faqForm').action = `/admin/faqs/${id}`;
            document.getElementById('faqId').value = data.faq.id;
            document.getElementById('pertanyaan').value = data.faq.pertanyaan;
            document.getElementById('jawaban').value = data.faq.jawaban;
        });
}

function deleteFaq(id) {
    if (confirm('Hapus FAQ ini?')) {
        fetch(`/admin/faqs/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
            .then(response => response.json())
            .then(data => location.reload());
    }
}

function closeModal() {
    document.getElementById('faqModal').classList.add('hidden');
}
</script>
@endsection