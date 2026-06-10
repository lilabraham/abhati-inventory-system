<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Operasional Abhati</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 font-sans p-8">

    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-800">Sistem Inventaris Bahan Kimia</h1>
            <p class="text-sm text-slate-500">Abhati Group - Local Development Environment</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h2 class="text-lg font-semibold text-slate-700 mb-4">Tambah Produk</h2>

                <form action="<?= base_url('produk/store') ?>" method="POST">
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Nama Bahan Kimia</label>
                        <input type="text" name="nama_produk" required class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 text-sm">
                    </div>
                    <div class="mb-6">
                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Stok Awal</label>
                        <input type="number" name="stok" required min="0" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 text-sm">
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg text-sm transition duration-200">
                        Simpan ke DB
                    </button>
                </form>
            </div>

            <div class="md:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h2 class="text-lg font-semibold text-slate-700 mb-4">Daftar Stok Aktif</h2>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 text-xs font-semibold text-slate-400 uppercase">
                                <th class="py-3 px-2">ID</th>
                                <th class="py-3 px-2">Nama Produk</th>
                                <th class="py-3 px-2 text-center">Stok</th>
                                <th class="py-3 px-2 text-right">Waktu Input</th>
                                <th class="py-3 px-2 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-slate-600 divide-y divide-gray-50">
                            <?php if (empty($all_produk)): ?>
                                <tr>
                                    <td colspan="5" class="py-4 text-center text-slate-400 italic">Belum ada data di database lokal.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($all_produk as $p): ?>
                                    <tr class="hover:bg-slate-50 transition duration-150">
                                        <td class="py-3 px-2 font-mono text-xs text-slate-400">#<?= $p['id'] ?></td>
                                        <td class="py-3 px-2 font-medium text-slate-800"><?= esc($p['nama_produk']) ?></td>
                                        <td class="py-3 px-2 text-center font-bold text-blue-600"><?= $p['stok'] ?></td>
                                        <td class="py-3 px-2 text-right text-xs text-slate-400"><?= $p['created_at'] ?></td>
                                        <td class="py-3 px-2 flex justify-center gap-2">
                                            
                                            <a href="<?= base_url('produk/edit/' . $p['id']) ?>" class="text-xs bg-yellow-100 text-yellow-700 hover:bg-yellow-200 px-3 py-1 rounded font-medium transition">Edit</a>
                                            
                                            <form action="<?= base_url('produk/delete/' . $p['id']) ?>" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                                <button type="submit" class="text-xs bg-red-100 text-red-600 hover:bg-red-200 px-3 py-1 rounded font-medium transition">Hapus</button>
                                            </form>

                                        </td>
                                    </tr> <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</body>

</html>