@extends('layouts.master')

@section('page_title', 'Edit Meja')

@section('breadcrumb')
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Meja</h3>
                <p class="text-subtitle text-muted">
                    Form untuk mengubah meja dan QR code untuk pemesanan.
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
            <form action="{{ route('tables.update', $table->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="table_number" class="form-label">Nomor Meja</label>
                    <input type="text" name="table_number" class="form-control"
                        value="{{ old('table_number', $table->table_number) }}" required>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" class="form-control" required>
                        <option value="available" {{ $table->status == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="occupied" {{ $table->status == 'occupied' ? 'selected' : '' }}>Occupied</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>QR Saat Ini</label><br>
                    <img src="{{ asset('storage/' . $table->qr_code) }}" alt="QR Code" width="150">
                    <p class="text-muted mt-1">QR akan digenerate ulang jika nomor meja diubah.</p>
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('tables.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection
