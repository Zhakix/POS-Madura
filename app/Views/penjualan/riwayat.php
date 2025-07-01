<html lang="en">

<?= $this->include('backend/template/css'); ?>

<body id="page-top">
    <div id="wrapper">
        <?= $this->include('backend/template/sidebar'); ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?= $this->include('backend/template/header'); ?>
                <div class="container-fluid">
                    <h3 class="mb-4">Riwayat Penjualan</h3>
                    <div class="card mb-4">
                        <div class="card-body">
                            <form id="filterForm" class="row g-3">
                                <div class="col-md-4 d-flex align-items-end">
                                    <input type="text" id="searchTerm" class="form-control" placeholder="Cari kasir atau total...">
                                </div>
                                <div class="col-md-3">
                                    <label for="startDate" class="form-label">Dari Tanggal</label>
                                    <input type="date" id="startDate" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="endDate" class="form-label">Sampai Tanggal</label>
                                    <input type="date" id="endDate" class="form-control">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-success w-100" id="btnExportCSV" disabled>Export CSV</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-rounded" id="salesTable">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Kasir</th>
                                        <th>Total</th>
                                        <th>Pembayaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data diisi via JS -->
                                </tbody>
                            </table>
                            <div id="emptyState" class="text-center text-muted my-4" style="display:none;">
                                Tidak ada riwayat penjualan.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Detail Penjualan (Bootstrap 4) -->
                <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="detailModalLabel">Detail Transaksi</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="modalDetailContent">
                                <!-- Isi detail transaksi via JS -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?= $this->include('backend/template/footer'); ?>
        </div>
    </div>

    <script>
        // Data dari backend (pastikan controller mengirim $sales, $users, $detailPenjualan)
        var sales = <?= json_encode($sales ?? []) ?>;
        var users = <?= json_encode($users ?? []) ?>;
        var detailPenjualan = <?= json_encode($detailPenjualan ?? []) ?>;

        function getUserById(id) {
            return users.find(u => u.id_user == id);
        }

        function getDetailByPenjualan(id_penjualan) {
            return detailPenjualan.filter(d => d.id_penjualan == id_penjualan);
        }

        function formatDate(dateString) {
            var date = new Date(dateString);
            return date.toLocaleString('id-ID', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function formatRupiah(angka) {
            return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
        }

        function renderTable() {
            var searchTerm = $('#searchTerm').val().toLowerCase();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var filtered = sales.filter(function(sale) {
                var saleDate = sale.tanggal_penjualan.split(' ')[0];
                if (startDate && saleDate < startDate) return false;
                if (endDate && saleDate > endDate) return false;
                var kasir = getUserById(sale.id_user);
                if (searchTerm) {
                    var matchKasir = kasir && kasir.nama.toLowerCase().includes(searchTerm);
                    var matchTotal = sale.total.toString().includes(searchTerm);
                    return matchKasir || matchTotal;
                }
                return true;
            });

            var $tbody = $('#salesTable tbody');
            $tbody.empty();

            if (filtered.length === 0) {
                $('#emptyState').show();
                $('#btnExportCSV').prop('disabled', true);
            } else {
                $('#emptyState').hide();
                $('#btnExportCSV').prop('disabled', false);
                filtered.forEach(function(sale) {
                    var kasir = getUserById(sale.id_user);
                    $tbody.append(`
                        <tr>
                            <td>${formatDate(sale.tanggal_penjualan)}</td>
                            <td>${kasir ? kasir.nama : 'Unknown'}</td>
                            <td class="font-weight-bold">${formatRupiah(sale.total)}</td>
                            <td>
                                <span class="badge badge-${sale.metode_pembayaran === 'cash' ? 'success' : 'primary'}">
                                    ${sale.metode_pembayaran === 'cash' ? 'Tunai' : 'Qris'}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info btnDetail" data-id="${sale.id_penjualan}">
                                <i class="fas fa-eye"></i>&nbsp; Detail
                                </button>
                                <a href="/penjualan/struk/${sale.id_penjualan}" target="_blank" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-print"></i>&nbsp; Struk
                                </a>
                            </td>
                        </tr>
                    `);
                });
            }
        }

        function showDetailModal(id_penjualan) {
            var sale = sales.find(s => s.id_penjualan == id_penjualan);
            var kasir = getUserById(sale.id_user);
            var details = getDetailByPenjualan(id_penjualan);

            var html = `
                <div class="row mb-3">
                    <div class="col">
                        <strong>Tanggal:</strong><br>${formatDate(sale.tanggal_penjualan)}
                    </div>
                    <div class="col">
                        <strong>Kasir:</strong><br>${kasir ? kasir.nama : 'Unknown'}
                    </div>
                    <div class="col">
                        <strong>Pembayaran:</strong><br>${sale.metode_pembayaran === 'cash' ? 'Tunai' : 'Qris'}
                    </div>
                    <div class="col">
                        <strong>ID Transaksi:</strong><br>${sale.id_penjualan}
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-rounded">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
            `;
            details.forEach(function(item) {
                html += `
                    <tr>
                        <td>${item.nama_barang}</td>
                        <td>${item.jumlah}</td>
                        <td>${formatRupiah(item.harga)}</td>
                        <td>${formatRupiah(item.subtotal)}</td>
                    </tr>
                 `;
            });
            html += `
                        </tbody>
                    </table>
                </div>
                <div class="bg-light p-3 rounded">
                    <div class="d-flex justify-content-between">
                        <span>Total</span>
                        <span class="font-weight-bold">${formatRupiah(sale.total)}</span>
                    </div>
                    ${sale.metode_pembayaran === 'cash' ? `
                    <div class="d-flex justify-content-between">
                        <span>Tunai</span>
                        <span>${formatRupiah(sale.jumlah_pembayaran)}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Kembalian</span>
                        <span class="font-weight-bold">${formatRupiah(sale.kembalian)}</span>
                    </div>
                    ` : ''}
                </div>
            `;
            $('#modalDetailContent').html(html);
            $('#detailModal').modal('show');
        }

        function exportToCSV() {
            var searchTerm = $('#searchTerm').val().toLowerCase();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var filtered = sales.filter(function(sale) {
                var saleDate = sale.tanggal_penjualan.split(' ')[0];
                if (startDate && saleDate < startDate) return false;
                if (endDate && saleDate > endDate) return false;
                var kasir = getUserById(sale.id_user);
                if (searchTerm) {
                    var matchKasir = kasir && kasir.nama.toLowerCase().includes(searchTerm);
                    var matchTotal = sale.total.toString().includes(searchTerm);
                    return matchKasir || matchTotal;
                }
                return true;
            });

            if (filtered.length === 0) return;

            var headers = ['Tanggal', 'Kasir', 'Total', 'Metode Pembayaran'];
            var rows = filtered.map(function(sale) {
                var kasir = getUserById(sale.id_user);
                return [
                    formatDate(sale.tanggal_penjualan),
                    kasir ? kasir.nama : 'Unknown',
                    sale.total,
                    sale.metode_pembayaran === 'cash' ? 'Tunai' : 'Qris'
                ];
            });

            var csvContent = 'data:text/csv;charset=utf-8,' + [headers, ...rows].map(row => row.join(',')).join('\n');
            var encodedUri = encodeURI(csvContent);
            var link = document.createElement('a');
            link.setAttribute('href', encodedUri);
            link.setAttribute('download', 'penjualan-' + (new Date()).toISOString().split('T')[0] + '.csv');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        $(function() {
            renderTable();

            $('#searchTerm, #startDate, #endDate').on('input change', function() {
                renderTable();
            });

            $('#salesTable').on('click', '.btnDetail', function() {
                var id = $(this).data('id');
                showDetailModal(id);
            });

            $('#btnExportCSV').on('click', function() {
                exportToCSV();
            });
        });
    </script>
</body>

</html>