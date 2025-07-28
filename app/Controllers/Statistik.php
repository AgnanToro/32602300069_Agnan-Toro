<?php
namespace App\Controllers;
use App\Models\TransaksiModel;

class Statistik extends BaseController
{
    public function index()
    {
        $model = new TransaksiModel();
        // Pengeluaran per bulan (tahun berjalan)
        $year = date('Y');
        $monthly = $model->select("MONTH(waktu) as bulan, SUM(nominal) as total")
            ->where("YEAR(waktu)", $year)
            ->groupBy("MONTH(waktu)")
            ->orderBy("bulan")
            ->findAll();
        // Pengeluaran per tahun
        $yearly = $model->select("YEAR(waktu) as tahun, SUM(nominal) as total")
            ->groupBy("YEAR(waktu)")
            ->orderBy("tahun")
            ->findAll();
        // Pengeluaran per kategori (bulan berjalan)
        $category = $model->select("kategori, SUM(nominal) as total")
            ->where("YEAR(waktu)", $year)
            ->where("MONTH(waktu)", date('m'))
            ->groupBy("kategori")
            ->findAll();
        return view('statistik', [
            'monthly' => $monthly,
            'yearly' => $yearly,
            'category' => $category,
        ]);
    }
}
