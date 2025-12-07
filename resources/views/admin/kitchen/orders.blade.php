@extends('layouts.master')

@section('page_title', 'Orderan Aktif - Bakoel Nonik Admin Dashboard')


@section('breadcrumb')
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Orderan Aktif</h3>
                <p class="text-subtitle text-muted">
                    Menampilkan orderan aktif saat ini.
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Data Master</li>
                        <li class="breadcrumb-item active" aria-current="page">Status Pesanan</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container mt-4">
        @foreach ($orders as $order)
            <div class="card mb-4">
                <div class="card-header">
                    Meja: {{ $order->table->table_number }} | Status: {{ ucfirst($order->status) }}
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach ($order->items as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $item->menu->name }}</strong> x{{ $item->quantity }}
                                    <br>
                                    <small>Catatan: {{ $item->note ?? '-' }}</small>
                                </div>
                                <form method="POST" action="{{ route('admin.kitchen.order-items.update', $item) }}">
                                    @csrf
                                    <select class="form-select item-status-select" data-item-id="{{ $item->id }}"
                                        data-order-id="{{ $order->id }}">
                                        <option value="waiting" {{ $item->status == 'waiting' ? 'selected' : '' }}>Menunggu
                                        </option>
                                        <option value="cooking" {{ $item->status == 'cooking' ? 'selected' : '' }}>Dimasak
                                        </option>
                                        <option value="done" {{ $item->status == 'done' ? 'selected' : '' }}>Selesai
                                        </option>
                                    </select>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.item-status-select').forEach(select => {
                select.addEventListener('change', function() {
                    const itemId = this.dataset.itemId;
                    const orderId = this.dataset.orderId;
                    const newStatus = this.value;

                    fetch(`{{ route('admin.kitchen.order-items.update', ':id') }}`.replace(':id',
                            itemId), {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                status: newStatus,
                                order_id: orderId
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: data.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Gagal', data.message, 'error');
                            }
                        })
                        .catch(() => {
                            Swal.fire('Error', 'Terjadi kesalahan saat mengupdate.', 'error');
                        });
                });
            });
        });
    </script>
@endpush
