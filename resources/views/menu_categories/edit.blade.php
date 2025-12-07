@extends('layouts.master')

@section('page_title', 'Edit Kategori Menu')

@section('breadcrumb')
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Kategori Menu</h3>
                <p class="text-subtitle text-muted">
                    Form utuk mengubah kategori menu.
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
            <form action="{{ route('menu-categories.update', $menu_category->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Kategori</label>
                    <input type="text" name="name" value="{{ $menu_category->name }}" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success">Perbarui</button>
                <a href="{{ route('menu-categories.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection
