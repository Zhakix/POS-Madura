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

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">TABLE KATEGORI</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <div style="margin-bottom:10px; text-align: right;">
                                    <button type="button" id="btnInput" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambahKategori">
                                        <i class="fas fa-plus"></i> Tambah Kategori
                                    </button>
                                </div>
                                <?php if (session()->getFlashdata('error')): ?>
                                    <div class="alert alert-danger">
                                        <?= session()->getFlashdata('error') ?>
                                    </div>
                                <?php endif; ?>
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Nama Kategori</th>
                                            <th>Deskripsi</th>
                                            <th>Jumlah Barang</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($kategori as $row): ?>
                                            <td><?= esc($row['nama_kategori']) ?></td>
                                            <td><?= esc($row['deskripsi']) ?></td>
                                            <td><?= esc($row['jumlah_barang']) ?></td>
                                            <td>
                                                <a href="#" class="btn btn-warning btn-sm btn-edit"
                                                    data-id="<?= $row['id_kategori'] ?>"
                                                    data-nama="<?= esc($row['nama_kategori']) ?>"
                                                    data-toggle="modal" data-target="#modalEditKategori">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <a href="#" class="btn btn-danger btn-sm btn-hapus"
                                                    data-id="<?= $row['id_kategori'] ?>"
                                                    data-nama="<?= esc($row['nama_kategori']) ?>">
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

            <!-- Modal-modal disini -->

            <!-- Modal Tambah Kategori -->
            <div class="modal fade" id="modalTambahKategori" tabindex="-1" role="dialog" aria-labelledby="modalTambahKategoriLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form action="<?= base_url('kategori/store'); ?>" method="post">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTambahKategoriLabel">Tambah Kategori</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Nama Kategori</label>
                                    <input type="text" name="nama_kategori" class="form-control" required>
                                </div>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Deskripsi</label>
                                    <textarea name="deskripsi" class="form-control" rows="3"></textarea>
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

            <!-- Modal Edit Kategori -->
            <div class="modal fade" id="modalEditKategori" tabindex="-1" role="dialog" aria-labelledby="modalEditKategoriLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form id="formEditKategori" method="post">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalEditKategoriLabel">Edit Kategori</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id_kategori" id="edit_id_kategori">
                                <div class="form-group">
                                    <label>Nama Kategori</label>
                                    <input type="text" name="nama_kategori" id="edit_nama_kategori" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Deskripsi</label>
                                    <textarea name="deskripsi" id="edit_deskripsi" class="form-control" rows="3"></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
            <script>
                $(document).on('click', '.btn-edit', function() {
                    $('#edit_id_kategori').val($(this).data('id'));
                    $('#edit_nama_kategori').val($(this).data('nama'));
                    $('#formEditKategori').attr('action', '/kategori/update/' + $(this).data('id'));
                });
                $(document).on('click', '.btn-hapus', function(e) {
                    e.preventDefault();
                    var id = $(this).data('id'); // pastikan ini tidak undefined
                    var nama = $(this).data('nama');
                    Swal.fire({
                        title: 'Yakin hapus kategori?',
                        text: "Kategori: " + nama,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('<form>', {
                                'method': 'POST',
                                'action': '/kategori/delete/' + id
                            }).appendTo('body').submit();
                        }
                    });
                });
            </script>
</body>

</html>