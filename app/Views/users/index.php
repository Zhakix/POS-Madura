<html lang="en">

<?= $this->include('backend/template/css'); ?>

<body id="page-top">
  <div id="wrapper">
    <?= $this->include('backend/template/sidebar'); ?>
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <?= $this->include('backend/template/header'); ?>
        <div class="container-fluid">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Kelola User</h3>
          </div>

          <div class="card shadow mb-4">
            <div class="card-body table-responsive">
              <div class="d-flex align-items-center justify-content-between flex-wrap mb-3 gap-2">
                <!-- Show entries kiri -->
                <div class="d-flex align-items-center">
                  <label class="mr-2 mb-0">Show</label>
                  <select id="showEntries" class="form-control form-control-sm" style="width: 70px;">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                  </select>
                  <label class="ml-2 mb-0">entries</label>
                </div>
                <!-- Filter status & Tambah user kanan -->
                <div class="d-flex align-items-center gap-2">
                  <select id="filterStatus" class="form-control form-control-sm mr-2" style="width: 150px;">
                    <option value="all">Semua</option>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                  </select>
                  <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambahUser">
                    <i class="fas fa-user-plus"></i> Tambah User
                  </button>
                </div>
              </div>
              <table class="table table-bordered  width=" 100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>Username</th>
                    <th>Nama</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($users)): ?>
                    <?php foreach ($users as $i => $user): ?>
                      <tr class="user-row <?= empty($user['deleted_at']) ? 'status-aktif' : 'status-nonaktif' ?>">
                        <td><?= esc($user['username']) ?></td>
                        <td><?= esc($user['nama']) ?></td>
                        <td><?= esc($user['role'] ?? '-') ?></td>
                        <td>
                          <?php if (empty($user['deleted_at'])): ?>
                            <span class="badge badge-success">Aktif</span>
                          <?php else: ?>
                            <span class="badge badge-secondary">Nonaktif</span>
                          <?php endif; ?>
                        </td>
                        <td>
                          <a href="#"
                            class="btn btn-sm btn-warning btnEditUser"
                            data-id="<?= $user['id_user'] ?>"
                            data-username="<?= esc($user['username']) ?>"
                            data-nama="<?= esc($user['nama']) ?>"
                            data-role="<?= esc($user['role']) ?>">
                            <i class="fas fa-edit"></i> Edit
                          </a>
                          <a href="<?= base_url('users/delete/' . $user['id_user']) ?>"
                            class="btn btn-danger btn-sm btn-hapus"
                            data-id="<?= $user['id_user'] ?>"
                            data-nama="<?= esc($user['nama']) ?>">
                            <i class="fas fa-trash"></i> Hapus
                          </a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="6" class="text-center text-muted">Belum ada data user.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
              <div class="d-flex justify-content-between align-items-center mt-2">
                <div id="infoEntries" class="text-muted small"></div>
                <nav>
                  <ul class="pagination pagination-sm mb-0" id="userPagination"></ul>
                </nav>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?= $this->include('backend/template/footer'); ?>
    </div>
  </div>

  <!-- Modal Tambah User -->
  <div class="modal fade" id="modalTambahUser" tabindex="-1" aria-labelledby="modalTambahUserLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form action="/users/store" method="post" id="formTambahUser">
        <?= csrf_field() ?>
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTambahUserLabel"><i class="fas fa-user-plus"></i> Tambah User</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <?php if (session()->getFlashdata('error')): ?>
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            <?php endif; ?>
            <div class="form-group">
              <label for="username">Username <span class="text-danger">*</span></label>
              <input type="text" name="username" id="username" class="form-control" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="nama">Nama <span class="text-danger">*</span></label>
              <input type="text" name="nama" id="nama" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="role">Role <span class="text-danger">*</span></label>
              <select name="role" id="role" class="form-control" required>
                <option value="">-- Pilih Role --</option>
                <option value="pemilik">Pemilik</option>
                <option value="penjaga">Penjaga</option>
                <!-- Tambahkan role lain jika perlu -->
              </select>
            </div>
            <div class="form-group">
              <label for="password">Password <span class="text-danger">*</span></label>
              <input type="password" name="password" id="password" class="form-control" required autocomplete="new-password">
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

  <!-- Modal Edit User -->
  <div class="modal fade" id="modalEditUser" tabindex="-1" aria-labelledby="modalEditUserLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form action="" method="post" id="formEditUser">
        <?= csrf_field() ?>
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalEditUserLabel"><i class="fas fa-user-edit"></i> Edit User</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="id_user" id="edit_id_user">
            <div class="form-group">
              <label for="edit_username">Username <span class="text-danger">*</span></label>
              <input type="text" name="username" id="edit_username" class="form-control" required autocomplete="off" readonly>
            </div>
            <div class="form-group">
              <label for="edit_nama">Nama <span class="text-danger">*</span></label>
              <input type="text" name="nama" id="edit_nama" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="edit_role">Role <span class="text-danger">*</span></label>
              <select name="role" id="edit_role" class="form-control" required>
                <option value="">-- Pilih Role --</option>
                <option value="pemilik">Pemilik</option>
                <option value="penjaga">Penjaga</option>
                <!-- Tambahkan role lain jika perlu -->
              </select>
            </div>
            <div class="form-group">
              <label for="edit_password">Password <span class="text-danger">*</span></label>
              <input type="password" name="password" id="edit_password" class="form-control" required autocomplete="new-password">
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


  <!-- script -->

  <!-- btn edit sc -->
  <script>
    $(document).on('click', '.btnEditUser', function(e) {
      e.preventDefault();
      $('#edit_id_user').val($(this).data('id'));
      $('#edit_username').val($(this).data('username'));
      $('#edit_nama').val($(this).data('nama'));
      $('#edit_role').val($(this).data('role'));
      $('#edit_password').val('');
      // Set action form
      $('#formEditUser').attr('action', '/users/update/' + $(this).data('id'));
      $('#modalEditUser').modal('show');
    });
    <?php if (session()->getFlashdata('error')): ?>
      $('#modalTambahUser').modal('show');
    <?php endif; ?>
  </script>

  <!-- btn hapus sc -->
  <script>
    $(document).on('click', '.btn-hapus', function(e) {
      e.preventDefault();
      var id = $(this).data('id');
      var nama = $(this).data('nama');
      Swal.fire({
        title: 'Yakin hapus User?',
        text: "User: " + nama,
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
            'action': '/users/delete/' + id
          }).appendTo('body').submit();
        }
      });
    });

    $('#filterStatus').on('change', function() {
      var val = $(this).val();
      if (val === 'all') {
        $('.user-row').show();
      } else if (val === 'aktif') {
        $('.user-row').hide();
        $('.status-aktif').show();
      } else if (val === 'nonaktif') {
        $('.user-row').hide();
        $('.status-nonaktif').show();
      }
    });
  </script>

  <!-- sc filter -->
  <script>
    $(function() {
      var $rows = $('.user-row');
      var $pagination = $('#userPagination');
      var $showEntries = $('#showEntries');
      var $filterStatus = $('#filterStatus');
      var $infoEntries = $('#infoEntries');
      var currentPage = 1;
      var rowsPerPage = parseInt($showEntries.val());

      function filterRows() {
        var status = $filterStatus.val();
        $rows.hide();
        var filtered = $rows.filter(function() {
          if (status === 'all') return true;
          if (status === 'aktif') return $(this).hasClass('status-aktif');
          if (status === 'nonaktif') return $(this).hasClass('status-nonaktif');
        });
        return filtered;
      }

      function renderTable() {
        var filtered = filterRows();
        var totalRows = filtered.length;
        var totalPages = Math.ceil(totalRows / rowsPerPage);
        if (currentPage > totalPages) currentPage = totalPages || 1;
        filtered.hide();
        var start = (currentPage - 1) * rowsPerPage;
        var end = start + rowsPerPage;
        filtered.slice(start, end).show();
        renderPagination(totalPages, totalRows, start, Math.min(end, totalRows));
        renderInfoEntries(totalRows, start, Math.min(end, totalRows));
      }

      function renderPagination(totalPages, totalRows, start, end) {
        $pagination.empty();
        if (totalPages <= 1 || totalRows === 0) return; // hanya tampil jika lebih dari 1 halaman dan ada data
        $pagination.append('<li class="page-item' + (currentPage == 1 ? ' disabled' : '') + '"><a class="page-link" href="#" data-page="' + (currentPage - 1) + '">Previous</a></li>');
        for (var i = 1; i <= totalPages; i++) {
          $pagination.append('<li class="page-item' + (i == currentPage ? ' active' : '') + '"><a class="page-link" href="#" data-page="' + i + '">' + i + '</a></li>');
        }
        $pagination.append('<li class="page-item' + (currentPage == totalPages ? ' disabled' : '') + '"><a class="page-link" href="#" data-page="' + (currentPage + 1) + '">Next</a></li>');
      }

      function renderInfoEntries(totalRows, start, end) {
        if (totalRows === 0) {
          $infoEntries.text('Showing 0 to 0 of 0 entries');
        } else {
          $infoEntries.text('Showing ' + (start + 1) + ' to ' + end + ' of ' + totalRows + ' entries');
        }
      }

      $pagination.on('click', 'a.page-link', function(e) {
        e.preventDefault();
        var page = parseInt($(this).data('page'));
        if (!isNaN(page) && page > 0 && page <= Math.ceil(filterRows().length / rowsPerPage)) {
          currentPage = page;
          renderTable();
        }
      });

      $showEntries.on('change', function() {
        rowsPerPage = parseInt($(this).val());
        currentPage = 1;
        renderTable();
      });

      $filterStatus.on('change', function() {
        currentPage = 1;
        renderTable();
      });

      // Inisialisasi
      renderTable();
    });
  </script>

</body>

</html>