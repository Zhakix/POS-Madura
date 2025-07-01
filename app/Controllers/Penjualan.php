<?php

namespace App\Controllers;

use App\Models\PenjualanModel;
use App\Models\UserModel;
use App\Models\BarangModel;
use App\Models\DetailPenjualanModel;

class Penjualan extends BaseController
{
    public function index()
    {
        $id_user = session()->get('id_user');
        $userModel = new UserModel();
        $user = $userModel->find($id_user);

        $produk = (new BarangModel())->findAll();
        $penjualan = (new PenjualanModel())->findAll();

        return view('penjualan/index', [
            'user' => $user,
            'produk' => $produk,
            'penjualan' => $penjualan
        ]);
    }

    public function create()
    {
        return view('penjualan/create');
    }

    public function riwayat()
    {
        $id_user = session()->get('id_user');
        $user = (new UserModel())->find($id_user);
        $sales = (new PenjualanModel())->findAll();
        $users = (new UserModel())->findAll();
        $detailPenjualan = (new DetailPenjualanModel())
            ->select('detail_penjualan.*, barang.nama_barang')
            ->join('barang', 'barang.id_barang = detail_penjualan.id_barang')
            ->findAll();

        return view('penjualan/riwayat', [
            'user' => $user,
            'users' => $users,
            'sales' => $sales,
            'detailPenjualan' => $detailPenjualan
        ]);
    }

    public function store()
    {

        // Pastikan method POST
        if ($this->request->getMethod() === 'POST') {
            $keranjang = $this->request->getPost('keranjang');
            $cart = json_decode($keranjang, true);
            error_log(print_r($cart, true));
            log_message('debug', 'Ini pesan debug.');

            if (!$cart || count($cart) == 0) {
                session()->setFlashdata('error', 'Keranjang kosong!');
                return redirect()->to('/penjualan');
            }

            $tanggal_penjualan = $this->request->getPost('tanggal_penjualan');
            $total = $this->request->getPost('total');
            $metode_pembayaran = $this->request->getPost('metode_pembayaran');
            $jumlah_pembayaran = $this->request->getPost('jumlah_pembayaran');
            $kembalian = $this->request->getPost('kembalian');
            $id_user = session()->get('id_user');

            // Ambil data dari request
            $bayar = $this->request->getPost('jumlah_pembayaran');
            $total = $this->request->getPost('total');
            $metode = $this->request->getPost('metode_pembayaran');

            // Validasi jumlah pembayaran
            if ($metode === 'cash' && $bayar < $total) {
                session()->setFlashdata('error', 'Jumlah pembayaran kurang!');
                return redirect()->to('/penjualan');
            }

            $penjualanModel = new PenjualanModel();
            $detailModel = new DetailPenjualanModel();
            $barangModel = new BarangModel();

            $randomId = 'TRX' . strtoupper(bin2hex(random_bytes(4))); // Contoh: TRX8F3A1B2C
            
            // Simpan penjualan utama
            $id_penjualan = $penjualanModel->insert([
                'tanggal_penjualan' => $tanggal_penjualan,
                'total' => $total,
                'metode_pembayaran' => $metode_pembayaran,
                'jumlah_pembayaran' => $jumlah_pembayaran,
                'kembalian' => $kembalian,
                'id_user' => $id_user
            ], true);

            if (!$id_penjualan) {
                session()->setFlashdata('error', 'Gagal menyimpan penjualan utama!');
                return redirect()->to('/penjualan');
            }

            // Simpan detail penjualan dan update stok
            foreach ($cart as $item) {
                // Cek stok barang
                $barang = $barangModel->find($item['id_barang']);
                if (!$barang || $barang['stok'] < $item['jumlah']) {
                    session()->setFlashdata('error', 'Stok tidak cukup untuk barang: ' . $barang['nama_barang']);
                    // Hapus penjualan utama jika sudah terlanjur insert
                    $penjualanModel->delete($id_penjualan);
                    return redirect()->to('/penjualan');
                }
                // Simpan detail penjualan
                $ok = $detailModel->insert([
                    'id_penjualan' => $id_penjualan,
                    'id_barang' => $item['id_barang'],
                    'jumlah' => $item['jumlah'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['jumlah'] * $item['harga']
                ]);
                if (!$ok) {
                    session()->setFlashdata('error', 'Gagal menyimpan detail penjualan!');
                    $penjualanModel->delete($id_penjualan);
                    return redirect()->to('/penjualan');
                }
                // Update stok barang
                $barangModel->where('id_barang', $item['id_barang'])
                    ->set('stok', 'stok - ' . (int)$item['jumlah'], false)
                    ->update();
            }

            session()->setFlashdata('success', 'Transaksi berhasil disimpan!');
            return redirect()->to('/penjualan');
        }

        // Jika bukan POST
        session()->setFlashdata('error', 'Metode tidak diizinkan');
        return redirect()->to('/penjualan');
    }

    public function edit($id)
    {
        $model = new PenjualanModel();
        $penjualan = $model->find($id);
        return view('penjualan/edit', ['penjualan' => $penjualan]);
    }

    public function update($id)
    {
        $model = new PenjualanModel();
        $data = [
            'tanggal_penjualan'   => $this->request->getPost('tanggal_penjualan'),
            'total'               => $this->request->getPost('total'),
            'metode_pembayaran'   => $this->request->getPost('metode_pembayaran'),
            'jumlah_pembayaran'   => $this->request->getPost('jumlah_pembayaran'),
            'kembalian'           => $this->request->getPost('kembalian'),
        ];
        $model->update($id, $data);
        session()->setFlashdata('success', 'Penjualan berhasil diupdate!');
        return redirect()->to('/penjualan');
    }

    public function delete($id)
    {
        $model = new PenjualanModel();
        $model->delete($id);
        session()->setFlashdata('success', 'Penjualan berhasil dihapus!');
        return redirect()->to('/penjualan');
    }

    public function search()
    {
        $keyword = $this->request->getGet('q');
        $model = new BarangModel();

        $result = $model
            ->like('nama_barang', $keyword)
            ->orLike('barcode', $keyword)
            ->findAll(10);

        $data = [];
        foreach ($result as $row) {
            $data[] = [
                'id'    => $row['id_barang'],
                'nama'  => $row['nama_barang'],
                'harga' => $row['harga_jual']
            ];
        }
        return $this->response->setJSON($data);
    }

    public function struk($id)
    {
        $penjualanModel = new PenjualanModel();
        $detailModel = new DetailPenjualanModel();
        $userModel = new UserModel();

        $penjualan = $penjualanModel->find($id);
        $detail = $detailModel->select('detail_penjualan.*, barang.nama_barang')
            ->join('barang', 'barang.id_barang = detail_penjualan.id_barang')
            ->where('detail_penjualan.id_penjualan', $id)
            ->findAll();
        $kasir = $userModel->find($penjualan['id_user']);

        return view('penjualan/struk', [
            'penjualan' => $penjualan,
            'detail' => $detail,
            'kasir' => $kasir,
            'nama_toko' => 'RX3 MANTAN',
            'alamat_toko' => 'Jl. Raya Jagakarsa, RT.9/RW.5, Jagakarsa, Kec. Jagakarsa, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12620'
        ]);
    }
}
