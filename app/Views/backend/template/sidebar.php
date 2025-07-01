<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('/'); ?>">
        <div class="sidebar-brand-icon">
            <i class="fas fa-store"></i>
        </div>
        <div class="sidebar-brand-text mx-3">RX3-MANTAN</div>
    </a>

    <hr class="sidebar-divider my-0">

    <!-- Dashboard -->
    <?php $uri = service('uri'); ?>
    <li class="nav-item <?= $uri->getSegment(1) == '' ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('/'); ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">Sidebar Menu</div>

    <!-- Barang & Kategori (Collapse) -->
    <?php $isBarang = in_array($uri->getSegment(1), ['barang', 'kategori']); ?>
    <li class="nav-item <?= $isBarang ? 'active' : '' ?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBarang"
            aria-expanded="<?= $isBarang ? 'true' : 'false' ?>" aria-controls="collapseBarang">
            <i class="fas fa-box"></i>
            <span>Barang</span>
        </a>
        <div id="collapseBarang" class="collapse <?= $isBarang ? 'show' : '' ?>" aria-labelledby="headingBarang" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Pilihan:</h6>
                <a class="collapse-item <?= $uri->getSegment(1) == 'barang' ? 'active' : '' ?>" href="<?= base_url('/barang'); ?>">Data Barang</a>
                <a class="collapse-item <?= $uri->getSegment(1) == 'kategori' ? 'active' : '' ?>" href="<?= base_url('/kategori'); ?>">Kategori Barang</a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">Transaksi</div>

    <!-- Transaksi (Collapse) -->
    <?php $isTransaksi = in_array($uri->getSegment(1), ['penjualan', 'riwayat_penjualan', 'pembelian', 'riwayat_pembelian']); ?>
    <li class="nav-item <?= $isTransaksi ? 'active' : '' ?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTransaksi"
            aria-expanded="<?= $isTransaksi ? 'true' : 'false' ?>" aria-controls="collapseTransaksi">
            <i class="fas fa-cash-register"></i>
            <span>Transaksi</span>
        </a>
        <div id="collapseTransaksi" class="collapse <?= $isTransaksi ? 'show' : '' ?>" aria-labelledby="headingTransaksi" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Pilihan:</h6>
                <a class="collapse-item <?= $uri->getSegment(1) == 'penjualan' ? 'active' : '' ?>" href="<?= base_url('/penjualan'); ?>">Penjualan</a>
                <a class="collapse-item <?= $uri->getSegment(1) == 'riwayat_penjualan' ? 'active' : '' ?>" href="<?= base_url('/riwayat_penjualan'); ?>">Riwayat Penjualan</a>
                <a class="collapse-item <?= $uri->getSegment(1) == 'pembelian' ? 'active' : '' ?>" href="<?= base_url('/pembelian'); ?>">Pembelian</a>
                <a class="collapse-item <?= $uri->getSegment(1) == 'riwayat_pembelian' ? 'active' : '' ?>" href="<?= base_url('/riwayat_pembelian'); ?>">Riwayat Pembelian</a>
            </div>
        </div>
    </li>

    <!-- Supplier (Single Menu) -->
    <li class="nav-item <?= $uri->getSegment(1) == 'supplier' ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('/supplier'); ?>">
            <i class="fas fa-truck"></i>
            <span>Supplier</span>
        </a>
    </li>

    <!-- Laporan (Single Menu) -->
    <li class="nav-item <?= $uri->getSegment(1) == 'laporan' ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('/laporan'); ?>">
            <i class="fas fa-file-alt"></i>
            <span>Laporan</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <!-- Kelola User (hanya untuk pemilik) -->
    <?php if (session()->get('role') === 'pemilik'): ?>

        <div class="sidebar-heading">Users</div>
        
        <?php $isUser = $uri->getSegment(1) == 'user'; ?>
        <li class="nav-item <?= $isUser ? 'active' : '' ?>">
            <a class="nav-link" href="<?= base_url('/users'); ?>">
                <i class="fas fa-users"></i>
                <span>Kelola User</span>
            </a>
        </li>
    <?php endif; ?>

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
