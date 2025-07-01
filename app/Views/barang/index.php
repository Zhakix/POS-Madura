<html lang="en">

<?= $this->include('backend/template/css'); ?>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?= $this->include('backend/template/sidebar'); ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?= $this->include('backend/template/header'); ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('error'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('success'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">TABEL BARANG</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <div style="margin-bottom:10px; text-align: right;">
                                    <button type="button" id="btnInput" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambahBarang">
                                        <i class="fas fa-plus"></i> Tambah Barang
                                    </button>
                                </div>
                                <!-- Modal Tambah Barang -->
                                <div class="modal fade" id="modalTambahBarang" tabindex="-1" role="dialog" aria-labelledby="modalTambahBarangLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form action="<?= base_url('barang/store'); ?>" method="post">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalTambahBarangLabel">Tambah Barang</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Nama Barang</label>
                                                        <input type="text" name="nama_barang" class="form-control" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Kategori</label>
                                                        <select name="id_kategori" class="form-control" required>
                                                            <option value="">-- Pilih Kategori --</option>
                                                            <?php foreach ($kategori as $kat): ?>
                                                                <option value="<?= $kat['id_kategori'] ?>"><?= esc($kat['nama_kategori']) ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Harga Beli</label>
                                                        <input type="number" name="harga_beli" class="form-control" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Harga Jual</label>
                                                        <input type="number" name="harga_jual" class="form-control" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Stok</label>
                                                        <input type="number" name="stok" class="form-control" required>
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
                                <!-- Modal Edit Barang -->
                                <div class="modal fade" id="modalEditBarang" tabindex="-1" role="dialog" aria-labelledby="modalEditBarangLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form id="formEditBarang" method="post">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalEditBarangLabel">Edit Barang</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="id_barang" id="edit_id_barang">
                                                    <div class="form-group">
                                                        <label>Nama Barang</label>
                                                        <input type="text" name="nama_barang" id="edit_nama_barang" class="form-control" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Kategori</label>
                                                        <select name="id_kategori" id="edit_id_kategori" class="form-control" required>
                                                            <option value="">-- Pilih Kategori --</option>
                                                            <?php foreach ($kategori as $kat): ?>
                                                                <option value="<?= $kat['id_kategori'] ?>"><?= esc($kat['nama_kategori']) ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Harga Beli</label>
                                                        <input type="text" name="harga_beli" id="edit_harga_beli" class="form-control rupiah" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Harga Jual</label>
                                                        <input type="text" name="harga_jual" id="edit_harga_jual" class="form-control rupiah" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Stok</label>
                                                        <input type="number" name="stok" id="edit_stok" class="form-control" required>
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
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Nama Barang</th>
                                            <th>Kategori</th>
                                            <th>Harga Beli</th>
                                            <th>Harga Jual</th>
                                            <th>Stok</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($barang as $row): ?>
                                            <tr>
                                                <td><?= esc($row['nama_barang']) ?></td>
                                                <td><?= esc($row['nama_kategori']) ?></td>
                                                <td><?= 'Rp. ' . number_format($row['harga_beli'], 0, ',', '.') ?></td>
                                                <td><?= 'Rp. ' . number_format($row['harga_jual'], 0, ',', '.') ?></td>
                                                <td><?= esc($row['stok']) ?></td>
                                                <td>
                                                    <a href="<?= base_url('barang/edit/' . $row['id_barang']) ?>"
                                                        class="btn btn-warning btn-sm btn-edit"
                                                        data-id="<?= $row['id_barang'] ?>"
                                                        data-nama="<?= esc($row['nama_barang']) ?>"
                                                        data-kategori="<?= esc($row['id_kategori']) ?>"
                                                        data-harga_beli="<?= esc($row['harga_beli']) ?>"
                                                        data-harga_jual="<?= esc($row['harga_jual']) ?>"
                                                        data-stok="<?= esc($row['stok']) ?>"
                                                        data-toggle="modal"
                                                        data-target="#modalEditBarang">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a href="<?= base_url('barang/delete/' . $row['id_barang']) ?>"
                                                        class="btn btn-danger btn-sm btn-hapus"
                                                        data-id="<?= $row['id_barang'] ?>"
                                                        data-nama="<?= esc($row['nama_barang']) ?>">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
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

    <!-- Custom script for handling edit and delete actions -->
    <script>
        $(document).on('click', '.btn-edit', function() {
            $('#edit_id_barang').val($(this).data('id'));
            $('#edit_nama_barang').val($(this).data('nama'));
            $('#edit_id_kategori').val($(this).data('id_kategori'));
            $('#edit_harga_beli').val($(this).data('harga_beli'));
            $('#edit_harga_jual').val($(this).data('harga_jual'));
            $('#edit_stok').val($(this).data('stok'));
            // Set action form
            $('#formEditBarang').attr('action', '/barang/update/' + $(this).data('id'));
        });
    </script>
    <script>
        $(document).on('click', '.btn-hapus', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var nama = $(this).data('nama');
            Swal.fire({
                title: 'Yakin hapus barang?',
                text: "Barang: " + nama,
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
                        'action': '/barang/delete/' + id
                    }).appendTo('body').submit();
                }
            });
        });
    </script>

    <!-- Penambahan Rp. dan titik di form harga beli dan harga jual -->
    <script>
        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                var separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix === undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }

        $(document).on('input', '.rupiah', function() {
            this.value = formatRupiah(this.value, 'Rp. ');
        });
    </script>
</body>

</html>