<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use Config\Services;

class AuthFilter implements FilterInterface
{
  public function before(RequestInterface $request, $arguments = null)
  {
    $header = $request->getHeaderLine('Authorization');
    if (!$header) {
      return Services::response()->setJSON(['message' => 'Token required'])->setStatusCode(401);
    }

    $token = str_replace('Bearer ', '', $header);

    try {
      $decoded = JWT::decode($token, new Key(getenv('JWT_SECRET'), 'HS256'));
      // Simpan user info ke request global
      $request->user = $decoded;
    } catch (\Exception $e) {
      return Services::response()->setJSON(['message' => 'Token invalid'])->setStatusCode(401);
    }
  }

  public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
