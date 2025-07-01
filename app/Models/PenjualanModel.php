<?php

namespace App\Models;
use CodeIgniter\Model;

class PenjualanModel extends Model
{
    protected $table = 'penjualan';
    protected $primaryKey = 'id_penjualan';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields = [ 'id_user', 'tanggal_penjualan', 'total', 'metode_pembayaran', 'jumlah_pembayaran', 'kembalian'];
}