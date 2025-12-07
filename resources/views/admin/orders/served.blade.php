@extends('layouts.master')

@section('page_title', 'Pembayaran Pesanan - Bakoel Nonik Admin Dashboard')

@section('breadcrumb')
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Pembayaran Pesanan</h3>
                <p class="text-subtitle text-muted">
                    Menampilkan pesanan yang harus dibayar.
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Data Master</li>
                        <li class="breadcrumb-item active" aria-current="page">Pembayaran</li>
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
                <div class="card-header bg-success text-white d-flex justify-content-between">
                    <span>Meja: {{ $order->table->table_number }}</span>
                    <span>Total: <strong>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</strong></span>
                </div>
                <div class="card-body">
                    <ul class="list-group mb-3">
                        @foreach ($order->items as $item)
                            <li class="list-group-item d-flex justify-content-between">
                                {{ $item->menu->name }} x{{ $item->quantity }}
                                <span>Rp{{ number_format($item->quantity * $item->price, 0, ',', '.') }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <form method="POST" action="{{ route('admin.orders.pay', $order) }}"
                        onsubmit="return confirm('Yakin ingin menyelesaikan pembayaran?')">
                        @csrf

                        <div class="mb-3">
                            <label for="payment_method_{{ $order->id }}" class="form-label">Metode Pembayaran</label>
                            <select name="payment_method" id="payment_method_{{ $order->id }}"
                                class="form-select payment-method" data-order-id="{{ $order->id }}"
                                data-total="{{ $order->total_amount }}" required>
                                <option value="">-- Pilih --</option>
                                <option value="cash">Tunai</option>
                                <option value="qr">QRIS / eWallet</option>
                                <option value="debit">Kartu Debit</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="amount_{{ $order->id }}" class="form-label">Jumlah Bayar (Rp)</label>
                            <input type="number" step="100" min="{{ $order->total_amount }}" name="amount"
                                id="amount_{{ $order->id }}" class="form-control" value="{{ $order->total_amount }}"
                                required>
                        </div>

                        <div id="qris-preview-{{ $order->id }}" class="text-center mb-3" style="display: none;">
                            <p>Scan QR untuk pembayaran:</p>
                            <div id="qris-box-{{ $order->id }}"></div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Konfirmasi & Selesaikan Pembayaran</button>
                    </form>
                </div>
            </div>
        @endforeach

        @if ($orders->isEmpty())
            <div class="alert alert-info text-center">
                Tidak ada pesanan yang menunggu pembayaran.
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Sukses!',
                text: '{{ session('success') }}',
                timer: 2000,
                showConfirmButton: false,
            });
        </script>
    @endif

    {{-- Include QRCode.js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script>
        window.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.payment-method').forEach(select => {
                select.addEventListener('change', function() {
                    const orderId = this.dataset.orderId;
                    const selected = this.value;
                    const total = this.dataset.total;
                    const qrContainer = document.getElementById('qris-box-' + orderId);
                    const previewWrapper = document.getElementById('qris-preview-' + orderId);
                    const cardHeader = this.closest('.card').querySelector('.card-header')
                        .innerText;
                    const meja = cardHeader.match(/Meja:\s?(\d+)/)?.[1] ?? 'Unknown';

                    console.log('Selected Payment:', selected, 'OrderID:', orderId, 'Total:',
                        total);

                    // Reset
                    qrContainer.innerHTML = '';
                    previewWrapper.style.display = 'none';

                    if (selected === 'qr') {
                        const qrText =
                            `Pembayaran QRIS\nMeja: ${meja}\nTotal: Rp${parseInt(total).toLocaleString('id-ID')}`;
                        console.log('QR Text:', qrText);

                        // Generate QR
                        new QRCode(qrContainer, {
                            text: qrText,
                            width: 200,
                            height: 200,
                        });

                        previewWrapper.style.display = 'block';
                    }
                });
            });
        });
    </script>
@endpush
