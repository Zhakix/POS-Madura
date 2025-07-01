<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>RX3-MANTAN-dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="/Assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="/Assets/css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?= $this->include('backend/template/sidebar'); ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Top Nav bar -->
                <?= $this->include('backend/template/header'); ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Heading -->
                    <div class="mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <p class="text-sm text-gray-500 mt-1">Ringkasan aktivitas toko Anda</p>
                    </div>

                    <!-- Stats Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Produk
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= esc($totalProduk) ?></div>
                                    <div class="mt-2 text-xs text-success-700">
                                        <?= $stokMenipis ? count($stokMenipis) . ' barang stok hampir habis' : 'Semua stok tersedia' ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Penjualan Hari
                                        Ini
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= esc($penjualanHariIni) ?></div>
                                    <div class="mt-2 text-xs text-success-700">
                                        Total: Rp <?= number_format($pendapatanHariIni, 0, ',', '.') ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Keuntungan Hari
                                        Ini
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($keuntunganHariIni, 0, ',', '.') ?></div>
                                    <div class="mt-2 text-xs text-success-700">
                                        <?= $penjualanHariIni ?> transaksi
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pembelian Stok
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= esc($totalPembelianHariIni) ?>
                                    </div>
                                    <div class="mt-2 text-xs text-success-700">
                                        Terakhir: <?= !empty($pembelianTerakhir) ? date('d M Y', strtotime($pembelianTerakhir)) : '-' ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts and Stok Menipis -->
                    <div class="row">
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow h-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <span>Produk Terlaris</span>
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                <div class="card-body">
                                    <canvas id="produkTerlarisChart" style="height:300px"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow h-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <span>Stok Hampir Habis</span>
                                    <i class="fas fa-exclamation-triangle text-warning"></i>
                                </div>
                                <div class="card-body">
                                    <?php if (!empty($stokMenipis)): ?>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr class="bg-light text-gray-700">
                                                        <th style="background-color: #f8f9fc;">Nama Barang</th>
                                                        <th style="background-color: #f8f9fc;">Stok</th>
                                                        <th style="background-color: #f8f9fc;">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($stokMenipis as $barang): ?>
                                                        <tr>
                                                            <td><?= esc($barang['nama_barang']) ?></td>
                                                            <td><?= esc($barang['stok']) ?></td>
                                                            <td>
                                                                <span class="badge badge-<?= $barang['stok'] <= 0 ? 'danger' : 'warning' ?>">
                                                                    <?= $barang['stok'] <= 0 ? 'Habis' : 'Hampir Habis' ?>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center text-muted py-5">
                                            <i class="fas fa-sparkles fa-2x mb-2"></i>
                                            <div>Semua stok barang aman</div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- End of Main Content -->

                <!-- Footer -->
                <?= $this->include('backend/template/footer'); ?>

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

    </div>
    <!-- End of Page Wrapper -->
    <script>
        // Chart Produk Terlaris
        var ctx = document.getElementById('produkTerlarisChart').getContext('2d');
        var produkLabels = <?= json_encode(array_column($produkTerlaris ?? [], 'nama_barang')) ?>;
        var produkData = <?= json_encode(array_column($produkTerlaris ?? [], 'jumlah')) ?>;
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: produkLabels,
                datasets: [{
                    label: 'Qty Terjual',
                    data: produkData,
                    backgroundColor: 'rgba(13, 148, 136, 0.6)',
                    borderColor: 'rgba(13, 148, 136, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Produk Terlaris'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>