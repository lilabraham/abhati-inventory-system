<?php

/** @var int $asset_id */
?>


<style>
    /* ── Soft Badges ── */
    .badge-soft-success {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-soft-danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-soft-warning {
        background: #fef9c3;
        color: #854d0e;
    }

    .badge-soft-secondary {
        background: #f1f5f9;
        color: #475569;
    }

    /* ── Cards ── */
    .card-premium {
        border: none !important;
        border-radius: 16px !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .04), 0 8px 32px rgba(0, 0, 0, .06);
    }

    .card-premium .card-header {
        background: #fff;
        border-bottom: 1px solid rgba(0, 0, 0, .06);
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
        border-bottom: 1px solid rgba(0, 0, 0, .04);
        margin-bottom: 0;
    }

    .asset-dl .row:last-child dd {
        border-bottom: none;
    }

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

    .repair-table tbody tr:last-child {
        border-bottom: none;
    }

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
        box-shadow: 0 1px 4px rgba(0, 0, 0, .06);
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
        box-shadow: 0 2px 8px rgba(30, 41, 59, .2);
        transition: all .18s;
    }

    .btn-add-repair:hover {
        background: #0f172a;
        box-shadow: 0 4px 14px rgba(30, 41, 59, .28);
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
        box-shadow: 0 20px 60px rgba(0, 0, 0, .18);
    }

    .modal-header {
        border-bottom: 1px solid rgba(0, 0, 0, .06);
        padding: 1.1rem 1.4rem .9rem;
    }

    .modal-footer {
        border-top: 1px solid rgba(0, 0, 0, .06);
    }
</style>

<!-- ── Page Header ── -->
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="<?= base_url('data-aset') ?>" class="btn-back">
        <i class="bi bi-arrow-left" style="font-size:.9rem;"></i>
    </a>
    <div>
        <div class="page-eyebrow mb-1">Detail Aset</div>
        <h5 class="fw-bold mb-0 text-dark" style="letter-spacing:-.01em;" id="assetPageTitle">
            <span class="placeholder col-3"></span>
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
            <div class="card-body px-4 py-3" id="assetInfoBody">
                <div class="text-center py-4">
                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                </div>
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
                    <span class="badge rounded-pill badge-soft-secondary px-2" id="repairCount">0</span>
                </div>
                <button class="btn-add-repair" data-bs-toggle="modal" data-bs-target="#modalRepair">
                    <i class="bi bi-plus-lg me-1"></i> Tambah
                </button>
            </div>
            <div class="card-body p-0" id="repairBody">
                <div class="text-center py-4">
                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                </div>
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
                <input type="hidden" name="asset_id" id="formAssetId" value="<?= (int) $asset_id ?>">
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
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.82rem;">Kondisi Laptop Setelah</label>
                            <select name="kondisi_akhir" class="form-select rounded-3">
                                <option value="">— Tidak Diubah —</option>
                                <option value="baik">Baik</option>
                                <option value="rusak">Rusak</option>
                                <option value="dalam_perbaikan">Dalam Perbaikan</option>
                                <option value="tidak_aktif">Tidak Aktif</option>
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
                        data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-add-repair px-4">
                        <i class="bi bi-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const BASE_URL = '<?= base_url() ?>';
    const ASSET_ID = <?= (int) $asset_id ?>;

    const kondisiMap = Object.fromEntries(
        Object.entries(window.KONDISI_CONFIG).map(([k, v]) => [k, {
            cls: `badge-soft-${v.cls}`,
            label: v.label
        }])
    );

    const statusMap = {
        selesai: {
            cls: 'badge-soft-success',
            label: 'Selesai'
        },
        pending: {
            cls: 'badge-soft-warning',
            label: 'Pending'
        },
        gagal: {
            cls: 'badge-soft-danger',
            label: 'Gagal'
        },
    };

    const escHtml = s => s == null ?
        '' :
        String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;')
        .replace(/>/g, '&gt;').replace(/"/g, '&quot;');

    const showToast = (msg, type = 'danger') => {
        // Gunakan Bootstrap toast dari layout jika ada,
        // fallback ke alert untuk show.php yang tidak punya #toastNotif
        const el = document.getElementById('toastNotif');
        if (el) {
            const msgEl = document.getElementById('toastMessage');
            el.className = `toast align-items-center border-0 text-white bg-${type}`;
            msgEl.textContent = msg;
            new bootstrap.Toast(el, {
                delay: 3500
            }).show();
        } else {
            alert(msg);
        }
    };

    const fmt = n => 'Rp ' + Number(n || 0).toLocaleString('id-ID');
    const fmtDate = s => s ? new Date(s).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    }) : '—';

    async function loadAsset() {
        const res = await apiFetch(`${BASE_URL}api/assets/${ASSET_ID}`);
        if (!res) {
            document.getElementById('assetInfoBody').innerHTML =
                `<div class="text-center text-muted py-4">Gagal memuat data aset.</div>`;
            return;
        }

        const json = await res.json();
        if (!json.success) {
            document.getElementById('assetInfoBody').innerHTML =
                `<div class="text-center text-muted py-4">${json.message ?? 'Gagal memuat data aset.'}</div>`;
            return;
        }

        const a = json.data;

        // Update page title
        document.getElementById('assetPageTitle').innerHTML =
            `${escHtml(a.kode_aset)} <span class="fw-normal text-muted fs-6 ms-1">— ${escHtml(a.merk)} ${escHtml(a.model)}</span>`;

        const k = kondisiMap[a.kondisi] ?? {
            cls: 'badge-soft-secondary',
            label: escHtml(a.kondisi)
        };

        document.getElementById('assetInfoBody').innerHTML = `
        <dl class="row mb-0 asset-dl">
            <div class="row w-100 mx-0">
                <dt class="col-5">Kode Aset</dt>
                <dd class="col-7"><code class="text-primary fw-semibold">${escHtml(a.kode_aset)}</code></dd>
            </div>
            <div class="row w-100 mx-0">
                <dt class="col-5">Merk</dt>
                <dd class="col-7 fw-semibold">${escHtml(a.merk)}</dd>
            </div>
            <div class="row w-100 mx-0">
                <dt class="col-5">Model</dt>
                <dd class="col-7">${escHtml(a.model)}</dd>
            </div>
            <div class="row w-100 mx-0">
                <dt class="col-5">Serial Number</dt>
                <dd class="col-7">${escHtml(a.serial_number)
                    ? `<code class="text-secondary">${escHtml(a.serial_number)}</code>`
                    : '<span class="text-muted">—</span>'}</dd>
            </div>
            <div class="row w-100 mx-0">
                <dt class="col-5">Pengguna</dt>
                <dd class="col-7">${a.pengguna ? escHtml(a.pengguna) : '—'}</dd>
            </div>
            <div class="row w-100 mx-0">
                <dt class="col-5">Lokasi</dt>
                <dd class="col-7">${a.lokasi ? escHtml(a.lokasi) : '—'}</dd>
            </div>
            <div class="row w-100 mx-0">
                <dt class="col-5">Kondisi</dt>
                <dd class="col-7">
                    <span class="badge rounded-pill fw-semibold px-3 py-1 ${k.cls}">${k.label}</span>
                </dd>
            </div>
            <div class="row w-100 mx-0">
                <dt class="col-5">Tanggal Beli</dt>
                <dd class="col-7">${fmtDate(a.tanggal_beli)}</dd>
            </div>
            <div class="row w-100 mx-0">
                <dt class="col-5">Harga Beli</dt>
                <dd class="col-7 fw-semibold">${a.harga_beli ? fmt(a.harga_beli) : '—'}</dd>
            </div>
        </dl>
        ${escHtml(a.spesifikasi) ? `
            <hr class="my-3" style="border-color:rgba(0,0,0,.06);">
            <div class="text-muted mb-2" style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;">Spesifikasi</div>
            <div class="spec-block">${escHtml(a.spesifikasi).replace(/\n/g, '<br>')}</div>
        ` : ''}`;
    }

    async function loadRepairs() {
        const res = await apiFetch(`${BASE_URL}api/assets/${ASSET_ID}/repairs`);
        if (!res) return;

        const json = await res.json();
        if (!json.success) return;

        const repairs = Array.isArray(json.data) ? json.data : (json.data?.data ?? []);
        document.getElementById('repairCount').textContent = repairs.length;

        if (repairs.length === 0) {
            document.getElementById('repairBody').innerHTML = `
            <div class="text-center text-muted py-5">
                <div class="mx-auto mb-3 rounded-3 d-inline-flex align-items-center justify-content-center"
                     style="width:52px;height:52px;background:#f8fafc;">
                    <i class="bi bi-clipboard-check fs-4 text-secondary"></i>
                </div>
                <div class="fw-semibold text-secondary mb-1" style="font-size:.9rem;">Belum ada riwayat</div>
                <div style="font-size:.8rem;">Tambahkan riwayat perbaikan pertama</div>
            </div>`;
            return;
        }

        const totalBiaya = repairs.reduce((sum, r) => sum + Number(r.biaya || 0), 0);

        const rows = repairs.map(r => {
            const s = statusMap[r.status_akhir] ?? {
                cls: 'badge-soft-secondary',
                label: escHtml(r.status_akhir)
            };
            const k = r.kondisi_akhir ?
                (kondisiMap[r.kondisi_akhir] ?? {
                    cls: 'badge-soft-secondary',
                    label: escHtml(r.kondisi_akhir)
                }) :
                null;

            return `
    <tr>
        <td class="ps-4 text-nowrap fw-semibold" style="font-size:.82rem;color:#64748b;">
            ${fmtDate(r.tanggal)}
        </td>
        <td style="max-width:220px;">
            <span class="text-truncate d-block" style="max-width:200px;">${escHtml(r.deskripsi)}</span>
        </td>
        <td class="text-nowrap">${r.teknisi ? escHtml(r.teknisi) : '—'}</td>
        <td class="text-nowrap fw-semibold">${fmt(r.biaya)}</td>
        <td><span class="badge rounded-pill fw-semibold px-3 py-1 ${s.cls}">${s.label}</span></td>
        <td>${k
            ? `<span class="badge rounded-pill fw-semibold px-3 py-1 ${k.cls}">${k.label}</span>`
            : '<span class="text-muted">—</span>'
        }</td>
    </tr>`;
        }).join('');

        document.getElementById('repairBody').innerHTML = `
        <div class="table-responsive">
            <table class="table repair-table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Tanggal</th>
                        <th>Deskripsi</th>
                        <th>Teknisi</th>
                        <th>Biaya</th>
                        <th>Status</th>
                        <th>Kondisi Akhir</th>
                    </tr>
                </thead>
                <tbody>${rows}</tbody>
            </table>
        </div>
        <div class="repair-total d-flex justify-content-end align-items-center gap-2">
            <span class="text-muted">Total biaya perbaikan</span>
            <span class="fw-bold text-dark">${fmt(totalBiaya)}</span>
        </div>`;
    }

    // ── Form submit repair ──
    document.getElementById('formRepair').addEventListener('submit', async e => {
        e.preventDefault();
        const btn = e.target.querySelector('[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';

        const res = await apiFetch(`${BASE_URL}api/repairs`, {
            method: 'POST',
            body: JSON.stringify(Object.fromEntries(new FormData(e.target))),
        });

        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-save me-1"></i> Simpan';
        if (!res) return;

        const json = await res.json();
        if (json.success) {
            bootstrap.Modal.getInstance(document.getElementById('modalRepair')).hide();
            e.target.reset();
            document.querySelector('[name="tanggal"]').value = new Date().toISOString().split('T')[0];
            loadRepairs();
        } else {
            const errMsg = json.data ? Object.values(json.data).join('\n') : json.message;
            showToast(errMsg || 'Terjadi kesalahan.');
        }
    });

    // Init
    window.addEventListener('load', () => {
        loadAsset();
        loadRepairs();
    });
</script>