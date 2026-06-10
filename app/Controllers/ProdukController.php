<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProdukModel;

class ProdukController extends BaseController
{
    // 1. Menampilkan halaman form dan daftar produk
    public function index()
    {
        $model = new ProdukModel();
        $data['all_produk'] = $model->findAll(); // Ambil semua data produk dari DB

        return view('produk_view', $data);
    }

    // 2. Memproses inputan dari form untuk disimpan ke DB
    public function store()
    {
        $model = new ProdukModel();

        // Tangkap data dari form frontend
        $dataSimpan = [
            'nama_produk' => $this->request->getPost('nama_produk'),
            'stok'        => $this->request->getPost('stok'),
        ];

        // Validasi simpel: Pastikan inputan tidak kosong
        if (!empty($dataSimpan['nama_produk'])) {
            $model->insert($dataSimpan); // Perintah insert ke DB lokal via model
        }

        // Setelah sukses simpan, kembali ke halaman utama
        return redirect()->to('/produk');
    }
    // 3. Menghapus data dari DB berdasarkan ID
    public function delete(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $model = new ProdukModel();

        // Perintah hapus data lokal
        $model->delete($id);

        // Kembali ke halaman utama
        return redirect()->to('/produk');
    }
    // 4. Menampilkan halaman form edit
    public function edit(int $id)
    {
        $model = new ProdukModel();
        $data['produk'] = $model->find($id); // Cari data berdasarkan ID

        // Jika user iseng masukin ID ngawur di URL, kembalikan ke halaman utama
        if (empty($data['produk'])) {
            return redirect()->to('/produk');
        }

        return view('produk_edit', $data);
    }

    // 5. Memproses update data ke DB
    public function update(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $model = new ProdukModel();
        
        // Tangkap data baru dari form edit
        $dataUpdate = [
            'nama_produk' => $this->request->getPost('nama_produk'),
            'stok'        => $this->request->getPost('stok'),
        ];

        // Eksekusi update ke database lokal
        $model->update($id, $dataUpdate);

        // Kembali ke halaman utama setelah sukses
        return redirect()->to('/produk');
    }
}