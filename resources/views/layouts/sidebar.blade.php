<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    <a href="/dashboard">
                        <img src="{{ asset('master/logo.svg') }}" alt="Logo"
                            style="max-width: 100%; height: auto;" srcset="">
                    </a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block">
                        <i class="bi bi-x bi-middle"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu Utama</li>

                <!-- Dashboard -->
                <li class="sidebar-item {{ Request::is('dashboard') ? 'active' : '' }}">
                    <a href="/dashboard" class="sidebar-link">
                        <i class="bi bi-house-door-fill"></i>
                        <span>Beranda</span>
                    </a>
                </li>

                <li class="sidebar-title">Data Master</li>

                <!-- Table / Meja -->
                <li class="sidebar-item {{ Request::is('tables*') ? 'active' : '' }}">
                    <a href="/tables" class="sidebar-link">
                        <i class="bi bi-grid-fill"></i>
                        <span>Meja</span>
                    </a>
                </li>

                <!-- Menu -->
                <li class="sidebar-item has-sub {{ Request::is('menu*') ? 'active' : '' }}">
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-list-ul"></i>
                        <span>Menu Makanan</span>
                    </a>
                    <ul class="submenu">
                        <li class="submenu-item {{ Request::is('menu-categories') ? 'active' : '' }}">
                            <a href="/menu-categories">Kategori Menu</a>
                        </li>
                        <li class="submenu-item {{ Request::is('menus') ? 'active' : '' }}">
                            <a href="/menus">Menu</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-title">Transaksi</li>

                <!-- Order -->
                <li class="sidebar-item {{ Request::is('admin/tables/*') ? 'active' : '' }}">
                    <a href="/admin/tables/status" class="sidebar-link">
                        <i class="bi bi-receipt-cutoff"></i>
                        <span>Status Meja</span>
                    </a>
                </li>

                <!-- Payment -->
                <li class="sidebar-item {{ Request::is('admin/kitchen/*') ? 'active' : '' }}">
                    <a href="/admin/kitchen/orders" class="sidebar-link">
                        <i class="bi bi-cash-stack"></i>
                        <span>Status Pesanan</span>
                    </a>
                </li>

                <li class="sidebar-item {{ Request::is('admin/orders/*') ? 'active' : '' }}">
                    <a href="/admin/orders/served" class="sidebar-link">
                        <i class="bi bi-cash-stack"></i>
                        <span>Pembayaran</span>
                    </a>
                </li>

                <li class="sidebar-title">Pengguna</li>

                <!-- Users -->
                <li class="sidebar-item {{ Request::is('users*') ? 'active' : '' }}">
                    <a href="/javascript:void(0)" class="sidebar-link">
                        <i class="bi bi-people-fill"></i>
                        <span>Pengguna</span>
                    </a>
                </li>

                <li class="sidebar-title">Laporan</li>

                <!-- Reports -->
                <li class="sidebar-item {{ Request::is('reports*') ? 'active' : '' }}">
                    <a href="javascript:void(0)" class="sidebar-link">
                        <i class="bi bi-bar-chart-fill"></i>
                        <span>Laporan</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
