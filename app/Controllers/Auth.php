<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        // Jika sudah login, redirect ke home
        if (session()->get('login')) {
            return redirect()->to('/');
        }

        if ($this->request->getMethod() === 'POST') {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            $modelUser = new UserModel();
            $user = $modelUser->where('username', $username)->first();

            // Pastikan $user berupa array
            if ($user && password_verify($password, $user['password'])) {
                session()->set([
                    'login'    => true,
                    'id_user'  => $user['id_user'],
                    'username' => $user['username'],
                    'nama'     => $user['nama'],
                    'role'     => $user['role'],
                    'foto'     => $user['foto'] ?? 'default.png',
                ]);
                return redirect()->to('/');
            }

            session()->setFlashdata('error', 'Username atau password salah.');
            return redirect()->back()->withInput();
        }

        return view('auth/login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Anda telah berhasil logout.');
    }
}