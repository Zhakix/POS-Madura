<html lang="en">

<?= $this->include('backend/template/css'); ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Profil Saya</h3>
        <a href="<?= base_url('/') ?>" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <div class="row">
        <div class="col-md-4 text-center mb-4 mb-md-0">
            <img src="<?= base_url('writable/uploads/user/' . ($user['foto'] ?? 'default.png')) ?>" class="rounded-circle mb-3 border" width="120" height="120" style="object-fit:cover;">
            <form action="<?= base_url('profile/upload_foto') ?>" method="post" enctype="multipart/form-data">
                <input type="file" name="foto" class="form-control mb-2" accept="image/*" required>
                <button class="btn btn-sm btn-primary" type="submit">Ganti Foto</button>
            </form>
        </div>
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form action="<?= base_url('profile/update') ?>" method="post" class="mb-3">
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" name="nama" class="form-control" value="<?= esc($user['nama']) ?>" required>
                        </div>
                        <!-- Tambahkan field lain jika perlu -->
                        <button class="btn btn-success" type="submit">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="<?= base_url('profile/password') ?>" method="post">
                        <div class="form-group">
                            <label>Password Baru</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Ulangi Password</label>
                            <input type="password" name="password_confirm" class="form-control" required>
                        </div>
                        <button class="btn btn-warning" type="submit">Ganti Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>