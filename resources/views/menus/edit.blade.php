@extends('layouts.master')

@section('page_title')
    Edit Menu - Bakoel Nonik Admin Dashboard
@endsection

@section('breadcrumb')
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Menu</h3>
                <p class="text-subtitle text-muted">Form untuk mengubah data menu.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav class="breadcrumb-header float-start float-lg-end" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Data Master</li>
                        <li class="breadcrumb-item"><a href="{{ route('menus.index') }}">Menu</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section class="section">
        <form action="{{ route('menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header">

                </div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="name">Nama Menu</label>
                        <input type="text" id="name" name="name" class="form-control" required
                            value="{{ old('name', $menu->name) }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="category_id">Kategori</label>
                        <select id="category_id" name="category_id" class="form-control" required>
                            <option value="" selected disabled hidden>-- Pilih Kategori --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ $menu->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="price">Harga</label>
                        <input type="number" id="price" name="price" class="form-control" required
                            value="{{ old('price', $menu->price) }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="description">Deskripsi</label>
                        <textarea id="description" name="description" class="form-control">{{ old('description', $menu->description) }}</textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="photo">Foto Menu</label><br>
                        @if ($menu->photo)
                            <img id="existingPreview" src="{{ asset('storage/' . $menu->photo) }}" alt="Foto Menu"
                                width="120" class="mb-2 d-block">
                        @endif
                        <img id="newPreview" src="#" alt="Preview Baru" width="120" class="mb-2 d-none">
                        <input type="file" id="photo" name="photo" class="form-control">
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" id="is_available" name="is_available" class="form-check-input"
                            {{ $menu->is_available ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_available">Tersedia</label>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <a href="{{ route('menus.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                </div>
            </div>
        </form>
    </section>
@endsection

@push('scripts')
    <script>
        document.getElementById('photo').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('newPreview');
            const existing = document.getElementById('existingPreview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                    if (existing) existing.classList.add('d-none');
                };
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('d-none');
                if (existing) existing.classList.remove('d-none');
            }
        });
    </script>
@endpush
