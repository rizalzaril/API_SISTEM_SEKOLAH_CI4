<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\GuruModel;

class GuruController extends ResourceController
{

  public function __construct()
  {

    $this->model = new GuruModel();
  }


  public function index()
  {
    $data = $this->model->findAll();

    return $this->respond([
      'status' => 200,
      'message' => 'Data guru berhasil di tampilkan',
      'data_guru' => $data,
    ]);
  }
}
