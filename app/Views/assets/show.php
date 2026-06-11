<?php
/**
 * @var array $asset
 * @var array $repairs
 * @var string $title
 */
?>
<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="/assets" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h4 class="fw-bold mb-0">Detail Aset: <?= esc($asset['kode_aset']) ?></h4>
        <small class="text-muted"><?= esc($asset['merk']) ?> — <?= esc($asset['model']) ?></small>
    </div>
</div>

<div class="row g-4">

    <!-- Informasi Aset -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-semibold border-bottom">
                <i class="bi bi-info-circle me-2 text-primary"></i>Informasi Aset
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5 text-muted">Kode Aset</dt>
                    <dd class="col-7"><code class="text-primary"><?= esc($asset['kode_aset']) ?></code></dd>

                    <dt class="col-5 text-muted">Merk</dt>
                    <dd class="col-7 fw-semibold"><?= esc($asset['merk']) ?></dd>

                    <dt class="col-5 text-muted">Model</dt>
                    <dd class="col-7"><?= esc($asset['model']) ?></dd>

                    <dt class="col-5 text-muted">Serial Number</dt>
                    <dd class="col-7"><?= esc($asset['serial_number'] ?? '-') ?></dd>

                    <dt class="col-5 text-muted">Pengguna</dt>
                    <dd class="col-7"><?= esc($asset['pengguna'] ?? '-') ?></dd>

                    <dt class="col-5 text-muted">Lokasi</dt>
                    <dd class="col-7"><?= esc($asset['lokasi'] ?? '-') ?></dd>

                    <dt class="col-5 text-muted">Kondisi</dt>
                    <dd class="col-7">
                        <?php
                        $badge = match ($asset['kondisi']) {
                            'baik'             => 'success',
                            'rusak'            => 'danger',
                            'dalam_perbaikan'  => 'warning',
                            default            => 'secondary',
                        };
                        $label = match ($asset['kondisi']) {
                            'baik'             => 'Baik',
                            'rusak'            => 'Rusak',
                            'dalam_perbaikan'  => 'Dalam Perbaikan',
                            'tidak_aktif'      => 'Tidak Aktif',
                            default            => $asset['kondisi'],
                        };
                        ?>
                        <span class="badge bg-<?= $badge ?>"><?= $label ?></span>
                    </dd>

                    <dt class="col-5 text-muted">Tanggal Beli</dt>
                    <dd class="col-7">
                        <?= $asset['tanggal_beli']
                            ? date('d M Y', strtotime($asset['tanggal_beli']))
                            : '-' ?>
                    </dd>

                    <dt class="col-5 text-muted">Harga Beli</dt>
                    <dd class="col-7">
                        <?= $asset['harga_beli']
                            ? 'Rp ' . number_format((float) $asset['harga_beli'], 0, ',', '.')
                            : '-' ?>
                    </dd>
                </dl>

                <?php if (!empty($asset['spesifikasi'])): ?>
                    <hr>
                    <div class="text-muted small mb-1">Spesifikasi:</div>
                    <p class="mb-0 small"><?= nl2br(esc($asset['spesifikasi'])) ?></p>
                <?php endif ?>
            </div>
        </div>
    </div>

    <!-- Riwayat Perbaikan -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom">
                <span class="fw-semibold">
                    <i class="bi bi-tools me-2 text-warning"></i>Riwayat Perbaikan
                    <span class="badge bg-secondary ms-1"><?= count($repairs) ?></span>
                </span>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalRepair">
                    <i class="bi bi-plus-lg me-1"></i> Tambah
                </button>
            </div>
            <div class="card-body p-0">
                <?php if (empty($repairs)): ?>
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-clipboard-check fs-2 d-block mb-2"></i>
                        Belum ada riwayat perbaikan
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Tanggal</th>
                                    <th>Deskripsi</th>
                                    <th>Teknisi</th>
                                    <th>Biaya</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($repairs as $r): ?>
                                    <?php
                                    $statusBadge = match ($r['status_akhir']) {
                                        'selesai' => 'success',
                                        'pending' => 'warning',
                                        'gagal'   => 'danger',
                                        default   => 'secondary',
                                    };
                                    ?>
                                    <tr>
                                        <td class="ps-3 text-nowrap">
                                            <?= date('d/m/Y', strtotime($r['tanggal'])) ?>
                                        </td>
                                        <td><?= esc($r['deskripsi']) ?></td>
                                        <td><?= esc($r['teknisi'] ?? '-') ?></td>
                                        <td class="text-nowrap">
                                            Rp <?= number_format((float) $r['biaya'], 0, ',', '.') ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $statusBadge ?>">
                                                <?= ucfirst(esc($r['status_akhir'])) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Total Biaya -->
                    <?php
                    $totalBiaya = array_sum(array_column($repairs, 'biaya'));
                    ?>
                    <div class="px-3 py-2 border-top bg-light text-end">
                        <small class="text-muted">Total biaya perbaikan: </small>
                        <strong>Rp <?= number_format($totalBiaya, 0, ',', '.') ?></strong>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Riwayat Perbaikan -->
<div class="modal fade" id="modalRepair" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-tools me-2 text-warning"></i>Tambah Riwayat Perbaikan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formRepair">
                <input type="hidden" name="asset_id" value="<?= (int) $asset['id'] ?>">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control"
                                   value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Deskripsi Kerusakan <span class="text-danger">*</span></label>
                            <textarea name="deskripsi" class="form-control" rows="3"
                                      placeholder="Jelaskan kerusakan dan tindakan yang dilakukan..." required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Teknisi</label>
                            <input name="teknisi" class="form-control" placeholder="Nama teknisi">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status Akhir</label>
                            <select name="status_akhir" class="form-select">
                                <option value="selesai">Selesai</option>
                                <option value="pending">Pending</option>
                                <option value="gagal">Gagal</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Biaya (Rp)</label>
                            <input type="number" name="biaya" class="form-control" value="0" min="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    document.getElementById('formRepair').addEventListener('submit', async e => {
        e.preventDefault();
        const btn = e.target.querySelector('[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';

        const res = await apiFetch('/api/repairs', {
            method: 'POST',
            body: JSON.stringify(Object.fromEntries(new FormData(e.target))),
        });

        if (!res) return;

        if (res.ok) {
            location.reload();
        } else {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-save me-1"></i> Simpan';
            const json = await res.json();
            alert(Object.values(json.errors ?? {}).join('\n') || 'Terjadi kesalahan.');
        }
    });
</script>
<?= $this->endSection() ?>