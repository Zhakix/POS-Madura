<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>RX3-MANTAN-dashboard</title>




</head>

<!-- Loading Indicator -->
<div id="globalLoading" style="display:none; position:fixed; z-index:9999; top:0; left:0; width:100vw; height:100vh; background:rgba(255,255,255,0.7); align-items:center; justify-content:center; display:flex;">
    <div class="spinner-border text-info" style="width:3rem; height:3rem;" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>

<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>
    <span class="navbar-text mr-3 font-weight-bold text-gray-700">
        Halo, <?= esc(session()->get('nama') ?? 'User') ?>!
    </span>
    <span id="topbarClock" class="mr-3 font-weight-bold" style="color:#0d9488; background:#f1f5f9; padding:6px 16px; border-radius:20px; letter-spacing:2px; font-size:1rem; box-shadow:0 1px 4px rgba(13,148,136,0.08);">
        00:00:00
    </span>
    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button"
                data-toggle="dropdown">
                <span class="d-none d-lg-inline text-gray-600 small mr-2">
                    <span class="badge badge-info"><?= esc(session()->get('role') ?? '') ?></span>
                    <?= esc(session()->get('nama') ?? 'User') ?>
                </span>
                <img class="img-profile rounded-circle"
                    src="<?= base_url('writable/uploads/user/' . (session()->get('foto') ?? 'default.png')) ?>"
                    width="32" height="32" style="object-fit:cover;">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                <a class="dropdown-item" href="<?= base_url('profile') ?>">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>

    </ul>

</nav>
<!-- End of Topbar -->