@php
    // Map status pesanan utama ke teks dan warna
    $statusOrderMap = [
        'pending' => ['label' => 'Menunggu Konfirmasi Dapur', 'color' => 'secondary'],
        'in_process' => ['label' => 'Sedang Dimasak', 'color' => 'info'],
        'served' => ['label' => 'Sudah Disajikan', 'color' => 'success'],
        'paid' => ['label' => 'Sudah Dibayar', 'color' => 'dark'],
    ];

    $statusItemMap = [
        'waiting' => ['label' => 'Menunggu Dimasak', 'color' => 'warning'],
        'cooking' => ['label' => 'Sedang Dimasak', 'color' => 'primary'],
        'done' => ['label' => 'Selesai', 'color' => 'success'],
    ];

    $statusInfo = $statusOrderMap[$order->status] ?? ['label' => strtoupper($order->status), 'color' => 'secondary'];
@endphp

@extends('layouts.one_column')

@section('page_title', 'Monitor Pesanan - Meja ' . $order->table->table_number)

@section('title', 'Monitor Pesanan - Meja ' . $order->table->table_number)

@section('content')
    <div class="card mt-5 border-{{ $statusInfo['color'] }}" id="status-container">
        <div class="card-header bg-{{ $statusInfo['color'] }} text-white">
            <h5>Status Pesanan : <strong>{{ $statusInfo['label'] }}</strong></h5>
        </div>
        <div class="card-body">
            <ul class="list-group mt-4">
                @foreach ($order->items as $item)
                    @php
                        $itemInfo = $statusItemMap[$item->status] ?? ['label' => ucfirst($item->status), 'color' => 'secondary'];
                    @endphp
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            {{ $item->menu->name }} x {{ $item->quantity }}
                        </div>
                        <span class="badge bg-{{ $itemInfo['color'] }}">
                            {{ $itemInfo['label'] }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const orderId = "{{ $order->id }}";

        function refreshStatus() {
            fetch(`/order/status/${orderId}/ajax`)
                .then(res => res.text())
                .then(html => {
                    document.getElementById('status-container').innerHTML = html;
                });
        }

        // Auto-refresh setiap 10 detik
        setInterval(refreshStatus, 10000);
    </script>
@endpush
