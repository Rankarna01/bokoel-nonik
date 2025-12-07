@extends('layouts.master')

@section('page_title', 'Tambah Meja Baru')

@section('breadcrumb')
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tambah Meja</h3>
                <p class="text-subtitle text-muted">
                    Form untuk menambah meja dan QR code untuk pemesanan.
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
    <div class="card">
        <div class="card-header">

        </div>
        <div class="card-body">
            <form action="{{ route('tables.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="table_number" class="form-label">Nomor Meja</label>
                    <input type="text" name="table_number" class="form-control" required placeholder="Misal: T11">
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" class="form-control" required>
                        <option value="available">Available</option>
                        <option value="occupied">Occupied</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="{{ route('tables.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection
