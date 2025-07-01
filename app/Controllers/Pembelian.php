<?php


namespace App\Controllers;

use App\Models\PembelianModel;
use App\Models\SupplierModel;
use App\Models\UserModel;
use App\Models\DetailPembelianModel;
use App\Models\BarangModel;

class Pembelian extends BaseController
{
    public function index()
    {
        $id_user = session()->get('id_user');
        $userModel = new UserModel();
        $user = $userModel->find($id_user);

        $model = new PembelianModel();
        $pembelian = $model->findAll();

        $produk = (new BarangModel())
            ->select('barang.*, (SELECT harga_beli FROM detail_pembelian WHERE id_barang = barang.id_barang ORDER BY id_dtl_pembelian DESC LIMIT 1) as harga_beli_terakhir')
            ->findAll();

        $suppliers = (new SupplierModel())->findAll();

        return view('pembelian/index', [
            'user' => $user,
            'pembelian' => $pembelian,
            'produk' => $produk,
            'suppliers' => $suppliers,
        ]);
    }

    public function create()
    {
        $supplierModel = new SupplierModel();
        $suppliers = $supplierModel->findAll();
        return view('pembelian/create', ['suppliers' => $suppliers]);
    }

    public function store()
    {
        if ($this->request->getMethod() === 'POST') {
            $keranjang = $this->request->getPost('keranjang');
            $cart = json_decode($keranjang, true);

            if (!$cart || count($cart) == 0) {
                session()->setFlashdata('error', 'Daftar pembelian kosong!');
                return redirect()->to('/pembelian');
            }

            $data = [
                'id_supplier' => $this->request->getPost('id_supplier'),
                'id_user'     => session()->get('id_user'),
                'tanggal_pembelian' => $this->request->getPost('tanggal'),
                'total'       => $this->request->getPost('total'),
                'catatan'     => $this->request->getPost('catatan'),
            ];

            $barangModel = new BarangModel();
            $pembelianModel = new PembelianModel();
            $ok = $pembelianModel->insert($data, true); // true agar dapat id terakhir (auto increment)
            if (!$ok) {
                session()->setFlashdata('error', 'Gagal menyimpan pembelian!');
                return redirect()->to('/pembelian');
            }

            $id_pembelian = $pembelianModel->getInsertID(); // ambil id auto increment terakhir

            $detailModel = new DetailPembelianModel();
            foreach ($cart as $item) {
                $detailModel->insert([
                    'id_pembelian' => $id_pembelian,
                    'id_barang' => $item['id_barang'],
                    'jumlah' => $item['jumlah'],
                    'harga_beli' => $item['harga_beli'],
                    'subtotal' => $item['jumlah'] * $item['harga_beli']
                ]);
                // Update stok barang (tambah stok)
                $barangModel->where('id_barang', $item['id_barang'])
                    ->set('stok', 'stok + ' . (int)$item['jumlah'], false)
                    ->update();
            }

            session()->setFlashdata('success', 'Pembelian berhasil ditambahkan!');
            return redirect()->to('/pembelian');
        }

        session()->setFlashdata('error', 'Metode tidak diizinkan');
        return redirect()->to('/pembelian');
    }

    public function edit($id)
    {
        $model = new PembelianModel();
        $pembelian = $model->find($id);
        $supplierModel = new SupplierModel();
        $suppliers = $supplierModel->findAll();
        return view('pembelian/edit', ['pembelian' => $pembelian, 'suppliers' => $suppliers]);
    }

    public function update($id)
    {
        $model = new PembelianModel();
        $data = [
            'id_supplier' => $this->request->getPost('id_supplier'),
            'tanggal_pembelian'     => $this->request->getPost('tanggal'),
            'total'       => $this->request->getPost('total'),
            'catatan'     => $this->request->getPost('catatan'),
        ];
        $model->update($id, $data);
        session()->setFlashdata('success', 'Pembelian berhasil diupdate!');
        return redirect()->to('/pembelian');
    }

    public function delete($id)
    {
        $model = new PembelianModel();
        $model->delete($id);
        session()->setFlashdata('success', 'Pembelian berhasil dihapus!');
        return redirect()->to('/pembelian');
    }
    public function riwayat()
    {
        $id_user = session()->get('id_user');
        $user = (new UserModel())->find($id_user);

        // Ambil semua pembelian, join suppliers dan users
        $pembelianModel = new PembelianModel();
        $riwayat = $pembelianModel
            ->select('pembelian.*, suppliers.nama_supplier, users.nama as nama_user')
            ->join('suppliers', 'suppliers.id_supplier = pembelian.id_supplier', 'left')
            ->join('users', 'users.id_user = pembelian.id_user', 'left')
            ->orderBy('pembelian.tanggal_pembelian', 'DESC')
            ->findAll();

        // Ambil detail pembelian (jika ingin tampilkan detail barang per pembelian)
        $detailPembelian = (new DetailPembelianModel())
            ->select('detail_pembelian.*, barang.nama_barang')
            ->join('barang', 'barang.id_barang = detail_pembelian.id_barang')
            ->findAll();

        return view('pembelian/riwayat', [
            'user' => $user,
            'riwayat' => $riwayat,
            'detailPembelian' => $detailPembelian
        ]);
    }
}
