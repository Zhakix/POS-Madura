<html lang="en">

<?= $this->include('backend/template/css'); ?>

<body id="page-top">
    <div id="wrapper">
        <?= $this->include('backend/template/sidebar'); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?= $this->include('backend/template/header'); ?>
                <div class="container-fluid">
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3>Data Supplier</h3>

                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-body table-responsive">
                            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                <div>
                                    <label>
                                        Show
                                        <select id="showEntries" class="custom-select custom-select-sm w-auto">
                                            <option value="5">5</option>
                                            <option value="10" selected>10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                        entries
                                    </label>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="text" id="searchSupplier" class="form-control form-control-sm mr-2"
                                        placeholder="Cari supplier..." style="width: 180px;">
                                    <button class="btn btn-primary btn-sm" data-toggle="modal"
                                        data-target="#modalTambahSupplier">
                                        <i class="fas fa-plus"></i> Tambah Supplier
                                    </button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="supplierTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Nama Supplier</th>
                                            <th>Alamat</th>
                                            <th>No. Telp</th>
                                            <th>Total Transaksi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($suppliers)): ?>
                                            <?php foreach ($suppliers as $row): ?>
                                                <tr>
                                                    <td><?= esc($row['nama_supplier']) ?></td>
                                                    <td><?= esc($row['alamat']) ?></td>
                                                    <td><?= esc($row['no_telepon']) ?></td>
                                                    <td>
                                                        <?= isset($totalTransaksi[$row['id_supplier']]) ? $totalTransaksi[$row['id_supplier']] : 0 ?>
                                                    </td>
                                                    <td>
                                                        <a href="<?= base_url('supplier/edit/' . $row['id_supplier']) ?>"
                                                            class="btn btn-warning btn-sm btn-edit"
                                                            data-id="<?= $row['id_supplier'] ?>"
                                                            data-nama="<?= esc($row['nama_supplier']) ?>"
                                                            data-alamat="<?= esc($row['alamat']) ?>"
                                                            data-no_telepon="<?= esc($row['no_telepon']) ?>"
                                                            data-toggle="modal"
                                                            data-target="#modalEditSupplier">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                        <a href="<?= base_url('supplier/delete/' . $row['id_supplier']) ?>"
                                                            class="btn btn-danger btn-sm btn-hapus"
                                                            data-id="<?= $row['id_supplier'] ?>"
                                                            data-nama="<?= esc($row['nama_supplier']) ?>">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5" class="text-center">Data tidak ditemukan.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <div id="showingInfo" class="text-xs text-muted ml-1">
                                        <!-- Info akan diisi oleh JS -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?= $this->include('backend/template/footer'); ?>
        </div>
    </div>

    <!-- Modal Tambah Supplier -->
    <div class="modal fade" id="modalTambahSupplier" tabindex="-1" aria-labelledby="modalTambahSupplierLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="<?= base_url('supplier/store') ?>" method="post" id="formTambahSupplier">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahSupplierLabel">Tambah Supplier</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Supplier</label>
                            <input type="text" name="nama_supplier" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <input type="text" name="alamat" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>No. Telp</label>
                            <input type="text" name="no_telepon" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Supplier -->
    <div class="modal fade" id="modalEditSupplier" tabindex="-1" aria-labelledby="modalEditSupplierLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="post" id="formEditSupplier">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditSupplierLabel">Edit Supplier</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_id_supplier" name="id_supplier">
                        <div class="form-group">
                            <label>Nama Supplier</label>
                            <input type="text" id="edit_nama_supplier" name="nama_supplier" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <input type="text" id="edit_alamat" name="alamat" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>No. Telp</label>
                            <input type="text" id="edit_no_telepon" name="no_telepon" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!--                                            script disni                                         -->

    <!-- script btn edit -->
    <script>
        $(document).on('click', '.btn-edit', function() {
            $('#edit_id_supplier').val($(this).data('id'));
            $('#edit_nama_supplier').val($(this).data('nama'));
            $('#edit_alamat').val($(this).data('alamat'));
            $('#edit_no_telepon').val($(this).data('no_telepon'));
            // Set action form
            $('#formEditSupplier').attr('action', '/supplier/update/' + $(this).data('id'));
        });
    </script>

    <!-- script btn hapus -->
    <script>
        $(document).on('click', '.btn-hapus', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var nama = $(this).data('nama');
            Swal.fire({
                title: 'Yakin hapus supplier?',
                text: "Supplier: " + nama,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form hapus secara dinamis
                    $('<form>', {
                        'method': 'POST',
                        'action': '/supplier/delete/' + id
                    }).appendTo('body').submit();
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            function filterAndShowEntries() {
                var searchValue = $('#searchSupplier').val().toLowerCase();
                var entries = parseInt($('#showEntries').val());
                var $rows = $('#supplierTable tbody tr');
                var matchedRows = $rows.filter(function() {
                    return $(this).text().toLowerCase().indexOf(searchValue) > -1;
                });

                $rows.hide();
                matchedRows.slice(0, entries).show();

                // Update showing info
                var total = matchedRows.length;
                var showing = total === 0 ? 0 : Math.min(entries, total);
                var infoText = total === 0
                    ? 'Showing 0 entries'
                    : 'Showing 1 to ' + showing + ' of ' + total + ' entries';
                $('#showingInfo').text(infoText);
            }

            $('#showEntries').on('change', filterAndShowEntries);
            $('#searchSupplier').on('keyup', filterAndShowEntries);

            // Jalankan saat halaman pertama kali dibuka
            filterAndShowEntries();
        });
    </script>

</body>

</html>