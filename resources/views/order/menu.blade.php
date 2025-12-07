@extends('layouts.one_column')

@section('page_title')
    Daftar Menu - Bakoel Nonik Admin Dashboard
@endsection

@section('title', 'Daftar Menu')

@section('content')
    <div class="card mt-5">
        <div class="card-header">
            <h4 class="card-title">Nomor Meja : {{ $tableName }}</h4>
        </div>
        <div class="card-body">

            {{-- Loop per kategori --}}
            @foreach ($categories as $category)
                <div class="p-3 mb-4 rounded shadow-sm" style="background-color: #f8f9fa;">
                    <h4 class="mb-3 text-primary border-bottom pb-2">{{ $category->name }}</h4>
                    <div class="row g-4">
                        @foreach ($menus->where('category_id', $category->id) as $menu)
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm">
                                    @if ($menu->photo)
                                        <img src="{{ asset('storage/' . $menu->photo) }}" class="card-img-top"
                                            alt="{{ $menu->name }}" style="height: 200px; object-fit: cover;">
                                    @else
                                        <img src="{{ asset('images/no-image.png') }}" class="card-img-top" alt="No Image"
                                            style="height: 200px; object-fit: cover;">
                                    @endif

                                    <div class="card-body d-flex flex-column">
                                        <h6 class="text-muted mb-1">{{ $menu->category->name ?? '-' }}</h6>
                                        <h5 class="card-title">{{ $menu->name }}</h5>
                                        <p class="card-text text-muted mb-2">
                                            {{ $menu->description ?? 'Tidak ada deskripsi.' }}</p>
                                        <div class="mt-auto">
                                            <form class="add-to-cart-form" data-menu-id="{{ $menu->id }}">
                                                @csrf
                                                <input type="hidden" name="table_number" value="{{ $tableNumber }}">
                                                <button type="submit" class="btn btn-sm btn-primary w-100">Tambah ke
                                                    Keranjang</button>
                                            </form>
                                        </div>
                                    </div>

                                    <div
                                        class="card-footer d-flex justify-content-between align-items-center bg-white border-top">
                                        <strong>Rp{{ number_format($menu->price, 0, ',', '.') }}</strong>
                                        <span class="badge {{ $menu->is_available ? 'bg-success' : 'bg-danger' }}">
                                            {{ $menu->is_available ? 'Tersedia' : 'Habis' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div id="cart-preview-container" class="mt-4"></div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tableNumber = "{{ $tableNumber }}";

            function loadCartPreview() {
                fetch(`/cart/preview/${tableNumber}`)
                    .then(res => res.text())
                    .then(html => {
                        document.getElementById('cart-preview-container').innerHTML = html;
                        bindRemoveCartEvents();
                    });
            }

            function showSuccessAlert() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Menu berhasil ditambahkan ke keranjang!',
                    timer: 1500,
                    showConfirmButton: false
                });
            }

            function bindRemoveCartEvents() {
                document.querySelectorAll('.remove-from-cart').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const menuId = this.dataset.menuId;

                        Swal.fire({
                            title: 'Hapus Menu?',
                            text: "Apakah kamu yakin ingin menghapus menu ini dari keranjang?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Ya, hapus'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                fetch(`{{ route('cart.remove.item') }}`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        menu_id: menuId,
                                        table_number: tableNumber
                                    })
                                }).then(() => {
                                    loadCartPreview();
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Dihapus!',
                                        text: 'Menu berhasil dihapus dari keranjang.',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                });
                            }
                        });
                    });
                });
            }

            document.querySelectorAll('.add-to-cart-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const menuId = this.dataset.menuId;

                    fetch(`{{ route('cart.add.ajax') }}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            menu_id: menuId,
                            table_number: tableNumber
                        })
                    }).then(() => {
                        loadCartPreview();
                        showSuccessAlert(); // Sudah sesuai
                    });
                });
            });

            // Initial load
            loadCartPreview();
        });
    </script>
@endpush
