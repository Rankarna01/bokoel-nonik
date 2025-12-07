@extends('layouts.master')

@section('page_title')
    Menu Restoran - Bakoel Nonik Admin Dashboard
@endsection

@section('breadcrumb')
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Menu</h3>
                <p class="text-subtitle text-muted">
                    Menampilkan daftar menu makanan dan minuman.
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Data Master</li>
                        <li class="breadcrumb-item active" aria-current="page">Menu</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title"></h4>
                <a href="{{ route('menus.create') }}" class="btn btn-primary">Tambah Menu</a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Ketersediaan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($menus as $menu)
                            <tr>
                                <td>
                                    @if ($menu->photo)
                                        <img src="{{ asset('storage/' . $menu->photo) }}" alt="{{ $menu->name }}" width="80">
                                    @else
                                        <span class="text-muted">Tidak ada foto</span>
                                    @endif
                                </td>
                                <td>{{ $menu->name }}</td>
                                <td>{{ $menu->category->name ?? '-' }}</td>
                                <td>Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-{{ $menu->is_available ? 'success' : 'danger' }}">
                                        {{ $menu->is_available ? 'Tersedia' : 'Habis' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('menus.edit', $menu->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('menus.destroy', $menu->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        let table1 = document.querySelector('#table1');
        let dataTable = new simpleDatatables.DataTable(table1);
    </script>
@endpush
