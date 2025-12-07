@extends('layouts.master')

@section('page_title')
    Meja Restoran - Bakoel Nonik Admin Dashboard
@endsection

@section('breadcrumb')
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Meja</h3>
                <p class="text-subtitle text-muted">
                    Menampilkan daftar meja dan QR code untuk pemesanan.
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Data Master</li>
                        <li class="breadcrumb-item active" aria-current="page">Meja</li>
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
                <a href="{{ route('tables.create') }}" class="btn btn-primary">Tambah Meja</a>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>No Meja</th>
                            <th>Status</th>
                            <th>QR Code</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tables as $table)
                            <tr>
                                <td>{{ $table->table_number }}</td>
                                <td>
                                    <span class="badge bg-{{ $table->status === 'available' ? 'success' : 'danger' }}">
                                        {{ ucfirst($table->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($table->qr_code)
                                        <img src="{{ asset('storage/' . $table->qr_code) }}" alt="QR Code" width="100">
                                    @else
                                        <span class="text-muted">Belum ada QR</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('tables.edit', $table->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('tables.destroy', $table->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus meja ini?')">
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
