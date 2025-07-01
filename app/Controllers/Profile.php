<?php

namespace App\Controllers;
use App\Models\UserModel;

class Profile extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $user = $userModel->find(session()->get('id_user'));
        return view('profile/index', ['user' => $user]);
    }

    public function update()
    {
        $userModel = new UserModel();
        $id = session()->get('id_user');
        $nama = $this->request->getPost('nama');

        // Cek apakah ada data yang dikirim
        $data = [];
        if ($nama) {
            $data['nama'] = $nama;
        }

        // Tambahkan field lain jika ada

        if (!empty($data)) {
            $userModel->update($id, $data);
            session()->set('nama', $nama);
            return redirect()->to('/profile')->with('success', 'Profil berhasil diupdate.');
        } else {
            return redirect()->to('/profile')->with('error', 'Tidak ada data yang diubah.');
        }
    }

    public function upload_foto()
    {
        $userModel = new UserModel();
        $id = session()->get('id_user');
        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $newName = $foto->getRandomName();
            $foto->move('writable/uploads/user', $newName);
            $userModel->update($id, ['foto' => $newName]);
            session()->set('foto', $newName);
        }
        return redirect()->to('/profile')->with('success', 'Foto profil berhasil diupdate.');
    }

    public function ganti_password()
    {
        $userModel = new UserModel();
        $id = session()->get('id_user');
        $password = $this->request->getPost('password');
        $password_confirm = $this->request->getPost('password_confirm');

        if ($password !== $password_confirm) {
            return redirect()->to('/profile')->with('error', 'Password tidak sama.');
        }

        $userModel->update($id, ['password' => password_hash($password, PASSWORD_DEFAULT)]);
        return redirect()->to('/profile')->with('success', 'Password berhasil diganti.');
    }
}