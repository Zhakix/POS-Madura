<?php

namespace App\Models;
use CodeIgniter\Model;

class BarangModel extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id_barang';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields = [
        'nama_barang', 
        'id_kategori', 
        'stok',
        'harga_jual',
        'harga_beli',
        'barcode', 
    ];
}
