@extends('layouts.app')

@section('title', 'FAQ - Dishine')

@section('content')
<div class="container mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold text-center mb-8">Pertanyaan yang Sering Diajukan (FAQ)</h1>
    <div class="space-y-4">
        @foreach($faqs as $faq)
        <div class="bg-white rounded-lg shadow-md p-4">
            <h3 class="text-xl font-semibold mb-2">{{ $faq->pertanyaan }}</h3>
            <p class="text-gray-700">{{ $faq->jawaban }}</p>
        </div>
        @endforeach
    </div>
</div>
@endsection