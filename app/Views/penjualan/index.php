<html lang="en">

<?= $this->include('backend/template/css'); ?>


<body id="page-top">
    <div id="wrapper">
        <?= $this->include('backend/template/sidebar'); ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?= $this->include('backend/template/header'); ?>
                <div class="container-fluid">
                    <h3 class="mb-4">Penjualan</h3>
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
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
                    <!-- Mulai form sebelum .row -->
                    <form action="<?= base_url('penjualan/store') ?>" method="post" id="formPenjualan">
                        <input type="hidden" name="keranjang" id="inputKeranjang">
                        <input type="hidden" name="tanggal_penjualan" id="inputTanggal">
                        <input type="hidden" name="total" id="inputTotal">
                        <input type="hidden" name="metode_pembayaran" id="inputMetode">
                        <input type="hidden" name="jumlah_pembayaran" id="inputBayar">
                        <input type="hidden" name="kembalian" id="inputKembalian">
                        <div class="row">
                            <div class="col-lg-8">
                                <!-- Form Cari Produk & Keranjang -->
                                <form id="formCariProduk" class="mb-3" onsubmit="return false;">
                                    <div class="input-group">
                                        <input type="text" id="searchProduct" class="form-control" placeholder="Cari nama produk...">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button" id="btnCariProduk">Cari</button>
                                        </div>
                                    </div>
                                    <div id="searchResults" class="list-group mt-2"></div>
                                </form>
                                <div id="selectedProductArea" style="display:none;" class="mb-3">
                                    <div class="card card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span id="selectedProductName" class="font-weight-bold"></span>
                                                <span class="text-muted ml-2" id="selectedprodukstock"></span>
                                            </div>
                                            <div>
                                                <button type="button" class="btn btn-secondary btn-sm" id="btnKurangjumlah">-</button>
                                                <input type="number" id="inputjumlah" value="1" min="1" class="mx-2 text-center no-spinner" style="width:60px;">
                                                <button type="button" class="btn btn-secondary btn-sm" id="btnTambahjumlah">+</button>
                                                <button type="button" class="btn btn-success btn-sm ml-2" id="btnTambahKeranjang">Tambah</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">Keranjang</div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table mb-0" id="cartTable">
                                                <thead>
                                                    <tr>
                                                        <th>Produk</th>
                                                        <th>Harga</th>
                                                        <th>Jumlah</th>
                                                        <th>Subtotal</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-header">Ringkasan Pembayaran</div>
                                    <div class="card-body">
                                        <div class="mb-2">
                                            <span>Total:</span>
                                            <span class="float-right font-weight-bold" id="cartTotal">Rp 0</span>
                                        </div>
                                        <div class="mb-2">
                                            <label>Metode Pembayaran:</label>
                                            <select id="paymentMethod" class="form-control">
                                                <option value="cash">Tunai</option>
                                                <option value="Qris">Qris</option>
                                            </select>
                                        </div>
                                        <div class="mb-2" id="paymentAmountArea">
                                            <label>Jumlah Dibayar:</label>
                                            <input type="text" id="paymentAmount" class="form-control" min="0" value="" placeholder="0">
                                        </div>
                                        <div class="mb-2">
                                            <span>Kembalian:</span>
                                            <span class="float-right" id="changeAmount">Rp 0</span>
                                        </div>
                                        <!-- Tombol submit dan batal di sini -->
                                        <button class="btn btn-primary btn-block mt-3" type="submit" id="btnSelesaiTransaksi">Selesai Transaksi</button>
                                        <button class="btn btn-secondary btn-block mt-2" type="button" id="btnBatalTransaksi">Batal</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Modal Konfirmasi Batal -->
                <div class="modal fade" id="modalBatalTransaksi" tabindex="-1" aria-labelledby="modalBatalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalBatalLabel">Batalkan Transaksi?</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Semua item di keranjang dan pembayaran akan direset. Lanjutkan?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                                <button type="button" class="btn btn-danger" id="btnKonfirmasiBatal">Ya, Batalkan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?= $this->include('backend/template/footer'); ?>
        </div>


    </div>

    <script>
        $(function() {
            var produk = <?= json_encode($produk ?? []) ?>;
            var cart = [];

            function formatRupiah(angka) {
                angka = angka.toString().replace(/[^,\d]/g, '');
                var split = angka.split(',');
                var sisa = split[0].length % 3;
                var rupiah = split[0].substr(0, sisa);
                var ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    var separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }
                rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
                return rupiah ? 'Rp ' + rupiah : '';
            }

            function hitungTotalCart() {
                var total = 0;
                cart.forEach(function(item) {
                    total += item.harga * item.jumlah;
                });
                return total;
            }

            function getJumlahBayar() {
                var bayar = $('#paymentAmount').val().replace(/[^,\d]/g, '');
                return parseInt(bayar) || 0;
            }

            function getKembalian() {
                return getJumlahBayar() - hitungTotalCart();
            }

            function renderCart() {
                var $tbody = $('#cartTable tbody');
                $tbody.empty();
                var total = 0;
                if (cart.length === 0) {
                    $tbody.append(`
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="fas fa-shopping-cart fa-3x mb-2"></i><br>
                                Keranjang kosong
                            </td>
                        </tr>
                    `);
                    $('#cartTotal').text('Rp 0');
                    $('#changeAmount').text('Rp 0');
                    return;
                }
                cart.forEach(function(item, idx) {
                    var subtotal = item.harga * item.jumlah;
                    total += subtotal;
                    $tbody.append(`
                <tr>
                    <td>${item.nama_barang}</td>
                    <td>Rp ${parseInt(item.harga).toLocaleString('id-ID')}</td>
                    <td>${item.jumlah}</td>
                    <td>Rp ${parseInt(subtotal).toLocaleString('id-ID')}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm btnHapusItem" data-idx="${idx}">Hapus</button>
                    </td>
                </tr>
            `);
                });
                $('#cartTotal').text('Rp ' + total.toLocaleString('id-ID'));
                hitungKembalian();
            }

            function hitungKembalian() {
                var total = hitungTotalCart();
                var bayar = getJumlahBayar();
                var kembali = bayar - total;
                $('#changeAmount').text('Rp ' + (kembali > 0 ? kembali.toLocaleString('id-ID') : '0'));
            }

            // Format input bayar
            $('#paymentAmount').on('input', function() {
                var val = $(this).val();
                var clean = val.replace(/[^,\d]/g, '');
                $(this).val(formatRupiah(clean));
                hitungKembalian();
            });

            // Tambah jumlah
            $('#btnTambahjumlah').on('click', function() {
                var $jumlah = $('#inputjumlah');
                var max = parseInt($('#selectedProductArea').data('product')?.stok || 9999);
                var val = parseInt($jumlah.val()) || 1;
                if (val < max) {
                    $jumlah.val(val + 1);
                }
            });

            // Kurang jumlah
            $('#btnKurangjumlah').on('click', function() {
                var $jumlah = $('#inputjumlah');
                var val = parseInt($jumlah.val()) || 1;
                if (val > 1) {
                    $jumlah.val(val - 1);
                }
            });

            // Tambah ke keranjang
            $('#btnTambahKeranjang').on('click', function() {
                var product = $('#selectedProductArea').data('product');
                var jumlah = parseInt($('#inputjumlah').val()) || 1;
                if (!product) return;

                var existing = cart.find(item => item.id_barang == product.id_barang);
                var totaljumlah = (existing ? existing.jumlah : 0) + jumlah;

                if (totaljumlah > product.stok) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Stok tidak cukup!',
                        text: 'Stok barang tidak mencukupi untuk transaksi ini.',
                        showConfirmButton: true
                    });
                    return;
                }

                if (existing) {
                    existing.jumlah += jumlah;
                } else {
                    cart.push({
                        id_barang: product.id_barang,
                        nama_barang: product.nama_barang,
                        harga: parseInt(product.harga_jual),
                        jumlah: jumlah
                    });
                }
                renderCart();
                $('#selectedProductArea').hide();
            });

            // Hapus item keranjang
            $('#cartTable').on('click', '.btnHapusItem', function() {
                var idx = $(this).data('idx');
                cart.splice(idx, 1);
                renderCart();
            });

            // Cari produk
            function searchProduk() {
                var keyword = $('#searchProduct').val().toLowerCase();
                var results = produk.filter(function(product) {
                    return product.nama_barang.toLowerCase().includes(keyword) ||
                        (product.barcode && product.barcode.toLowerCase().includes(keyword));
                });

                var $results = $('#searchResults');
                $results.empty();
                if (results.length === 0 && keyword.length > 0) {
                    $results.append('<div class="list-group-item">Tidak ditemukan</div>');
                } else {
                    results.forEach(function(product) {
                        $results.append(
                            `<div class="list-group-item list-group-item-action" data-id="${product.id_barang}">
                                <div><b>${product.nama_barang}</b></div>
                                <div class="small text-muted">Stok: ${product.stok} | Rp ${parseInt(product.harga_jual).toLocaleString('id-ID')}</div>
                            </div>`
                        );
                    });
                }
            }

            $('#searchProduct').on('keyup', function() {
                searchProduk();
            });

            $('#searchResults').on('click', '.list-group-item-action', function() {
                var id = $(this).data('id');
                var product = produk.find(p => p.id_barang == id);
                if (product) {
                    $('#selectedProductName').text(product.nama_barang);
                    $('#selectedprodukstock').text('Stok: ' + product.stok);
                    $('#inputjumlah').val(1);
                    $('#selectedProductArea').show().data('product', product);
                    $('#searchResults').empty();
                    $('#searchProduct').val('');
                }
            });

            function getLocalDateTimeString() {
                const now = new Date();
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');

                return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
            }

            // Submit form penjualan (tanpa AJAX)
            $('#formPenjualan').on('submit', function(e) {
                // Tidak perlu validasi JS keranjang kosong di sini
                // Biarkan backend yang validasi dan tampilkan flashdata error
                var total = hitungTotalCart();
                var metode = $('#paymentMethod').val();
                var bayar = getJumlahBayar();

                if (metode === 'cash' && bayar < total) {
                    // Biarkan form tetap submit, backend yang validasi dan tampilkan flashdata error
                }

                $('#inputKeranjang').val(JSON.stringify(cart));
                $('#inputTanggal').val(getLocalDateTimeString());
                $('#inputTotal').val(total);
                $('#inputMetode').val(metode);
                $('#inputBayar').val(bayar);
                $('#inputKembalian').val(bayar - total);
                // Form akan submit normal
            });

            // Tombol Batal Transaksi: tampilkan modal
            $('#btnBatalTransaksi').on('click', function() {
                $('#modalBatalTransaksi').modal('show');
            });

            // Tombol konfirmasi batal di modal
            $('#btnKonfirmasiBatal').on('click', function() {
                cart = [];
                renderCart();
                $('#paymentAmount').val('0');
                $('#changeAmount').text('Rp 0');
                $('#modalBatalTransaksi').modal('hide');
            });

            // Inisialisasi awal
            renderCart();

            function togglePaymentFields() {
                var metode = $('#paymentMethod').val();
                if (metode === 'Qris') {
                    $('#paymentAmountArea').hide();
                    $('#changeAmount').closest('.mb-2').hide();
                } else {
                    $('#paymentAmountArea').show();
                    $('#changeAmount').closest('.mb-2').show();
                }
            }

            // Panggil saat halaman dimuat
            togglePaymentFields();

            // Panggil saat metode pembayaran berubah
            $('#paymentMethod').on('change', togglePaymentFields);
        });
    </script>
</body>

</html>