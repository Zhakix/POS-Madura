<?php

namespace App\Controllers;

use App\Models\PenjualanModel;
use App\Models\UserModel;
use App\Models\DetailPenjualanModel;
use App\Models\BarangModel;
use App\Models\KategoriModel;
use App\Models\PembelianModel;

class Laporan extends BaseController
{
    // Laporan Penjualan
    public function index()
    {
        $id_user = session()->get('id_user');
        $modelUser = new UserModel();
        $user = $modelUser->find($id_user);

        $modelPenjualan = new PenjualanModel();
        $modelDetail = new DetailPenjualanModel();
        $modelProduk = new BarangModel();
        $modelKategori = new KategoriModel();
        $modelPembelian = new PembelianModel();

        // Ambil filter dari GET/POST (atau set default)
        $report_type = $this->request->getPost('report_type') ?? 'daily';
        $start_date = $this->request->getPost('start_date') ?? date('Y-m-01');
        $end_date = $this->request->getPost('end_date') ?? date('Y-m-d');

        // Query penjualan sesuai filter tanggal
        // Ambil penjualan + nama kasir
        $penjualan = $modelPenjualan
            ->select('penjualan.*, users.nama as nama_kasir')
            ->join('users', 'users.id_user = penjualan.id_user', 'left')
            ->where('tanggal_penjualan >=', $start_date)
            ->where('tanggal_penjualan <=', $end_date . ' 23:59:59')
            ->findAll();

        // Ringkasan
        $total_penjualan = 0;
        $jumlah_transaksi = 0;
        $data_harian = [];
        $labels_harian = [];
        $kategori_map = [];
        $data_kategori = [];
        $labels_kategori = [];

        // Contoh: hitung total penjualan dan transaksi per hari
        foreach ($penjualan as $row) {
            $tanggal = date('Y-m-d', strtotime($row['tanggal_penjualan']));
            $total_penjualan += $row['total'];
            $jumlah_transaksi++;
            // Data harian
            if (!isset($data_harian[$tanggal])) $data_harian[$tanggal] = 0;
            $data_harian[$tanggal] += $row['total'];
            // Data kategori (jika ada kategori di $row['kategori'])
            if (!empty($row['kategori'])) {
                if (!isset($kategori_map[$row['kategori']])) $kategori_map[$row['kategori']] = 0;
                $kategori_map[$row['kategori']] += $row['total'];
            }
        }

        // Ambil semua detail produk pada transaksi ini
        foreach ($penjualan as $row) {
            $details = $modelDetail->where('id_penjualan', $row['id_penjualan'])->findAll();
            foreach ($details as $detail) {
                $produk = $modelProduk->find($detail['id_barang']);
                $kategori = 'Tanpa Kategori';
                if (!empty($produk['id_kategori'])) {
                    $kategoriData = $modelKategori->find($produk['id_kategori']);
                    if ($kategoriData) {
                        $kategori = $kategoriData['nama_kategori'];
                    }
                }
                if (!isset($kategori_map[$kategori])) $kategori_map[$kategori] = 0;
                $kategori_map[$kategori] += $detail['subtotal'];
            }
        }

        // Siapkan data chart harian
        $period = new \DatePeriod(
            new \DateTime($start_date),
            new \DateInterval('P1D'),
            (new \DateTime($end_date))->modify('+1 day')
        );
        foreach ($period as $dt) {
            $tgl = $dt->format('Y-m-d');
            $labels_harian[] = $dt->format('d M');
            $data_harian_chart[] = $data_harian[$tgl] ?? 0;
        }

        // Siapkan data chart kategori
        foreach ($kategori_map as $kategori => $total) {
            $labels_kategori[] = $kategori;
            $data_kategori[] = $total;
        }

        // Query pembelian sesuai filter tanggal
        $pembelian = $modelPembelian
            ->where('tanggal_pembelian >=', $start_date)
            ->where('tanggal_pembelian <=', $end_date . ' 23:59:59')
            ->findAll();

        $total_pembelian = 0;
        $jumlah_pembelian = 0;
        foreach ($pembelian as $row) {
            $total_pembelian += $row['total'];
            $jumlah_pembelian++;
        }

        // Hitung total HPP (harga pokok penjualan) dari barang yang terjual
        $total_hpp = 0;
        foreach ($penjualan as $row) {
            $details = $modelDetail->where('id_penjualan', $row['id_penjualan'])->findAll();
            foreach ($details as $detail) {
                $produk = $modelProduk->find($detail['id_barang']);
                if ($produk) {
                    $total_hpp += $produk['harga_beli'] * $detail['jumlah'];
                }
            }
        }
        $keuntungan = $total_penjualan - $total_hpp;

        $data = [
            'user' => $user,
            'penjualan' => $penjualan,
            'total_penjualan' => $total_penjualan,
            'jumlah_transaksi' => $jumlah_transaksi,
            'total_pembelian' => $total_pembelian,
            'jumlah_pembelian' => $jumlah_pembelian,
            'keuntungan' => $keuntungan,
            'labels_harian' => $labels_harian,
            'data_harian' => $data_harian_chart ?? [],
            'labels_kategori' => $labels_kategori,
            'data_kategori' => $data_kategori,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'report_type' => $report_type,
        ];
        return view('laporan/index', $data);
    }

    // Laporan Stok
    public function stok()
    {
        $id_user = session()->get('id_user');
        $modelUser = new UserModel();
        $user = $modelUser->find($id_user);

        // Jika Anda punya model stok, misal StokModel:
        // use App\Models\StokModel;
        // $modelStok = new StokModel();
        // $stok = $modelStok->findAll();

        $data = [
            'user' => $user,
            // 'stok' => $stok, // aktifkan jika sudah ada data stok
        ];
        return view('laporan/laporan_stok', $data);
    }
}