<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Libraries\JSONResponseBuilder;
use App\Services\UserService;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;

class UserAPI extends BaseController
{
    use ResponseTrait;

    protected UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function index(): ResponseInterface
    {
        if (! auth()->user()->can('users.manage')) {
            return $this->respond(JSONResponseBuilder::make(403, false, 'Akses ditolak.'), 403);
        }

        try {
            $data = $this->userService->list();
        } catch (\Throwable $e) {
            log_message('error', '[UserAPI::index] ' . $e->getMessage());
            return $this->respond(JSONResponseBuilder::make(500, false, 'Terjadi kesalahan server.'), 500);
        }

        return $this->respond(JSONResponseBuilder::make(200, true, 'Data pengguna berhasil diambil.', [
            'data' => $data,
        ]), 200);
    }

    public function create(): ResponseInterface
    {
        if (! auth()->user()->can('users.manage')) {
            return $this->respond(JSONResponseBuilder::make(403, false, 'Akses ditolak.'), 403);
        }

        $data  = $this->request->getJSON(true) ?? [];
        $rules = [
            'username' => 'required|min_length[3]|max_length[30]|is_unique[users.username]',
            'email'    => 'required|valid_email|email_unique',
            'password' => 'required|min_length[8]',
        ];

        if (! $this->validateData($data, $rules)) {
            return $this->respond(JSONResponseBuilder::make(422, false, 'Validasi gagal.', $this->validator->getErrors()), 422);
        }

        $payload = [
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => $data['password'],
        ];

        try {
            $result = $this->userService->create($payload);
        } catch (\Throwable $e) {
            log_message('error', '[UserAPI::create] ' . $e->getMessage());
            return $this->respond(JSONResponseBuilder::make(500, false, 'Terjadi kesalahan server.'), 500);
        }

        if (is_string($result)) {
            return $this->respond(JSONResponseBuilder::make(400, false, $result), 400);
        }

        return $this->respond(JSONResponseBuilder::make(201, true, 'User berhasil dibuat.', [
            'id'       => $result->id,
            'username' => $result->username,
        ]), 201);
    }

    public function ban(int $id): ResponseInterface
    {
        if (! auth()->user()->can('users.manage')) {
            return $this->respond(JSONResponseBuilder::make(403, false, 'Akses ditolak.'), 403);
        }

        try {
            $result = $this->userService->ban($id);
        } catch (\Throwable $e) {
            log_message('error', '[UserAPI::ban] ' . $e->getMessage());
            return $this->respond(JSONResponseBuilder::make(500, false, 'Terjadi kesalahan server.'), 500);
        }

        if (is_string($result)) {
            return $this->respond(JSONResponseBuilder::make(400, false, $result), 400);
        }

        return $this->respond(JSONResponseBuilder::make(200, true, 'User berhasil di-ban.'), 200);
    }

    public function unban(int $id): ResponseInterface
    {
        if (! auth()->user()->can('users.manage')) {
            return $this->respond(JSONResponseBuilder::make(403, false, 'Akses ditolak.'), 403);
        }

        try {
            $result = $this->userService->unban($id);
        } catch (\Throwable $e) {
            log_message('error', '[UserAPI::unban] ' . $e->getMessage());
            return $this->respond(JSONResponseBuilder::make(500, false, 'Terjadi kesalahan server.'), 500);
        }

        if (is_string($result)) {
            return $this->respond(JSONResponseBuilder::make(400, false, $result), 400);
        }

        return $this->respond(JSONResponseBuilder::make(200, true, 'User berhasil di-unban.'), 200);
    }

    public function delete(int $id): ResponseInterface
    {
        if (! auth()->user()->can('users.manage')) {
            return $this->respond(JSONResponseBuilder::make(403, false, 'Akses ditolak.'), 403);
        }

        if (auth()->id() === $id) {
            return $this->respond(JSONResponseBuilder::make(400, false, 'Tidak dapat menghapus akun sendiri.'), 400);
        }

        try {
            $result = $this->userService->delete($id);
        } catch (\Throwable $e) {
            log_message('error', '[UserAPI::delete] ' . $e->getMessage());
            return $this->respond(JSONResponseBuilder::make(500, false, 'Terjadi kesalahan server.'), 500);
        }

        if (is_string($result)) {
            return $this->respond(JSONResponseBuilder::make(400, false, $result), 400);
        }

        return $this->respond(JSONResponseBuilder::make(200, true, 'User berhasil dihapus.'), 200);
    }
}
