<?php
namespace App\Models;
use CodeIgniter\Model;

class BudgetModel extends Model
{
    protected $table = 'budget';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nominal', 'tanggal'];
    public $timestamps = false;
}
