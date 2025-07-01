<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\UserModel;
use App\Models\KategoriModel;
use PhpParser\Node\Stmt\ElseIf_;

class Barang extends BaseController
{
    public function index(): string
    {
        $id_user = session()->get('id_user');
        $modelUser = new UserModel();
        $user = $modelUser->find($id_user);

        $modelBarang = new BarangModel();
        $barang = $modelBarang
            ->select('barang.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.id_kategori = barang.id_kategori', 'left')
            ->findAll();

        $kategoriModel = new KategoriModel();
        $kategori = $kategoriModel->findAll();

        return view('barang/index', [
            'user' => $user,
            'barang' => $barang,
            'kategori' => $kategori
        ]);
    }

    public function create()
    {
        $kategoriModel = new KategoriModel();
        $kategori = $kategoriModel->findAll();
        return view('barang/create', ['kategori' => $kategori]);
    }

    public function store()
    {
        $harga_beli = str_replace(['Rp', '.', ' '], '', $this->request->getPost('harga_beli'));
        $harga_jual = str_replace(['Rp', '.', ' '], '', $this->request->getPost('harga_jual'));
        $stok = $this->request->getPost('stok');

        if ($harga_beli === '' || $harga_jual === '') {
            session()->setFlashdata('error', 'Harga beli dan harga jual tidak boleh kosong!');
            return redirect()->back()->withInput();
        } elseif ($harga_beli > $harga_jual) {
            session()->setFlashdata('error', 'Harga beli tidak boleh lebih besar dari harga jual!');
            return redirect()->back()->withInput();
        } elseif ($stok === '' || $stok <= 0) {
            session()->setFlashdata('error', 'Stok tidak boleh kosong atau kurang dari 0!');
            return redirect()->back()->withInput();
        }


        $data = [
            'nama_barang' => $this->request->getPost('nama_barang'),
            'id_kategori' => $this->request->getPost('id_kategori'),
            'harga_beli'  => (int)$harga_beli,
            'harga_jual'  => (int)$harga_jual,
            'stok'        => $this->request->getPost('stok'),
            'barcode'     => $this->request->getPost('barcode'),
        ];
        $modelBarang = new BarangModel();
        $modelBarang->insert($data);
        session()->setFlashdata('success', 'Barang berhasil ditambahkan!');
        return redirect()->to('/barang');
    }

    public function edit($id)
    {
        $modelBarang = new BarangModel();
        $barang = $modelBarang->find($id);
        return view('barang/edit', ['barang' => $barang]);
    }

    public function update($id)
    {
        $harga_beli = str_replace(['Rp', '.', ' '], '', $this->request->getPost('harga_beli'));
        $harga_jual = str_replace(['Rp', '.', ' '], '', $this->request->getPost('harga_jual'));
        $stok = $this->request->getPost('stok');

        if ($harga_beli === '' || $harga_jual === '') {
            session()->setFlashdata('error', 'Harga beli dan harga jual tidak boleh kosong!');
            return redirect()->back()->withInput();
        } elseif ($harga_beli > $harga_jual) {
            session()->setFlashdata('error', 'Harga beli tidak boleh lebih besar dari harga jual!');
            return redirect()->back()->withInput();
        } elseif ($stok === '' || $stok <= 0) {
            session()->setFlashdata('error', 'Stok tidak boleh kosong atau kurang dari 0!');
            return redirect()->back()->withInput();
        }

        $modelBarang = new BarangModel();
        $data = [
            'nama_barang' => $this->request->getPost('nama_barang'),
            'id_kategori' => $this->request->getPost('id_kategori'),
            'harga_beli'  => (int)$harga_beli,
            'harga_jual'  => (int)$harga_jual,
            'stok'        => $this->request->getPost('stok'),
            'barcode'     => $this->request->getPost('barcode'),
        ];
        $modelBarang->update($id, $data);
        session()->setFlashdata('success', 'Barang berhasil diupdate!');
        return redirect()->to('/barang');
    }

    public function delete($id)
    {
        $modelBarang = new BarangModel();
        $modelBarang->delete($id);
        return redirect()->to('/barang');
    }
}
