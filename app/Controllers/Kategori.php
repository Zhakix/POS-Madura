<?php

namespace App\Controllers;

use App\Models\KategoriModel;
use App\Models\BarangModel;
use App\Models\UserModel;

class Kategori extends BaseController
{
    public function index()
    {
        $id_user = session()->get('id_user');
        $modelUser = new UserModel();
        $user = $modelUser->find($id_user);

        $model = new KategoriModel();
        $kategori = $model->findAll();

        $barangModel = new BarangModel();
        foreach ($kategori as &$kat) {
            $kat['jumlah_barang'] = $barangModel->where('id_kategori', $kat['id_kategori'])->countAllResults();
            unset($kat); // Unset reference to avoid issues in the loop                                                     
        }

        return view('kategori/index', [
            'user' => $user,
            'kategori' => $kategori
        ]);
    }

    public function store()
    {
        $model = new KategoriModel();
        $data = [
            'nama_kategori' => $this->request->getPost('nama_kategori'),
            'deskripsi' => $this->request->getPost('deskripsi'),
        ];
        $model->insert($data);
        session()->setFlashdata('success', 'Kategori berhasil diupdate!');
        return redirect()->to('/kategori');
    }

    public function edit($id)
    {
        $model = new KategoriModel();
        $kategori = $model->find($id);
        return view('kategori/edit', ['kategori' => $kategori]);
    }

    public function update($id)
    {
        $model = new KategoriModel();
        $data = [
            'nama_kategori' => $this->request->getPost('nama_kategori'),
            'deskripsi' => $this->request->getPost('deskripsi'),
        ];
        $model->update($id, $data);
        session()->setFlashdata('success', 'Kategori berhasil diupdate!');
        return redirect()->to('/kategori');
    }

    public function delete($id)
    {
        $barangModel = new BarangModel();
        $jumlahBarang = $barangModel->where('id_kategori', $id)->countAllResults();

        if ($jumlahBarang > 0) {
            session()->setFlashdata('error', 'Kategori tidak dapat dihapus karena masih ada barang yang terkait.');
            return redirect()->to('/kategori');
        }

        $model = new KategoriModel();
        $model->delete($id);
        session()->setFlashdata('success', 'Kategori berhasil dihapus.');
        return redirect()->to('/kategori');
    }
}
