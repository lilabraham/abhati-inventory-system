<?php
/**
 * @var array $asset
 * @var array $repairs
 * @var string $title
 */
?>
<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
    /* ── Soft Badges ── */
    .badge-soft-success  { background: #d1fae5; color: #065f46; }
    .badge-soft-danger   { background: #fee2e2; color: #991b1b; }
    .badge-soft-warning  { background: #fef9c3; color: #854d0e; }
    .badge-soft-secondary{ background: #f1f5f9; color: #475569; }

    /* ── Cards ── */
    .card-premium {
        border: none !important;
        border-radius: 16px !important;
        box-shadow: 0 2px 8px rgba(0,0,0,.04), 0 8px 32px rgba(0,0,0,.06);
    }

    .card-premium .card-header {
        background: #fff;
        border-bottom: 1px solid rgba(0,0,0,.06);
        border-radius: 16px 16px 0 0 !important;
        padding: 1rem 1.25rem;
    }

    /* ── DL list ── */
    .asset-dl dt {
        font-size: .75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #94a3b8;
        padding-top: .55rem;
        padding-bottom: .1rem;
    }

    .asset-dl dd {
        font-size: .9rem;
        color: #1e293b;
        padding-bottom: .35rem;
        border-bottom: 1px solid rgba(0,0,0,.04);
        margin-bottom: 0;
    }

    .asset-dl .row:last-child dd { border-bottom: none; }

    /* ── Repair Table ── */
    .repair-table thead tr {
        background: #f8fafc;
    }

    .repair-table thead th {
        font-size: .68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .07em;
        color: #94a3b8;
        border-bottom: 1px solid #e2e8f0;
        border-top: none;
        padding: .75rem 1rem;
    }

    .repair-table tbody tr {
        border-bottom: 1px solid #f1f5f9;
    }

    .repair-table tbody tr:last-child { border-bottom: none; }

    .repair-table tbody td {
        font-size: .85rem;
        color: #334155;
        padding: .75rem 1rem;
        border: none;
        vertical-align: middle;
    }

    .repair-table.table-hover tbody tr:hover {
        background: #f8faff;
    }

    /* ── Header section ── */
    .page-eyebrow {
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: #94a3b8;
    }

    /* ── Back button ── */
    .btn-back {
        width: 36px;
        height: 36px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #64748b;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        transition: all .18s;
        flex-shrink: 0;
    }

    .btn-back:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
        color: #1e293b;
        transform: translateX(-2px);
    }

    /* ── Add button ── */
    .btn-add-repair {
        font-size: .8rem;
        font-weight: 600;
        letter-spacing: .02em;
        padding: .35rem .85rem;
        border-radius: 9px;
        background: #1e293b;
        color: #fff;
        border: none;
        box-shadow: 0 2px 8px rgba(30,41,59,.2);
        transition: all .18s;
    }

    .btn-add-repair:hover {
        background: #0f172a;
        box-shadow: 0 4px 14px rgba(30,41,59,.28);
        transform: translateY(-1px);
        color: #fff;
    }

    /* ── Total footer ── */
    .repair-total {
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        padding: .65rem 1rem;
        font-size: .82rem;
    }

    /* ── Spec text ── */
    .spec-block {
        background: #f8fafc;
        border-radius: 10px;
        padding: .75rem 1rem;
        font-size: .82rem;
        color: #475569;
        line-height: 1.65;
    }

    /* ── Modal ── */
    .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0,0,0,.18);
    }

    .modal-header {
        border-bottom: 1px solid rgba(0,0,0,.06);
        padding: 1.1rem 1.4rem .9rem;
    }

    .modal-footer {
        border-top: 1px solid rgba(0,0,0,.06);
    }
</style>

<!-- ── Page Header ── -->
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="<?= base_url('data-aset') ?>" class="btn-back">
        <i class="bi bi-arrow-left" style="font-size:.9rem;"></i>
    </a>
    <div>
        <div class="page-eyebrow mb-1">Detail Aset</div>
        <h5 class="fw-bold mb-0 text-dark" style="letter-spacing:-.01em;">
            <?= esc($asset['kode_aset']) ?>
            <span class="fw-normal text-muted fs-6 ms-1">— <?= esc($asset['merk']) ?> <?= esc($asset['model']) ?></span>
        </h5>
    </div>
</div>

<div class="row g-4">

    <!-- ── Informasi Aset ── -->
    <div class="col-lg-5">
        <div class="card card-premium h-100">
            <div class="card-header d-flex align-items-center gap-2">
                <div class="rounded-3 d-flex align-items-center justify-content-center"
                     style="width:28px;height:28px;background:#eff6ff;">
                    <i class="bi bi-info-circle-fill text-primary" style="font-size:.8rem;"></i>
                </div>
                <span class="fw-semibold text-dark" style="font-size:.9rem;">Informasi Aset</span>
            </div>
            <div class="card-body px-4 py-3">
                <dl class="row mb-0 asset-dl">
                    <div class="row w-100 mx-0">
                        <dt class="col-5">Kode Aset</dt>
                        <dd class="col-7"><code class="text-primary fw-semibold"><?= esc($asset['kode_aset']) ?></code></dd>
                    </div>
                    <div class="row w-100 mx-0">
                        <dt class="col-5">Merk</dt>
                        <dd class="col-7 fw-semibold"><?= esc($asset['merk']) ?></dd>
                    </div>
                    <div class="row w-100 mx-0">
                        <dt class="col-5">Model</dt>
                        <dd class="col-7"><?= esc($asset['model']) ?></dd>
                    </div>
                    <div class="row w-100 mx-0">
                        <dt class="col-5">Serial Number</dt>
                        <dd class="col-7">
                            <?php if (!empty($asset['serial_number'])): ?>
                                <code class="text-secondary"><?= esc($asset['serial_number']) ?></code>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif ?>
                        </dd>
                    </div>
                    <div class="row w-100 mx-0">
                        <dt class="col-5">Pengguna</dt>
                        <dd class="col-7"><?= esc($asset['pengguna'] ?? '—') ?></dd>
                    </div>
                    <div class="row w-100 mx-0">
                        <dt class="col-5">Lokasi</dt>
                        <dd class="col-7"><?= esc($asset['lokasi'] ?? '—') ?></dd>
                    </div>
                    <div class="row w-100 mx-0">
                        <dt class="col-5">Kondisi</dt>
                        <dd class="col-7">
                            <?php
                            $badgeClass = match ($asset['kondisi']) {
                                'baik'            => 'badge-soft-success',
                                'rusak'           => 'badge-soft-danger',
                                'dalam_perbaikan' => 'badge-soft-warning',
                                default           => 'badge-soft-secondary',
                            };
                            $label = match ($asset['kondisi']) {
                                'baik'            => 'Baik',
                                'rusak'           => 'Rusak',
                                'dalam_perbaikan' => 'Dalam Perbaikan',
                                'tidak_aktif'     => 'Tidak Aktif',
                                default           => $asset['kondisi'],
                            };
                            ?>
                            <span class="badge rounded-pill fw-semibold px-3 py-1 <?= $badgeClass ?>">
                                <?= $label ?>
                            </span>
                        </dd>
                    </div>
                    <div class="row w-100 mx-0">
                        <dt class="col-5">Tanggal Beli</dt>
                        <dd class="col-7">
                            <?= $asset['tanggal_beli']
                                ? date('d M Y', strtotime($asset['tanggal_beli']))
                                : '—' ?>
                        </dd>
                    </div>
                    <div class="row w-100 mx-0">
                        <dt class="col-5">Harga Beli</dt>
                        <dd class="col-7 fw-semibold">
                            <?= $asset['harga_beli']
                                ? 'Rp ' . number_format((float) $asset['harga_beli'], 0, ',', '.')
                                : '—' ?>
                        </dd>
                    </div>
                </dl>

                <?php if (!empty($asset['spesifikasi'])): ?>
                    <hr class="my-3" style="border-color:rgba(0,0,0,.06);">
                    <div class="text-muted mb-2" style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;">
                        Spesifikasi
                    </div>
                    <div class="spec-block"><?= nl2br(esc($asset['spesifikasi'])) ?></div>
                <?php endif ?>
            </div>
        </div>
    </div>

    <!-- ── Riwayat Perbaikan ── -->
    <div class="col-lg-7">
        <div class="card card-premium h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="width:28px;height:28px;background:#fffbeb;">
                        <i class="bi bi-tools text-warning" style="font-size:.8rem;"></i>
                    </div>
                    <span class="fw-semibold text-dark" style="font-size:.9rem;">Riwayat Perbaikan</span>
                    <span class="badge rounded-pill badge-soft-secondary px-2"><?= count($repairs) ?></span>
                </div>
                <button class="btn-add-repair" data-bs-toggle="modal" data-bs-target="#modalRepair">
                    <i class="bi bi-plus-lg me-1"></i> Tambah
                </button>
            </div>
            <div class="card-body p-0">
                <?php if (empty($repairs)): ?>
                    <div class="text-center text-muted py-5">
                        <div class="mx-auto mb-3 rounded-3 d-inline-flex align-items-center justify-content-center"
                             style="width:52px;height:52px;background:#f8fafc;">
                            <i class="bi bi-clipboard-check fs-4 text-secondary"></i>
                        </div>
                        <div class="fw-semibold text-secondary mb-1" style="font-size:.9rem;">Belum ada riwayat</div>
                        <div style="font-size:.8rem;">Tambahkan riwayat perbaikan pertama</div>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table repair-table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Tanggal</th>
                                    <th>Deskripsi</th>
                                    <th>Teknisi</th>
                                    <th>Biaya</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($repairs as $r): ?>
                                    <?php
                                    $statusClass = match ($r['status_akhir']) {
                                        'selesai' => 'badge-soft-success',
                                        'pending' => 'badge-soft-warning',
                                        'gagal'   => 'badge-soft-danger',
                                        default   => 'badge-soft-secondary',
                                    };
                                    ?>
                                    <tr>
                                        <td class="ps-4 text-nowrap fw-semibold" style="font-size:.82rem;color:#64748b;">
                                            <?= date('d/m/Y', strtotime($r['tanggal'])) ?>
                                        </td>
                                        <td style="max-width:220px;">
                                            <span class="text-truncate d-block" style="max-width:200px;">
                                                <?= esc($r['deskripsi']) ?>
                                            </span>
                                        </td>
                                        <td class="text-nowrap"><?= esc($r['teknisi'] ?? '—') ?></td>
                                        <td class="text-nowrap fw-semibold">
                                            Rp <?= number_format((float) $r['biaya'], 0, ',', '.') ?>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill fw-semibold px-3 py-1 <?= $statusClass ?>">
                                                <?= ucfirst(esc($r['status_akhir'])) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>

                    <?php $totalBiaya = array_sum(array_column($repairs, 'biaya')); ?>
                    <div class="repair-total d-flex justify-content-end align-items-center gap-2">
                        <span class="text-muted">Total biaya perbaikan</span>
                        <span class="fw-bold text-dark">
                            Rp <?= number_format($totalBiaya, 0, ',', '.') ?>
                        </span>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>

<!-- ── Modal Tambah Riwayat Perbaikan ── -->
<div class="modal fade" id="modalRepair" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="width:30px;height:30px;background:#fffbeb;">
                        <i class="bi bi-tools text-warning" style="font-size:.82rem;"></i>
                    </div>
                    <h6 class="modal-title fw-bold mb-0">Tambah Riwayat Perbaikan</h6>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formRepair">
                <input type="hidden" name="asset_id" value="<?= (int) $asset['id'] ?>">
                <div class="modal-body px-4 py-3">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold" style="font-size:.82rem;">
                                Tanggal <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="tanggal" class="form-control rounded-3"
                                   value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold" style="font-size:.82rem;">
                                Deskripsi Kerusakan <span class="text-danger">*</span>
                            </label>
                            <textarea name="deskripsi" class="form-control rounded-3" rows="3"
                                      placeholder="Jelaskan kerusakan dan tindakan yang dilakukan..." required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.82rem;">Teknisi</label>
                            <input name="teknisi" class="form-control rounded-3" placeholder="Nama teknisi">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.82rem;">Status Akhir</label>
                            <select name="status_akhir" class="form-select rounded-3">
                                <option value="selesai">Selesai</option>
                                <option value="pending">Pending</option>
                                <option value="gagal">Gagal</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold" style="font-size:.82rem;">Biaya (Rp)</label>
                            <input type="number" name="biaya" class="form-control rounded-3" value="0" min="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer px-4 py-3 gap-2">
                    <button type="button" class="btn btn-sm rounded-3 px-3"
                            style="background:#f1f5f9;color:#64748b;border:none;font-weight:600;"
                            data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-sm btn-add-repair px-4">
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

        const res = await apiFetch('<?= base_url('api/repairs') ?>', {
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