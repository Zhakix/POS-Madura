<html lang="en">

<?= $this->include('backend/template/css'); ?>

<body id="page-top">
    <div id="wrapper">
        <?= $this->include('backend/template/sidebar'); ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?= $this->include('backend/template/header'); ?>
                <div class="container-fluid">
                    <h3 class="mb-4">Pembelian</h3>
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                    <?php endif; ?>
                    <form action="/pembelian/store" method="post" id="formPembelian">
                        <input type="hidden" name="keranjang" id="inputKeranjang">
                        <input type="hidden" name="tanggal" id="inputTanggal">
                        <input type="hidden" name="total" id="inputTotal">
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
                                        <div class="row align-items-center">
                                            <!-- Kiri: Info Produk -->
                                            <div class="col-md-6 mb-2 mb-md-0">
                                                <span id="selectedProductName" class="font-weight-bold"></span>
                                                <span class="text-muted ml-2" id="selectedprodukstock"></span>
                                                <div class="mt-2 small text-muted">
                                                    Harga beli terakhir: <span id="hargaBeliTerakhir"></span>
                                                </div>
                                            </div>
                                            <!-- Kanan: Tombol jumlah, harga beli, tambah -->
                                            <div class="col-md-6">
                                                <div class="form-row align-items-center">
                                                    <!-- Tombol kurang -->
                                                    <div class="col-auto">
                                                        <button type="button" class="btn btn-secondary btn-sm" id="btnKurangjumlah">-</button>
                                                    </div>
                                                    <!-- Input jumlah -->
                                                    <div class="col-auto">
                                                        <input type="number" id="inputjumlah" value="1" min="1"
                                                            class="form-control text-center no-spinner"
                                                            style="width: 70px;">
                                                    </div>
                                                    <!-- Tombol tambah -->
                                                    <div class="col-auto">
                                                        <button type="button" class="btn btn-secondary btn-sm" id="btnTambahjumlah">+</button>
                                                    </div>
                                                    <!-- Input harga beli dengan prefix Rp. -->
                                                    <div class="col">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Rp.</span>
                                                            </div>
                                                            <input type="number" id="inputHargaBeli" class="form-control" min="0" placeholder="Harga Beli">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <button type="button" class="btn btn-success btn-block" id="btnTambahKeranjang">Tambah</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">Daftar Pembelian</div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table mb-0" id="cartTable">
                                                <thead>
                                                    <tr>
                                                        <th>Produk</th>
                                                        <th>Harga Beli</th>
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
                                    <div class="card-header">Ringkasan Pembelian</div>
                                    <div class="card-body">
                                        <div class="mb-2">
                                            <span>Total:</span>
                                            <span class="float-right font-weight-bold" id="cartTotal">Rp 0</span>
                                        </div>
                                        <div class="mb-2">
                                            <label>Supplier:</label>
                                            <select name="id_supplier" id="id_supplier" class="form-control" required>
                                                <option value="">Pilih Supplier</option>
                                                <?php foreach ($suppliers ?? [] as $supplier): ?>
                                                    <option value="<?= $supplier['id_supplier'] ?>"><?= $supplier['nama_supplier'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="mb-2">
                                            <label>Catatan:</label>
                                            <textarea name="catatan" class="form-control" rows="2"></textarea>
                                        </div>
                                        <button class="btn btn-primary btn-block mt-3" type="submit" id="btnSelesaiPembelian">Selesai Pembelian</button>
                                        <button class="btn btn-secondary btn-block mt-2" type="button" id="btnBatalPembelian">Batal</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Modal Konfirmasi Batal -->
                <div class="modal fade" id="modalBatalPembelian" tabindex="-1" aria-labelledby="modalBatalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalBatalLabel">Batalkan Pembelian?</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Semua item di daftar pembelian akan direset. Lanjutkan?
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
                    total += item.harga_beli * item.jumlah;
                });
                return total;
            }

            function renderCart() {
                var $tbody = $('#cartTable tbody');
                $tbody.empty();
                var total = 0;
                if (cart.length === 0) {
                    $tbody.append(`
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="fas fa-truck fa-3x mb-2"></i><br>
                                Daftar pembelian masih kosong
                            </td>
                        </tr>
                    `);
                    $('#cartTotal').text('Rp 0');
                    return;
                }
                cart.forEach(function(item, idx) {
                    var subtotal = item.harga_beli * item.jumlah;
                    total += subtotal;
                    $tbody.append(`
                        <tr>
                            <td>${item.nama_barang}</td>
                            <td>Rp ${parseInt(item.harga_beli).toLocaleString('id-ID')}</td>
                            <td>${item.jumlah}</td>
                            <td>Rp ${parseInt(subtotal).toLocaleString('id-ID')}</td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm btnHapusItem" data-idx="${idx}">Hapus</button>
                            </td>
                        </tr>
                    `);
                });
                $('#cartTotal').text('Rp ' + total.toLocaleString('id-ID'));
            }

            // Tambah ke keranjang
            $('#btnTambahKeranjang').on('click', function() {
                var product = $('#selectedProductArea').data('product');
                var jumlah = parseInt($('#inputjumlah').val()) || 1;
                var hargaBeli = parseInt($('#inputHargaBeli').val()) || 0;
                if (!product) return;
                if (hargaBeli <= 0) {
                    alert('Harga beli harus diisi!');
                    return;
                }
                var existing = cart.find(item => item.id_barang == product.id_barang);
                if (existing) {
                    existing.jumlah += jumlah;
                    existing.harga_beli = hargaBeli; // update harga beli terakhir
                } else {
                    cart.push({
                        id_barang: product.id_barang,
                        nama_barang: product.nama_barang,
                        harga_beli: hargaBeli,
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
                                <div class="small text-muted">Stok: ${product.stok} | Harga beli terakhir: Rp ${parseInt(product.harga_beli_terakhir || 0).toLocaleString('id-ID')}</div>
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
                    $('#inputHargaBeli').val(product.harga_beli_terakhir || '');
                    $('#hargaBeliTerakhir').text('Rp ' + (product.harga_beli_terakhir ? parseInt(product.harga_beli_terakhir).toLocaleString('id-ID') : '0'));
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

            // Submit form pembelian (tanpa AJAX)
            $('#formPembelian').on('submit', function(e) {
                // Tidak perlu validasi JS keranjang kosong di sini
                // Biarkan backend yang validasi dan tampilkan flashdata error
                var total = hitungTotalCart();
                $('#inputKeranjang').val(JSON.stringify(cart));
                $('#inputTanggal').val(getLocalDateTimeString());
                $('#inputTotal').val(total);
                // Form akan submit normal
            });

            // Tombol Batal Pembelian: tampilkan modal
            $('#btnBatalPembelian').on('click', function() {
                $('#modalBatalPembelian').modal('show');
            });

            // Tombol konfirmasi batal di modal
            $('#btnKonfirmasiBatal').on('click', function() {
                cart = [];
                renderCart();
                $('#modalBatalPembelian').modal('hide');
            });

            // Tambah jumlah
            $('#selectedProductArea').on('click', '#btnTambahjumlah', function() {
                var $jumlah = $('#inputjumlah');
                var val = parseInt($jumlah.val()) || 1;
                $jumlah.val(val + 1);
            });

            // Kurang jumlah
            $('#selectedProductArea').on('click', '#btnKurangjumlah', function() {
                var $jumlah = $('#inputjumlah');
                var val = parseInt($jumlah.val()) || 1;
                if (val > 1) {
                    $jumlah.val(val - 1);
                }
            });

            // Inisialisasi awal
            renderCart();

            date_default_timezone_set('Asia/Jakarta');
        });
    </script>
</body>

</html>