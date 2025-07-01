<html lang="en">

<?= $this->include('backend/template/css'); ?>

<body id="page-top">
    <div id="wrapper">
        <?= $this->include('backend/template/sidebar'); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?= $this->include('backend/template/header'); ?>
                <div class="container-fluid">

                    <!-- Header & Filter -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="mb-0">Laporan Penjualan</h3>
                    </div>

                    <!-- Card Filter Laporan -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="<?= base_url('laporan/filter') ?>" method="POST" class="form-row align-items-end">
                                <div class="col-md-3 mb-2">
                                    <label for="report_type" class="small font-weight-bold">Tipe Laporan</label>
                                    <select name="report_type" id="report_type" class="form-control">
                                        <option value="daily" <?= $report_type == 'daily' ? 'selected' : '' ?>>Harian
                                        </option>
                                        <option value="weekly" <?= $report_type == 'weekly' ? 'selected' : '' ?>>Mingguan
                                        </option>
                                        <option value="monthly" <?= $report_type == 'monthly' ? 'selected' : '' ?>>Bulanan
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label for="start_date" class="small font-weight-bold">Dari Tanggal</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control"
                                        value="<?= esc($start_date) ?>" required>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label for="end_date" class="small font-weight-bold">Sampai Tanggal</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control"
                                        value="<?= esc($end_date) ?>" required>
                                </div>
                                <div class="col-md-3 mb-2 d-flex">
                                    <button type="submit" class="btn btn-primary mr-2 flex-grow-1">Tampilkan</button>
                                    <button type="button" class="btn btn-outline-success flex-grow-1" id="exportCSV">
                                        <i class="fas fa-file-csv"></i> Export CSV
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Penjualan</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        Rp <?= number_format($total_penjualan ?? 0, 0, ',', '.') ?>
                                    </div>
                                    <div class="text-xs text-muted mt-2"><?= $jumlah_transaksi ?? 0 ?> transaksi</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Total Pembelian</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        Rp <?= number_format($total_pembelian ?? 0, 0, ',', '.') ?>
                                    </div>
                                    <div class="text-xs text-muted mt-2"><?= $jumlah_pembelian ?? 0 ?> transaksi</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Keuntungan Bersih</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        Rp <?= number_format($keuntungan ?? 0, 0, ',', '.') ?>
                                    </div>
                                    <div class="text-xs text-muted mt-2">
                                        Margin: <?= ($total_penjualan ?? 0) > 0 ? round(($keuntungan / $total_penjualan) * 100) : 0 ?>%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart Section -->
                    <div class="row mb-4">
                        <div class="col-lg-6 mb-3">
                            <div class="card shadow">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <span>Penjualan per Hari</span>
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                <div class="card-body">
                                    <canvas id="barChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="card shadow">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <span>Penjualan per Kategori</span>
                                    <i class="fas fa-chart-pie"></i>
                                </div>
                                <div class="card-body">
                                    <canvas id="pieChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Table -->
                    <div class="card shadow mb-4">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">Detail Penjualan</h6>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Kasir</th>
                                        <th>Metode Pembayaran</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($penjualan)): ?>
                                        <?php foreach ($penjualan as $row): ?>
                                            <tr>
                                                <td><?= date('d M Y H:i', strtotime($row['tanggal_penjualan'])) ?></td>
                                                <td><?= esc($row['nama_kasir'] ?? '-') ?></td>
                                                <td>
                                                    <span class="badge badge-<?= $row['metode_pembayaran'] === 'cash' ? 'success' : 'primary' ?>">
                                                        <?= $row['metode_pembayaran'] === 'cash' ? 'Tunai' : 'Qris' ?>
                                                    </span>
                                                </td>
                                                <td class="text-right">Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center">Tidak ada data penjualan untuk periode ini
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                                <?php if (!empty($penjualan)): ?>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-right font-weight-bold">Total Penjualan:</td>
                                            <td class="text-right font-weight-bold">Rp <?= number_format($total_penjualan ?? 0, 0, ',', '.') ?></td>
                                        </tr>
                                    </tfoot>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
            <?= $this->include('backend/template/footer'); ?>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Contoh data chart, ganti dengan data PHP jika perlu
        var barCtx = document.getElementById('barChart').getContext('2d');
        var barChart = new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($labels_harian ?? []) ?>,
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: <?= json_encode($data_harian ?? []) ?>,
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
                        text: 'Penjualan per Hari'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        var pieCtx = document.getElementById('pieChart').getContext('2d');
        var pieChart = new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: <?= json_encode($labels_kategori ?? []) ?>,
                datasets: [{
                    data: <?= json_encode($data_kategori ?? []) ?>,
                    backgroundColor: [
                        'rgba(13, 148, 136, 0.6)',
                        'rgba(249, 115, 22, 0.6)',
                        'rgba(245, 158, 11, 0.6)',
                        'rgba(34, 197, 94, 0.6)',
                        'rgba(99, 102, 241, 0.6)'
                    ],
                    borderColor: [
                        'rgba(13, 148, 136, 1)',
                        'rgba(249, 115, 22, 1)',
                        'rgba(245, 158, 11, 1)',
                        'rgba(34, 197, 94, 1)',
                        'rgba(99, 102, 241, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    title: {
                        display: true,
                        text: 'Penjualan per Kategori'
                    }
                }
            }
        });

        // Export CSV
        document.getElementById('exportCSV').addEventListener('click', function() {
            // Ambil nilai filter
            var start = document.getElementById('start_date').value;
            var end = document.getElementById('end_date').value;
            // Redirect ke endpoint export_csv dengan parameter
            window.open('<?= base_url('laporan/export_csv') ?>?start_date=' + start + '&end_date=' + end, '_blank');
        });
    </script>
</body>

</html>