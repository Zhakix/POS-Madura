<!-- Footer -->
<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>Copyright &copy; RX3 - MANTAN</span>
        </div>
    </div>
</footer>
<!-- End of Footer -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Mau Keluar?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Pilih "Logout" jika kamu sudah selesai shift.</div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger" href="<?= base_url('/logout'); ?>">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Link Link -->
<!-- Bootstrap core JavaScript-->
<script src="/Assets/vendor/jquery/jquery.min.js"></script>
<script src="/Assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="/Assets/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="/Assets/js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="/Assets/vendor/chart.js/Chart.min.js"></script>

<!-- js demo -->
<script src="/Assets/js/demo/chart-area-demo.js"></script>
<script src="/Assets/js/demo/chart-pie-demo.js"></script>
<script src="/Assets/js/demo/datatables-demo.js"></script>

<!-- DataTables -->
<script src="/Assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="/Assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- script universal -->
<?php if (session()->getFlashdata('success')): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Sukses',
            text: '<?= session()->getFlashdata('success') ?>',
            showConfirmButton: false,
            timer: 1800
        });
    </script>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: '<?= session()->getFlashdata('error') ?>',
            showConfirmButton: true
        });
    </script>
<?php endif; ?>

<script>
    function updateClock() {
        var now = new Date();
        var jam = now.getHours().toString().padStart(2, '0');
        var menit = now.getMinutes().toString().padStart(2, '0');
        var detik = now.getSeconds().toString().padStart(2, '0');
        document.getElementById('topbarClock').textContent = jam + ':' + menit + ':' + detik;
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>

<script>
    // Tampilkan loading saat ajax mulai, sembunyikan saat selesai
    $(document).ajaxStart(function() {
        $('#globalLoading').show();
    }).ajaxStop(function() {
        $('#globalLoading').hide();
    });

    // Jika ingin loading saat submit form:
    $('form').on('submit', function() {
        $('#globalLoading').show();
    });

    // Menyembunyikan loading setelah ajax selesai
    $.ajax({
        // ...
    }).always(function() {
        $('#globalLoading').hide();
    });
</script>