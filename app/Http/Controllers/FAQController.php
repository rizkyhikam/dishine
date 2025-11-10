<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FAQController extends Controller
{
    public function __construct()
    {
        // Hanya admin yang bisa tambah, ubah, hapus
        $this->middleware('role:admin')->only(['store', 'update', 'destroy']);
    }

    /**
     * Menampilkan semua FAQ (akses publik)
     */
    public function index()
    {
        $faqs = Faq::latest()->get();
        return response()->json(['faqs' => $faqs], 200);
    }

    /**
     * Menyimpan FAQ baru (admin only)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pertanyaan' => 'required|string|max:255',
            'jawaban' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $faq = Faq::create($request->only(['pertanyaan', 'jawaban']));
        return response()->json(['message' => 'FAQ berhasil ditambahkan', 'faq' => $faq], 201);
    }

    /**
     * Mengupdate FAQ (admin only)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'pertanyaan' => 'required|string|max:255',
            'jawaban' => 'required|string',
        ]);

        $faq = Faq::findOrFail($id);
        $faq->update($request->only(['pertanyaan', 'jawaban']));

        return response()->json(['message' => 'FAQ berhasil diperbarui', 'faq' => $faq], 200);
    }

    /**
     * Menghapus FAQ (admin only)
     */
    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();

        return response()->json(['message' => 'FAQ berhasil dihapus'], 200);
    }
}
