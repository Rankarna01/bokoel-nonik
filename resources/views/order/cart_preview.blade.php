@if (!empty($cart))
    <div class="card mt-5 border-info shadow-sm" id="cart-preview">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-white">
                <i class="bi bi-cart4 me-2"></i>Keranjang ({{ $totalQty }} item)
            </h5>
        </div>
        <div class="card-body p-0">
            <ul class="list-group list-group-flush">
                @foreach ($cart as $menuId => $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            {{ $item['name'] }} <span class="text-muted">× {{ $item['qty'] }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span
                                class="fw-semibold text-dark">Rp{{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</span>
                            <button
                                class="btn btn-sm btn-outline-danger remove-from-cart d-flex align-items-center justify-content-center"
                                data-menu-id="{{ $menuId }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-top fw-bold bg-light">
                <span>Total Harga:</span>
                <span>Rp{{ number_format($totalPrice, 0, ',', '.') }}</span>
            </div>

            <div class="text-end px-3 py-3">
                <form action="{{ route('order.checkout', $tableNumber) }}" method="POST">
                    @csrf
                    <button class="btn btn-success d-flex align-items-center justify-content-center gap-1"
                        type="button" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                        <i class="bi bi-bag-check"></i> Checkout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Checkout -->
    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('order.checkout', $tableNumber) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="checkoutModalLabel">Konfirmasi Pesanan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin memesan <strong>{{ $totalQty }}</strong> item dengan total
                        <strong>Rp{{ number_format($totalPrice, 0, ',', '.') }}</strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary d-flex align-items-center justify-content-center gap-1" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-success d-flex align-items-center justify-content-center gap-1">
                            Ya, Pesan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endif
