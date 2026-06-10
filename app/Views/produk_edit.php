<?php /** @var array $produk */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Abhati</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans p-8">

    <div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-slate-800">Edit Data Bahan Kimia</h2>
            <a href="<?= base_url('produk') ?>" class="text-sm text-slate-500 hover:text-slate-800 transition">← Kembali</a>
        </div>
        
        <form action="<?= base_url('produk/update/' . $produk['id']) ?>" method="POST">
            <div class="mb-4">
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Nama Bahan Kimia</label>
                <input type="text" name="nama_produk" value="<?= esc($produk['nama_produk']) ?>" required class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 text-sm">
            </div>
            <div class="mb-6">
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Stok Awal</label>
                <input type="number" name="stok" value="<?= $produk['stok'] ?>" required min="0" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 text-sm">
            </div>
            <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-lg text-sm transition duration-200">
                Update Data
            </button>
        </form>
    </div>

</body>
</html>