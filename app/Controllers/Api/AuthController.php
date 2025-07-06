<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController extends ResourceController
{
  // REGISTER USER
  public function register()
  {
    $rules = [
      'name'     => 'required|min_length[3]',
      'email'    => 'required|valid_email|is_unique[users.email]',
      'password' => 'required|min_length[6]',
      'role'     => 'required|in_list[admin,guru,siswa,ortu]'
    ];

    if (!$this->validate($rules)) {
      return $this->failValidationErrors($this->validator->getErrors());
    }

    $userModel = new UserModel();

    $data = [
      'name'     => $this->request->getPost('name'),
      'email'    => $this->request->getPost('email'),
      'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
      'role'     => $this->request->getPost('role')
    ];

    $userModel->save($data);

    return $this->respondCreated([
      'status'  => 201,
      'message' => 'Registrasi berhasil',
      'data'    => $data
    ]);
  }

  // LOGIN USER
  public function login()
  {
    $email = $this->request->getPost('email');
    $password = $this->request->getPost('password');

    $model = new UserModel();
    $user = $model->where('email', $email)->first();

    if (!$user || !password_verify($password, $user['password'])) {
      return $this->respond(['message' => 'Email atau Password anda salah!'], 401);
    }

    $key = getenv('JWT_SECRET');

    if (!$key) {
      return $this->failServerError('JWT secret key is not set. Please configure JWT_SECRET in .env file.');
    }

    $payload = [
      "iss" => "localhost",
      "aud" => "users",
      "iat" => time(),
      "nbf" => time(),
      "exp" => time() + 3600, // 1 jam
      "uid" => $user['id'],
      "role" => $user['role'],
    ];

    $token = JWT::encode($payload, $key, 'HS256');

    return $this->respond([
      'message' => 'Login berhasil',
      'token' => $token
    ]);
  }
}
