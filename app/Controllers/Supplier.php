<?php

namespace App\Controllers;

use App\Models\SupplierModel;
use App\Models\UserModel;

class Supplier extends BaseController
{
    public function index()
    {

        $id_user = session()->get('id_user');
        $modelUser = new UserModel();   
        $user = $modelUser->find($id_user);

        $model = new SupplierModel();
        $suppliers = $model->findAll();

        // Ambil total transaksi pembelian per supplier
        $db = \Config\Database::connect();
        $transaksi = $db->table('pembelian')
            ->select('id_supplier, COUNT(*) as total')
            ->groupBy('id_supplier')
            ->get()->getResultArray();

        // Buat array mapping id_supplier => total
        $totalTransaksi = [];
        foreach ($transaksi as $row) {
            $totalTransaksi[$row['id_supplier']] = $row['total'];
        }

        return view('supplier/index', [
            'user' => $user,
            'suppliers' => $suppliers,
            'totalTransaksi' => $totalTransaksi
        ]);
    }

    public function create()
    {
        return view('supplier/create');
    }

    public function store()
    {
        $model = new SupplierModel();
        $data = [
            'nama_supplier' => $this->request->getPost('nama_supplier'),
            'alamat'        => $this->request->getPost('alamat'),
            'no_telepon'    => $this->request->getPost('no_telepon'),
            'email'         => $this->request->getPost('email'),
        ];
        $model->insert($data);
        session()->setFlashdata('success', 'Supplier berhasil ditambahkan!');
        return redirect()->to('/supplier');
    }

    public function edit($id)
    {
        $model = new SupplierModel();
        $supplier = $model->find($id);
        return view('supplier/edit', ['supplier' => $supplier]);
    }

    public function update($id)
    {
        $model = new SupplierModel();
        $data = [
            'nama_supplier' => $this->request->getPost('nama_supplier'),
            'alamat'        => $this->request->getPost('alamat'),
            'no_telepon'    => $this->request->getPost('no_telepon'),
            'email'         => $this->request->getPost('email'),
        ];
        $model->update($id, $data);
        session()->setFlashdata('success', 'Supplier berhasil diupdate!');
        return redirect()->to('/supplier');
    }

    public function delete($id)
    {
    $db = \Config\Database::connect();
    // Cek apakah ada transaksi pembelian dengan id_supplier ini
    $hasTransaksi = $db->table('pembelian')->where('id_supplier', $id)->countAllResults();

    if ($hasTransaksi > 0) {
        session()->setFlashdata('error', 'Supplier tidak bisa dihapus karena sudah memiliki transaksi pembelian!');
        return redirect()->to('/supplier');
    }

    $model = new SupplierModel();
    $model->delete($id);
    session()->setFlashdata('success', 'Supplier berhasil dihapus!');
    return redirect()->to('/supplier');
    }
}