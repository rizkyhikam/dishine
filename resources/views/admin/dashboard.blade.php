@extends('layouts.admin')

@section('content')
    <h1 class="mb-4">Dashboard Admin</h1>

    @if(session('notification'))
        <div class="alert alert-warning">
            {{ session('notification') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-3">
            <div class="card text-center mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Produk</h5>
                    <h2>{{ $totalProducts }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Pelanggan</h5>
                    <h2>{{ $totalUsers }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Reseller</h5>
                    <h2>{{ $totalResellers }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total FAQ</h5>
                    <h2>{{ $totalFaqs }}</h2>
                </div>
            </div>
        </div>
    </div>
@endsection
