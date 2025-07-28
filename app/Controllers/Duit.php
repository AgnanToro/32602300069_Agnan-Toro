<?php
namespace App\Controllers;
use App\Models\TransaksiModel;

class Duit extends BaseController

   {
    protected $transaksiModel;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiModel();
    }

    public function index()
    {
        // Ambil semua transaksi hari ini
        $today = date('Y-m-d');
        $transaksi = $this->transaksiModel
            ->where('DATE(waktu)', $today)
            ->orderBy('waktu', 'DESC')
            ->findAll();

        // Ambil budget hari ini
        $budgetModel = new \App\Models\BudgetModel();
        $budget = $budgetModel->where('tanggal', $today)->first();
        $budgetNominal = $budget ? (int)$budget['nominal'] : 0;

        // Hitung total pengeluaran hari ini
        $totalPengeluaran = array_sum(array_column($transaksi, 'nominal'));

        // Hitung persentase
        $persen = $budgetNominal > 0 ? round($totalPengeluaran / $budgetNominal * 100) : 0;

        return view('beranda', [
            'transaksi' => $transaksi,
            'budget' => $budgetNominal,
            'totalPengeluaran' => $totalPengeluaran,
            'persen' => $persen,
        ]);
    }

    public function tambah()
    {
        $request = $this->request;

        $data = [
            'nominal'  => (int) $request->getPost('nominal'),
            'kategori' => $request->getPost('kategori'),
            'catatan'  => $request->getPost('catatan'),
            'waktu'    => date('Y-m-d H:i:s'), // waktu input
        ];

        // Validasi sederhana
        if (!$data['nominal'] || !$data['kategori']) {
            return redirect()->back()->with('error', 'Nominal dan kategori wajib diisi.');
        }

        $this->transaksiModel->save($data);

        return redirect()->to('/'); // redirect ke halaman utama setelah tambah
    }
    public function hapus($id)
{
    $this->transaksiModel->delete($id);
    return redirect()->to('/');
}
}