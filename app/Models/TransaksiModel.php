<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiModel extends Model
{
    protected $table      = 'transaksi';
    protected $primaryKey = 'id';

    protected $allowedFields = ['nominal', 'kategori', 'catatan', 'waktu'];
    protected $useTimestamps = false; // karena kita pakai CURRENT_TIMESTAMP
}