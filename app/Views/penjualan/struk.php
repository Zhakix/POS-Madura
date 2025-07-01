<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Struk Penjualan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; }
        .struk { width: 320px; margin: auto; border: 1px dashed #333; padding: 16px; }
        .struk h4, .struk h5 { text-align: center; margin: 0; }
        .struk table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .struk th, .struk td { padding: 3px; text-align: left; }
        .struk .right { text-align: right; }
        .struk .center { text-align: center; }
        .struk hr { margin: 8px 0; }
    </style>
</head>
<body>
<div class="struk">
    <h4><?= esc($nama_toko ?? 'TOKO ANDA') ?></h4>
    <h5><?= esc($alamat_toko ?? 'Alamat Toko, Kota') ?></h5>
    <hr>
    <table>
        <tr>
            <td>ID Transaksi</td>
            <td class="right"><?= esc($penjualan['id_penjualan']) ?></td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td class="right"><?= date('d-m-Y H:i', strtotime($penjualan['tanggal_penjualan'])) ?></td>
        </tr>
        <tr>
            <td>Kasir</td>
            <td class="right"><?= esc($kasir['nama'] ?? '-') ?></td>
        </tr>
    </table>
    <hr>
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th class="center">Qty</th>
                <th class="right">Harga</th>
                <th class="right">Sub</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($detail as $item): ?>
            <tr>
                <td><?= esc($item['nama_barang']) ?></td>
                <td class="center"><?= esc($item['jumlah']) ?></td>
                <td class="right"><?= number_format($item['harga'],0,',','.') ?></td>
                <td class="right"><?= number_format($item['subtotal'],0,',','.') ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <hr>
    <table>
        <tr>
            <td><b>Total</b></td>
            <td class="right"><b>Rp <?= number_format($penjualan['total'],0,',','.') ?></b></td>
        </tr>
        <tr>
            <td>Bayar</td>
            <td class="right">Rp <?= number_format($penjualan['jumlah_pembayaran'],0,',','.') ?></td>
        </tr>
        <tr>
            <td>Kembalian</td>
            <td class="right">Rp <?= number_format($penjualan['kembalian'],0,',','.') ?></td>
        </tr>
    </table>
    <hr>
    <div class="center">Terima kasih atas kunjungan Anda!</div>
</div>
<script>
    window.print();
</script>
</body>
</html>