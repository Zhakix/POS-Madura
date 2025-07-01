<html lang="en">

<?= $this->include('backend/template/css'); ?>

<body id="page-top">
    <div id="wrapper">
        <?= $this->include('backend/template/sidebar'); ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?= $this->include('backend/template/header'); ?>
                <div class="container-fluid">
                    <h3 class="mb-4">Riwayat Pembelian</h3>
                    <div class="card mb-4">
                        <div class="card-body">
                            <form id="filterForm" class="row g-3">
                                <div class="col-md-4 d-flex align-items-end">
                                    <input type="text" id="searchTerm" class="form-control" placeholder="Cari supplier atau total...">
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
                            <table class="table table-bordered table-rounded" id="pembelianTable">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Supplier</th>
                                        <th>User</th>
                                        <th>Total</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data diisi via JS -->
                                </tbody>
                            </table>
                            <div id="emptyState" class="text-center text-muted my-4" style="display:none;">
                                Tidak ada riwayat pembelian.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Detail Pembelian -->
                <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="detailModalLabel">Detail Pembelian</h5>
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
        // Data dari backend
        var riwayat = <?= json_encode($riwayat ?? []) ?>;
        var detailPembelian = <?= json_encode($detailPembelian ?? []) ?>;

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

        function getDetailByPembelian(id_pembelian) {
            return detailPembelian.filter(d => d.id_pembelian == id_pembelian);
        }

        function renderTable() {
            var searchTerm = $('#searchTerm').val().toLowerCase();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var filtered = riwayat.filter(function(row) {
                var tgl = row.tanggal_pembelian.split(' ')[0];
                if (startDate && tgl < startDate) return false;
                if (endDate && tgl > endDate) return false;
                if (searchTerm) {
                    var matchSupplier = row.nama_supplier && row.nama_supplier.toLowerCase().includes(searchTerm);
                    var matchUser = row.nama_user && row.nama_user.toLowerCase().includes(searchTerm);
                    var matchTotal = row.total && row.total.toString().includes(searchTerm);
                    return matchSupplier || matchUser || matchTotal;
                }
                return true;
            });

            var $tbody = $('#pembelianTable tbody');
            $tbody.empty();

            if (filtered.length === 0) {
                $('#emptyState').show();
                $('#btnExportCSV').prop('disabled', true);
            } else {
                $('#emptyState').hide();
                $('#btnExportCSV').prop('disabled', false);
                filtered.forEach(function(row) {
                    $tbody.append(`
                        <tr>
                            <td>${formatDate(row.tanggal_pembelian)}</td>
                            <td>${row.nama_supplier || '-'}</td>
                            <td>${row.nama_user || '-'}</td>
                            <td class="font-weight-bold">${formatRupiah(row.total)}</td>
                            <td>
                                <button class="btn btn-sm btn-info btnDetail" data-id="${row.id_pembelian}">
                                <i class="fas fa-eye"></i>&nbsp; Detail
                                </button>
                            </td>
                        </tr>
                    `);
                });
            }
        }

        function showDetailModal(id_pembelian) {
            var pembelian = riwayat.find(r => r.id_pembelian == id_pembelian);
            var details = getDetailByPembelian(id_pembelian);

            var html = `
                <div class="row mb-3">
                    <div class="col">
                        <strong>Tanggal:</strong><br>${formatDate(pembelian.tanggal_pembelian)}
                    </div>
                    <div class="col">
                        <strong>Supplier:</strong><br>${pembelian.nama_supplier || '-'}
                    </div>
                    <div class="col">
                        <strong>User:</strong><br>${pembelian.nama_user || '-'}
                    </div>
                    <div class="col">
                        <strong>ID Transaksi:</strong><br>${pembelian.id_pembelian}
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-rounded">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Jumlah</th>
                                <th>Harga Beli</th>
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
                        <td>${formatRupiah(item.harga_beli)}</td>
                        <td>${formatRupiah(item.subtotal)}</td>
                    </tr>
                 `;
            });
            html += `
                        </tbody>
                    </table>
                </div>
                <!-- Bagian Total -->
                <div class="bg-light p-3 rounded mb-4">
                    <div class="d-flex justify-content-between">
                        <span>Total:</span>
                        <span class="font-weight-bold">${formatRupiah(pembelian.total)}</span>
                    </div>
                </div>

                <!-- Bagian Catatan terpisah -->
                <div class="mt-4">
                    <h6 class="mb-1" style="font-weight: 500; font-size: 0.875rem; color: #4a5568;">Catatan:</h6>
                    <div class="bg-light p-3 rounded border" style="border-color: #e2e8f0; font-size: 0.875rem;">
                        ${pembelian.catatan ? pembelian.catatan : '-'}
                    </div>
                </div>
            `;
            $('#modalDetailContent').html(html);
            $('#detailModal').modal('show');
        }

        function exportToCSV() {
            var searchTerm = $('#searchTerm').val().toLowerCase();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var filtered = riwayat.filter(function(row) {
                var tgl = row.tanggal_pembelian.split(' ')[0];
                if (startDate && tgl < startDate) return false;
                if (endDate && tgl > endDate) return false;
                if (searchTerm) {
                    var matchSupplier = row.nama_supplier && row.nama_supplier.toLowerCase().includes(searchTerm);
                    var matchUser = row.nama_user && row.nama_user.toLowerCase().includes(searchTerm);
                    var matchTotal = row.total && row.total.toString().includes(searchTerm);
                    return matchSupplier || matchUser || matchTotal;
                }
                return true;
            });

            if (filtered.length === 0) return;

            var headers = ['Tanggal', 'Supplier', 'User', 'Total'];
            var rows = filtered.map(function(row) {
                return [
                    formatDate(row.tanggal_pembelian),
                    row.nama_supplier || '-',
                    row.nama_user || '-',
                    row.total
                ];
            });

            var csvContent = 'data:text/csv;charset=utf-8,' + [headers, ...rows].map(row => row.join(',')).join('\n');
            var encodedUri = encodeURI(csvContent);
            var link = document.createElement('a');
            link.setAttribute('href', encodedUri);
            link.setAttribute('download', 'pembelian-' + (new Date()).toISOString().split('T')[0] + '.csv');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        $(function() {
            renderTable();

            $('#searchTerm, #startDate, #endDate').on('input change', function() {
                renderTable();
            });

            $('#pembelianTable').on('click', '.btnDetail', function() {
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