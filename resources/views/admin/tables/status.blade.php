@extends('layouts.master')

@section('page_title', 'Status Meja - Bakoel Nonik Admin Dashboard')

@section('breadcrumb')
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Status Meja</h3>
                <p class="text-subtitle text-muted">
                    Menampilkan ketersediaan meja saat ini.
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Data Master</li>
                        <li class="breadcrumb-item active" aria-current="page">Status Meja</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container mt-4">
        <div class="row">
            @foreach ($tables as $table)
                <div class="col-md-3">
                    <div class="card text-white mb-4"
                        style="background-color: {{ $table->status == 'available' ? '#4CAF50' : '#D32F2F' }};">
                        <div class="card-body text-center">
                            <h5 style="color: {{ $table->status == 'available' ? '#29261F' : '#FCFAF3' }};">Meja {{ $table->table_number }}</h5>
                            <p>Status: {{ $table->status == 'available' ? 'Tersedia' : 'Digunakan' }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
