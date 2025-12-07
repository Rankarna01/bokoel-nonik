@extends('layouts.master')

@section('page_title')
    Tambah Menu - Bakoel Nonik Admin Dashboard
@endsection

@section('breadcrumb')
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tambah Menu</h3>
                <p class="text-subtitle text-muted">Form untuk menambahkan data menu baru.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav class="breadcrumb-header float-start float-lg-end" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Data Master</li>
                        <li class="breadcrumb-item"><a href="{{ route('menus.index') }}">Menu</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section class="section">
        <form action="{{ route('menus.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-header">

                </div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="name">Nama Menu</label>
                        <input type="text" id="name" name="name" class="form-control" required value="{{ old('name') }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="category_id">Kategori</label>
                        <select id="category_id" name="category_id" class="form-control" required>
                            <option value="" selected disabled hidden>-- Pilih Kategori --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="price">Harga</label>
                        <input type="number" id="price" name="price" class="form-control" required value="{{ old('price') }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="description">Deskripsi</label>
                        <textarea id="description" name="description" class="form-control">{{ old('description') }}</textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="photo">Foto Menu</label>
                        <input type="file" id="photo" name="photo" class="form-control">
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" id="is_available" name="is_available" class="form-check-input" checked>
                        <label class="form-check-label" for="is_available">Tersedia</label>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <a href="{{ route('menus.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </section>
@endsection
