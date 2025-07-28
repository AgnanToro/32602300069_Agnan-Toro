<?php
namespace App\Controllers;
use App\Models\BudgetModel;

class Budget extends BaseController
{
    protected $budgetModel;

    public function __construct()
    {
        $this->budgetModel = new BudgetModel();
    }

    public function index()
    {
        // Ambil semua budget
        $budgets = $this->budgetModel->orderBy('tanggal', 'DESC')->findAll();
        return view('budget', [
            'budgets' => $budgets,
        ]);
    }

    public function tambah()
    {
        // Pastikan timezone Asia/Jakarta
        date_default_timezone_set('Asia/Jakarta');
        $request = $this->request;
        $nominal = (int) $request->getPost('nominal');
        $tanggal = date('Y-m-d');

        if (!$nominal) {
            return redirect()->back()->with('error', 'Nominal budget wajib diisi.');
        }

        // Cek jika sudah ada budget hari ini, update
        $existing = $this->budgetModel->where('tanggal', $tanggal)->first();
        if ($existing) {
            $this->budgetModel->update($existing['id'], [
                'nominal' => $nominal,
            ]);
        } else {
            $this->budgetModel->save([
                'nominal' => $nominal,
                'tanggal' => $tanggal,
            ]);
        }

        return redirect()->to('/budget');
    }
}
