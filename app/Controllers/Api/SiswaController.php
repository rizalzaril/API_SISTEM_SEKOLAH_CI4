<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\SiswaModel;

class SiswaController extends ResourceController
{
  // protected $modelName = 'App\Models\SiswaModel';
  // protected $format    = 'json';

  public function __construct()
  {
    // Inisialisasi model secara manual
    $this->model = new SiswaModel();
  }

  public function index()
  {
    $data = $this->model->findAll();

    return $this->respond([
      'status'  => true,
      'message' => 'Data siswa berhasil diambil',
      'data'    => $data
    ]);
  }
}
