<?php

namespace App\Controllers;

use App\Models\UserModel;

class Users extends BaseController
{
    public function index()
    {   
        $model = new UserModel();
        $users = $model->withDeleted()->findAll();

        return view('users/index', ['users' => $users]);
    }

    public function create()
    {
        return view('users/create');
    }

    public function store()
    {
        $model = new UserModel();
        $username = $this->request->getPost('username');

        // Cek apakah username sudah ada
        if ($model->withDeleted()->where('username', $username)->countAllResults() > 0) {
            session()->setFlashdata('error', 'Username sudah pernah terdaftar!');
            return redirect()->to('/users');
        }

        $data = [
            'username' => $username,
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'nama'     => $this->request->getPost('nama'),
            'role'     => $this->request->getPost('role'),
        ];
        $model->insert($data);
        session()->setFlashdata('success', 'User berhasil ditambahkan!');
        return redirect()->to('/users');
    }

    public function edit($id)
    {
        $model = new UserModel();
        $user = $model->find($id);
        return view('users/edit', ['user' => $user]);
    }

    public function update($id)
    {
        $model = new UserModel();
        $data = [
            'username' => $this->request->getPost('username'),
            'nama'     => $this->request->getPost('nama'),
            'role'     => $this->request->getPost('role'),
        ];
        if ($this->request->getPost('password')) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }
        $model->update($id, $data);
        session()->setFlashdata('success', 'User berhasil diupdate!');
        return redirect()->to('/users');
    }

    public function delete($id)
    {
        $model = new UserModel();
        $model->delete($id);
        session()->setFlashdata('success', 'User berhasil dihapus!');
        return redirect()->to('/users');
    }
}