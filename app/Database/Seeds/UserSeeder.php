<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'username'      => 'admin',
            'password'      => password_hash('admin123', PASSWORD_DEFAULT),
            'nama'  => 'fandi wicaksono',
            'role' => 'pemilik',
            'status' =>  'aktif',
        ];

        // Using Query Builder
        $this->db->table('users')->insert($data);
    }
}
