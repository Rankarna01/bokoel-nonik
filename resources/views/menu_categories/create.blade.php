@extends('layouts.master')

@section('page_title', 'Tambah Kategori Menu')

@section('breadcrumb')
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tambah Kategori Menu</h3>
                <p class="text-subtitle text-muted">
                    Form untuk menambah kategori menu.
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Data Master</li>
                        <li class="breadcrumb-item active" aria-current="page">Kategori Menu</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
        </div>
        <div class="card-body">
            <form action="{{ route('menu-categories.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Kategori</label>
                    <input type="text" name="name" class="form-control" required placeholder="Contoh: Minuman">
                </div>
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="{{ route('menu-categories.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
@endsection
