<?php

namespace App\Controllers;
use App\Models\UserModel;
use App\Models\BarangModel;
use App\Models\PenjualanModel;
use App\Models\DetailPenjualanModel;
use App\Models\PembelianModel;

class Home extends BaseController
{
    public function index()
    {   
        if (!session()->has('id_user')) {
            return redirect()->to('/login');
        }

        $id_user = session()->get('id_user');
        $modelUser = new UserModel();
        $user = $modelUser->find($id_user);

        $modelBarang = new BarangModel();   
        $modelPenjualan = new PenjualanModel();
        $modelDetail = new DetailPenjualanModel();
        $modelPembelian = new PembelianModel();

        // Total produk
        $totalProduk = $modelBarang->countAll();

        // Penjualan hari ini
        $today = date('Y-m-d');
        $penjualanHariIni = $modelPenjualan
            ->where('tanggal_penjualan >=', $today . ' 00:00:00')
            ->where('tanggal_penjualan <=', $today . ' 23:59:59')
            ->findAll();
        $jumlahPenjualanHariIni = count($penjualanHariIni);
        $pendapatanHariIni = 0;
        foreach ($penjualanHariIni as $pj) {
            $pendapatanHariIni += $pj['total'];
        }

        // Keuntungan hari ini (penjualan - harga beli)
        $keuntunganHariIni = 0;
        foreach ($penjualanHariIni as $pj) {
            $details = $modelDetail->where('id_penjualan', $pj['id_penjualan'])->findAll();
            foreach ($details as $dt) {
                $barang = $modelBarang->find($dt['id_barang']);
                if ($barang) {
                    $keuntunganHariIni += ($dt['harga'] - $barang['harga_beli']) * $dt['jumlah'];
                }
            }
        }

        // Pembelian stok hari ini
        $pembelianHariIni = $modelPembelian
            ->where('tanggal_pembelian >=', $today . ' 00:00:00')
            ->where('tanggal_pembelian <=', $today . ' 23:59:59')
            ->findAll();
        $jumlahPembelianHariIni = count($pembelianHariIni);
        $pembelianTerakhir = $modelPembelian->orderBy('tanggal_pembelian', 'DESC')->first()['tanggal_pembelian'] ?? null;

        // Produk terlaris (top 5)
        $produkTerlaris = [];
        $produkJual = [];
        $allDetails = $modelDetail->select('id_barang, jumlah')->findAll();
        foreach ($allDetails as $dt) {
            if (!isset($produkJual[$dt['id_barang']])) $produkJual[$dt['id_barang']] = 0;
            $produkJual[$dt['id_barang']] += $dt['jumlah'];
        }
        arsort($produkJual);
        $produkTerlaris = [];
        $i = 0;
        foreach ($produkJual as $id_barang => $jumlah) {
            $barang = $modelBarang->find($id_barang);
            if ($barang) {
                $produkTerlaris[] = [
                    'nama_barang' => $barang['nama_barang'],
                    'jumlah' => $jumlah
                ];
                $i++;
                if ($i >= 5) break;
            }
        }

        // Stok hampir habis (< 5)
        $stokMenipis = $modelBarang->where('stok <', 5)->findAll();

        return view('auth/dashboard', [
            'user' => $user,
            'totalProduk' => $totalProduk,
            'penjualanHariIni' => $jumlahPenjualanHariIni,
            'pendapatanHariIni' => $pendapatanHariIni,
            'keuntunganHariIni' => $keuntunganHariIni,
            'totalPembelianHariIni' => $jumlahPembelianHariIni,
            'pembelianTerakhir' => $pembelianTerakhir,
            'produkTerlaris' => $produkTerlaris,
            'stokMenipis' => $stokMenipis
        ]);
    }
}
