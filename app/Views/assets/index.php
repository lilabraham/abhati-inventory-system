<?php

/** @var array{manage: bool, import: bool} $can */
?>

<style>
    /* ════════════════════════════════════════════════════
       STAT CARDS
    ════════════════════════════════════════════════════ */
    .stat-card {
        background: var(--clr-surface) !important;
        border: none !important;
        border-radius: var(--radius-card) !important;
        box-shadow: var(--shadow-card) !important;
        transition: var(--transition);
        overflow: hidden;
        position: relative;
    }

    .stat-card:hover {
        box-shadow: var(--shadow-hover) !important;
        transform: translateY(-2px);
    }

    .stat-card .card-body {
        padding: 22px 22px !important;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .icon-circle {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.25rem;
        background: var(--ic-bg);
    }

    .icon-circle i {
        color: var(--ic-clr);
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1;
        letter-spacing: -1.5px;
        color: var(--clr-txt-900);
        font-variant-numeric: tabular-nums;
    }

    .stat-label {
        font-size: 11.5px;
        color: var(--clr-txt-400);
        font-weight: 500;
        margin-top: 5px;
        letter-spacing: 0.01em;
    }

    /* ════════════════════════════════════════════════════
       TABLE — CLEAN MINIMAL (asset-specific)
    ════════════════════════════════════════════════════ */
    .asset-table {
        width: 100%;
        border-collapse: collapse;
    }

    .asset-table thead th {
        background: var(--clr-surface);
        color: var(--clr-txt-400);
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        padding: 11px 14px;
        border-top: none;
        border-bottom: 1px solid var(--clr-sep) !important;
        white-space: nowrap;
        vertical-align: middle;
    }

    .asset-table tbody tr {
        border-bottom: 1px solid var(--clr-sep);
        transition: background .12s;
    }

    .asset-table tbody tr:last-child {
        border-bottom: none;
    }

    .asset-table tbody tr:hover {
        background: #fafbfc;
    }

    .asset-table tbody td {
        padding: 15px 14px;
        border: none !important;
        vertical-align: middle;
        font-size: 13.5px;
        color: var(--clr-txt-600);
    }

    .kode-pill {
        display: inline-block;
        background: #eff6ff;
        color: #2563eb;
        padding: 3px 9px;
        border-radius: 6px;
        font-size: 11.5px;
        font-family: ui-monospace, 'Cascadia Code', monospace;
        font-weight: 700;
        letter-spacing: 0.02em;
    }

    .cell-merk {
        font-weight: 600;
        color: var(--clr-txt-900);
    }

    .cell-model {
        font-size: 12.5px;
        color: var(--clr-txt-400);
        margin-top: 1px;
    }

    .chip-perbaikan {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #f3f4f6;
        color: #6b7280;
        font-size: 12px;
        font-weight: 600;
        padding: 3px 9px;
        border-radius: 999px;
        font-variant-numeric: tabular-nums;
    }
</style>

<!-- ══════════════════════════════════════════════════════
     PAGE HEADER
══════════════════════════════════════════════════════ -->
<div class="d-flex justify-content-between align-items-start mb-4 gap-3">
    <div>
        <h1 class="page-title">Aset Laptop</h1>
        <p class="page-subtitle">Manajemen inventaris laptop kantor Abhati Group</p>
    </div>
    <div class="d-flex gap-2">
        <?php if ($can['import']): ?>
            <button class="btn btn-outline-success fw-semibold" style="border-radius: var(--radius-btn); font-size: 13px;" data-bs-toggle="modal" data-bs-target="#modalImport">
                <i class="bi bi-file-earmark-excel me-1"></i> Import Excel
            </button>
        <?php endif; ?>
        <?php if ($can['manage']): ?>
            <button class="btn-tambah" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-lg"></i> Tambah Aset
            </button>
        <?php endif; ?>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════
     STAT CARDS
══════════════════════════════════════════════════════ -->
<div class="row g-3 mb-4" id="statsCards">

    <div class="col-6 col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="icon-circle" style="--ic-bg:rgba(59,130,246,.1); --ic-clr:#3b82f6;">
                    <i class="bi bi-laptop"></i>
                </div>
                <div>
                    <div class="stat-value" id="statTotal">-</div>
                    <div class="stat-label">Total Aset</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="icon-circle" style="--ic-bg:rgba(34,197,94,.1); --ic-clr:#22c55e;">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div>
                    <div class="stat-value" id="statBaik">-</div>
                    <div class="stat-label">Kondisi Baik</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="icon-circle" style="--ic-bg:rgba(239,68,68,.1); --ic-clr:#ef4444;">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div>
                    <div class="stat-value" id="statRusak">-</div>
                    <div class="stat-label">Rusak</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="icon-circle" style="--ic-bg:rgba(245,158,11,.1); --ic-clr:#f59e0b;">
                    <i class="bi bi-tools"></i>
                </div>
                <div>
                    <div class="stat-value" id="statPerbaikan">-</div>
                    <div class="stat-label">Dalam Perbaikan</div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- ══════════════════════════════════════════════════════
     TABLE CARD
══════════════════════════════════════════════════════ -->
<div class="table-card">

    <div class="table-card-header">
        <div class="table-card-header-title">
            <span class="dot"></span>
            Daftar Aset Laptop
        </div>
        <small>Data diperbarui otomatis</small>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0 asset-table">
            <thead>
                <tr>
                    <th class="ps-4" style="width:48px;">#</th>
                    <th>Kode Aset</th>
                    <th>Merk / Model</th>
                    <th>Pengguna</th>
                    <th>Lokasi</th>
                    <th>Kondisi</th>
                    <th class="text-center" style="width:96px;">Perbaikan</th>
                    <th class="text-center pe-4" style="width:106px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="assetTableBody">
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                        <span class="text-muted" style="font-size:13px;">Memuat data...</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div id="paginationContainer" class="px-4 py-3"></div>

</div>

<!-- ══════════════════════════════════════════════════════
     MODAL TAMBAH & EDIT — hanya superadmin
══════════════════════════════════════════════════════ -->
<?php if ($can['manage']): ?>
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold fs-6" id="modalTambahLabel">
                        <i class="bi bi-plus-circle me-2 text-primary"></i>Tambah Aset Laptop
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-4">
                    <?= view('assets/_form') ?>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold fs-6" id="modalEditLabel">
                        <i class="bi bi-pencil-square me-2 text-warning"></i>Edit Aset Laptop
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-4" id="editFormContainer">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- ══════════════════════════════════════════════════════
     MODAL IMPORT — hanya yang punya imports.run
══════════════════════════════════════════════════════ -->
<?php if ($can['import']): ?>
    <div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="modalImportLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold fs-6" id="modalImportLabel">
                        <i class="bi bi-file-earmark-excel me-2 text-success"></i>Import Data Aset
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formImportAset">
                    <div class="modal-body px-4 py-4">
                        <div class="alert alert-info" style="font-size: 13px; border-radius: 10px;">
                            <strong>Panduan Import:</strong><br>
                            1. Pastikan file berformat <strong>.xlsx</strong>.<br>
                            2. Kolom baris pertama (Header) harus sesuai dengan format sistem.<br>
                            3. Jangan biarkan Kode Aset kosong atau duplikat.
                        </div>
                        <button type="button" id="btnDownloadTemplate" class="btn btn-outline-secondary btn-sm mb-3" style="border-radius:8px; font-size:12.5px;">
                            <i class="bi bi-download me-1"></i> Download Template Excel
                        </button>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pilih File Excel <span class="text-danger">*</span></label>
                            <input type="file" name="file_excel" id="file_excel" class="form-control" accept=".xlsx" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success fw-semibold">
                            <i class="bi bi-cloud-upload me-1"></i> Upload Data
                        </button>
                    </div>
                    <div id="importErrorContainer" class="px-4 pb-3"></div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($can['manage']): ?>
    <div class="modal fade" id="modalConfirmDelete" tabindex="-1" aria-labelledby="modalConfirmDeleteLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold fs-6" id="modalConfirmDeleteLabel">
                        <i class="bi bi-exclamation-triangle me-2 text-danger"></i>Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0" style="font-size:13.5px;" id="confirmDeleteText"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger fw-semibold" id="btnConfirmDelete">
                        <i class="bi bi-trash me-1"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($can['import']): ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<?php endif; ?>
<script>
    const CAN_MANAGE = <?= json_encode($can['manage']) ?>;
    const CAN_IMPORT = <?= json_encode($can['import']) ?>;

    // ─── Helpers ────────────────────────────────────────────────
    const kondisiConfig = window.KONDISI_CONFIG;
    const escHtml = window.escHtml;

    const kondisiBadge = k => {
        const c = kondisiConfig[k];
        return c ?
            `<span class="badge-soft badge-soft-${escHtml(c.cls)}">${escHtml(c.label)}</span>` :
            `<span class="badge-soft badge-soft-secondary">${escHtml(k)}</span>`;
    };

    // ─── State ───────────────────────────────────────────────────
    let currentPage = 1;
    const perPage = 15;
    let loadController = null;
    let currentAssets = [];


    // ─── Spinner ─────────────────────────────────────────────────
    const showSpinner = () => {
        document.getElementById('assetTableBody').innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-5">
                    <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                    <span class="text-muted" style="font-size:13px;">Memuat data...</span>
                </td>
            </tr>`;
    };

    // ─── Load Assets ─────────────────────────────────────────────
    async function loadAssets(page = 1) {
        if (loadController) loadController.abort();
        loadController = new AbortController();
        const signal = loadController.signal;

        currentPage = page;
        showSpinner();

        // AFTER
        const res = await apiFetch(`/api/assets?page=${page}&per_page=${perPage}`, {
            signal
        });
        if (signal.aborted) return;
        if (!res) {
            document.getElementById('assetTableBody').innerHTML = `
                <tr><td colspan="8" class="text-center py-5 text-muted" style="font-size:13px;">
                <i class="bi bi-wifi-off me-2 opacity-50"></i>Sesi habis. Silakan <a href="/login">login ulang</a>.
                </td></tr>`;
            return;
        }
        if (res.status === 403) {
            document.getElementById('assetTableBody').innerHTML = `
                <tr><td colspan="8" class="text-center py-5 text-muted" style="font-size:13px;">
                <i class="bi bi-shield-lock me-2 opacity-50"></i>Anda tidak memiliki akses untuk melihat data ini.
                </td></tr>`;
            return;
        }

        const json = await res.json();

        if (!json.success) {
            document.getElementById('assetTableBody').innerHTML = `
        <tr><td colspan="8" class="text-center py-5 text-muted" style="font-size:13px;">
            <i class="bi bi-exclamation-circle me-2 opacity-50"></i>${escHtml(json.message ?? 'Gagal memuat data.')}
        </td></tr>`;
            return;
        }

        renderTable(json.data.data);
        renderPagination({
            current_page: page,
            total_pages: json.data.last_page,
            total: json.data.total,
            has_previous: page > 1,
            has_next: page < json.data.last_page,
        });
    }

    function renderTable(assets) {
        currentAssets = assets;
        const tbody = document.getElementById('assetTableBody');
        if (!assets || assets.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted" style="font-size:13px;">
                        <i class="bi bi-inbox me-2 opacity-50"></i>Tidak ada data aset.
                    </td>
                </tr>`;
            return;
        }

        tbody.innerHTML = assets.map((a, i) => `
            <tr>
                <td class="ps-4 text-muted" style="font-size:12px; font-variant-numeric:tabular-nums;">${((currentPage - 1) * perPage) + i + 1}</td>
                <td><span class="kode-pill">${escHtml(a.kode_aset)}</span></td>
                <td>
                    <div class="cell-merk">${escHtml(a.merk)}</div>
                    <div class="cell-model">${escHtml(a.model)}</div>
                </td>
                <td>${a.pengguna ? escHtml(a.pengguna) : '<span class="text-muted">—</span>'}</td>
                <td>${a.lokasi ? escHtml(a.lokasi) : '<span class="text-muted">—</span>'}</td>
                <td>${kondisiBadge(a.kondisi)}</td>
                <td class="text-center">
                    <span class="chip-perbaikan">
                        <i class="bi bi-wrench" style="font-size:10px;"></i>${a.total_perbaikan ?? 0}×
                    </span>
                </td>
                <td class="text-center pe-4">
                    <div class="d-flex align-items-center justify-content-center gap-1">
                        <a href="/data-aset/${a.id}" class="btn-action btn-action-view" title="Detail">
                            <i class="bi bi-eye"></i>
                        </a>
                        ${CAN_MANAGE ? `
                        <button class="btn-action btn-action-edit" data-id="${a.id}" data-action="edit" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn-action btn-action-delete" data-id="${a.id}" data-action="delete" title="Hapus">
                            <i class="bi bi-trash"></i>
                        </button>
                        ` : ''}
                    </div>
                </td>
            </tr>
        `).join('');

        tbody.querySelectorAll('[data-action]').forEach(btn => {
            btn.addEventListener('click', () => {
                const {
                    id,
                    action
                } = btn.dataset;
                if (action === 'edit') openEditModal(id);
                if (action === 'delete') {
                    const asset = currentAssets.find(a => String(a.id) === id);
                    deleteAsset(id, asset?.kode_aset ?? '');
                }
            });
        });
    }

    function renderPagination({
        current_page,
        total_pages,
        has_previous,
        has_next,
        total
    }) {
        const container = document.getElementById('paginationContainer');
        if (!total_pages) {
            container.innerHTML = '';
            return;
        }

        container.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted" style="font-size:12px;">
                    Halaman <strong>${current_page}</strong> dari <strong>${total_pages}</strong>
                    &mdash; Total <strong>${total}</strong> aset
                </small>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item ${!has_previous ? 'disabled' : ''}">
                        <button class="page-link" data-page="${current_page - 1}">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                    </li>
                    ${buildPageNumbers(current_page, total_pages)}
                    <li class="page-item ${!has_next ? 'disabled' : ''}">
                        <button class="page-link" data-page="${current_page + 1}">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </li>
                </ul>
            </div>`;

        container.querySelectorAll('[data-page]').forEach(btn => {
            btn.addEventListener('click', () => loadAssets(parseInt(btn.dataset.page)));
        });
    }

    function buildPageNumbers(current, total) {
        let start = Math.max(1, current - 2);
        let end = Math.min(total, start + 4);
        if (end - start < 4) start = Math.max(1, end - 4);

        let pages = [];
        for (let i = start; i <= end; i++) {
            pages.push(`
                <li class="page-item ${i === current ? 'active' : ''}">
                    <button class="page-link" data-page="${i}">${i}</button>
                </li>`);
        }
        return pages.join('');
    }

    // ─── Tambah Asset ────────────────────────────────────────────
    const formTambahAset = document.getElementById('formTambahAset');
    if (formTambahAset) {
        formTambahAset.addEventListener('submit', async e => {
            e.preventDefault();
            const btn = e.target.querySelector('[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';

            const res = await apiFetch('/api/assets', {
                method: 'POST',
                body: JSON.stringify(Object.fromEntries(new FormData(e.target))),
            });

            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-save me-1"></i> Simpan Aset';
            if (!res) return;

            const json = await res.json();

            if (json.success) {
                bootstrap.Modal.getInstance(document.getElementById('modalTambah')).hide();
                e.target.reset();
                loadAssets();
                loadStats();
                showToast('Asset berhasil ditambahkan!', 'success');
            } else {
                const errMsg = json.data ?
                    Object.values(json.data).flat().join('\n') :
                    json.message;
                showToast(errMsg || 'Terjadi kesalahan.', 'danger');
            }
        });
    }

    // ─── Edit Asset ──────────────────────────────────────────────
    async function openEditModal(id) {
        const modal = new bootstrap.Modal(document.getElementById('modalEdit'));
        document.getElementById('editFormContainer').innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status"></div>
            </div>`;
        modal.show();

        const res = await apiFetch(`/api/assets/${id}`);
        if (!res) return;

        const json = await res.json();
        if (!json.success) {
            showToast(json.message ?? 'Gagal memuat data aset.', 'danger');
            return;
        }

        const a = json.data;

        const kondisiOptions = Object.keys(kondisiConfig)
            .map(k => `<option value="${escHtml(k)}" ${a.kondisi === k ? 'selected' : ''}>${escHtml(kondisiConfig[k].label)}</option>`)
            .join('');

        document.getElementById('editFormContainer').innerHTML = `
            <form id="formEditAset">
                <input type="hidden" name="_asset_id" value="${escHtml(String(a.id))}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Kode Aset <span class="text-danger">*</span></label>
                        <input name="kode_aset" class="form-control" value="${escHtml(a.kode_aset)}" required maxlength="20">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Merk <span class="text-danger">*</span></label>
                        <input name="merk" class="form-control" value="${escHtml(a.merk)}" required maxlength="100">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Model <span class="text-danger">*</span></label>
                        <input name="model" class="form-control" value="${escHtml(a.model)}" required maxlength="100">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Serial Number</label>
                        <input name="serial_number" class="form-control" value="${escHtml(a.serial_number ?? '')}" maxlength="100">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Pengguna</label>
                        <input name="pengguna" class="form-control" value="${escHtml(a.pengguna ?? '')}" maxlength="100">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Kondisi <span class="text-danger">*</span></label>
                        <select name="kondisi" class="form-select" required>${kondisiOptions}</select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Lokasi</label>
                        <input name="lokasi" class="form-control" value="${escHtml(a.lokasi ?? '')}" maxlength="150">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Tanggal Beli</label>
                        <input type="date" name="tanggal_beli" class="form-control" value="${escHtml(a.tanggal_beli ?? '')}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Harga Beli (Rp)</label>
                        <input type="number" name="harga_beli" class="form-control" value="${escHtml(a.harga_beli ?? '')}" min="0">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Spesifikasi</label>
                        <textarea name="spesifikasi" class="form-control" rows="3">${escHtml(a.spesifikasi ?? '')}</textarea>
                    </div>
                </div>
                <div class="mt-4 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning fw-semibold">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>`;

        document.getElementById('formEditAset').addEventListener('submit', async e => {
            e.preventDefault();
            const btn = e.target.querySelector('[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';

            const formData = Object.fromEntries(new FormData(e.target));
            const assetId = formData._asset_id;
            delete formData._asset_id;

            const res = await apiFetch(`/api/assets/${assetId}`, {
                method: 'PUT',
                body: JSON.stringify(formData),
            });

            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-save me-1"></i> Simpan Perubahan';
            if (!res) return;

            const json = await res.json();

            if (json.success) {
                bootstrap.Modal.getInstance(document.getElementById('modalEdit')).hide();
                loadAssets(currentPage);
                loadStats();
                showToast('Asset berhasil diperbarui!', 'success');
            } else {
                const errMsg = json.data ?
                    Object.values(json.data).join('\n') :
                    json.message;
                showToast(errMsg || 'Terjadi kesalahan.', 'danger');
            }
        });
    }

    // ─── Delete Asset ────────────────────────────────────────────
    function confirmDelete(message) {
        return new Promise(resolve => {
            const modalEl = document.getElementById('modalConfirmDelete');
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            document.getElementById('confirmDeleteText').textContent = message;

            const btnConfirm = document.getElementById('btnConfirmDelete');
            const onConfirm = () => {
                cleanup();
                modal.hide();
                resolve(true);
            };
            const onCancel = () => {
                cleanup();
                resolve(false);
            };
            const cleanup = () => {
                btnConfirm.removeEventListener('click', onConfirm);
                modalEl.removeEventListener('hidden.bs.modal', onCancel);
            };

            btnConfirm.addEventListener('click', onConfirm);
            modalEl.addEventListener('hidden.bs.modal', onCancel);
            modal.show();
        });
    }

    async function deleteAsset(id, kode) {
        const confirmed = await confirmDelete(`Hapus aset "${kode}"? Data yang dihapus tidak dapat dikembalikan.`);
        if (!confirmed) return;

        const res = await apiFetch(`/api/assets/${id}`, {
            method: 'DELETE'
        });
        if (!res) return;

        const json = await res.json();

        if (json.success) {
            loadAssets(currentPage);
            loadStats();
            showToast(`Asset ${kode} berhasil dihapus.`, 'success');
        } else {
            showToast(json.message || 'Gagal menghapus asset.', 'danger');
        }
    }

    // ─── Load Stats ──────────────────────────────────────────────
    async function loadStats() {
        const res = await apiFetch('/api/reports/summary');
        if (!res) return;

        const json = await res.json();
        if (!json.success) return;

        const d = json.data;
        const byKondisi = Object.fromEntries(
            (d.kondisi_stats ?? []).map(k => [k.kondisi, parseInt(k.total)])
        );

        document.getElementById('statTotal').textContent = d.total_aset ?? '-';
        document.getElementById('statBaik').textContent = byKondisi['baik'] ?? 0;
        document.getElementById('statRusak').textContent = byKondisi['rusak'] ?? 0;
        document.getElementById('statPerbaikan').textContent = byKondisi['dalam_perbaikan'] ?? 0;
    }

    // ─── Import Asset — SheetJS client-side ──────────────────────
    const IMPORT_CHUNK_SIZE = 100;

    const IMPORT_HEADERS = [
        'kode_aset', 'merk', 'model', 'serial_number',
        'pengguna', 'kondisi', 'lokasi',
        'tanggal_beli', 'harga_beli', 'spesifikasi',
    ];

    if (CAN_IMPORT) {
        document.getElementById('modalImport').addEventListener('show.bs.modal', () => {
            document.getElementById('importErrorContainer').innerHTML = '';
            document.getElementById('formImportAset').reset();
        });

        document.getElementById('btnDownloadTemplate').addEventListener('click', () => {
            const ws = XLSX.utils.aoa_to_sheet([IMPORT_HEADERS]);
            ws['!cols'] = IMPORT_HEADERS.map(() => ({
                wch: 16
            }));
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Template Import');
            XLSX.writeFile(wb, 'Template_Import_Aset_Abhati.xlsx');
        });

        function readExcelFile(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    try {
                        const wb = XLSX.read(e.target.result, {
                            type: 'array',
                            cellDates: false
                        });
                        const sheet = wb.Sheets[wb.SheetNames[0]];
                        resolve(XLSX.utils.sheet_to_json(sheet, {
                            defval: ''
                        }));
                    } catch (err) {
                        reject(new Error('Gagal membaca file Excel. Pastikan format file valid.'));
                    }
                };
                reader.onerror = () => reject(new Error('Gagal membaca file.'));
                reader.readAsArrayBuffer(file);
            });
        }

        function chunkArray(arr, size) {
            const chunks = [];
            for (let i = 0; i < arr.length; i += size) chunks.push(arr.slice(i, i + size));
            return chunks;
        }

        document.getElementById('formImportAset').addEventListener('submit', async e => {
            e.preventDefault();

            const file = document.getElementById('file_excel').files[0];
            if (!file) {
                showToast('Pilih file Excel terlebih dahulu.', 'danger');
                return;
            }
            if (!file.name.toLowerCase().endsWith('.xlsx')) {
                showToast('Hanya file .xlsx yang diizinkan.', 'danger');
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                showToast('Ukuran file maksimal 5MB.', 'danger');
                return;
            }

            const btn = e.target.querySelector('[type="submit"]');
            const originalBtnHtml = btn.innerHTML;
            btn.disabled = true;
            document.getElementById('importErrorContainer').innerHTML = '';

            try {
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Membaca file...';
                const rows = await readExcelFile(file);
                if (!rows.length) throw new Error('File Excel kosong atau tidak ada data.');

                const chunks = chunkArray(rows, IMPORT_CHUNK_SIZE);
                const totalChunks = chunks.length;
                let totalImported = 0,
                    totalSkipped = 0,
                    totalFailed = 0,
                    allErrors = [];

                for (let i = 0; i < totalChunks; i++) {
                    btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1"></span>Mengupload bagian ${i + 1}/${totalChunks}...`;

                    const isLast = i === totalChunks - 1;
                    const res = await apiFetch('/api/assets/import', {
                        method: 'POST',
                        body: JSON.stringify({
                            rows: chunks[i],
                            is_last_chunk: isLast,
                            grand_total_imported: isLast ? totalImported : 0,
                            grand_total_failed: isLast ? totalFailed : 0,
                        }),
                    });

                    if (!res) throw new Error(`Koneksi gagal pada bagian ${i + 1}/${totalChunks}. Upload dihentikan, silakan unggah ulang file untuk melanjutkan sisa data.`);

                    const json = await res.json();
                    if (!res.ok && res.status !== 207) throw new Error(json.message || `Gagal pada bagian ${i + 1}/${totalChunks}. Upload dihentikan.`);

                    const result = json.data;
                    totalImported += result.imported ?? 0;
                    totalSkipped += result.skipped ?? 0;
                    totalFailed += result.failed ?? 0;
                    allErrors.push(...(result.errors ?? []).map(err => ({
                        ...err,
                        chunk: i + 1
                    })));
                }

                if (totalFailed === 0) {
                    bootstrap.Modal.getInstance(document.getElementById('modalImport')).hide();
                    showToast(`Berhasil! ${totalImported} aset diimport, ${totalSkipped} dilewati (duplikat).`, 'success');
                } else {
                    const errorItems = allErrors
                        .map(err => `<li>Bagian ${escHtml(String(err.chunk))}, Baris ${escHtml(String(err.row))}: ${err.errors.map(escHtml).join(', ')}</li>`)
                        .join('');
                    document.getElementById('importErrorContainer').innerHTML = `
                    <div class="alert alert-warning mt-2 mb-0" style="font-size:12.5px; border-radius:10px;">
                        <strong>${totalImported} berhasil, ${totalSkipped} dilewati (duplikat).</strong>
                        ${totalFailed} baris gagal:
                        <ul class="mb-0 mt-1 ps-3">${errorItems}</ul>
                    </div>`;
                    showToast(`${totalImported} berhasil, ${totalSkipped} dilewati, ${totalFailed} gagal.`, 'warning');
                }

                loadAssets();
                loadStats();

            } catch (error) {
                showToast(error.message, 'danger');
                document.getElementById('importErrorContainer').innerHTML = `
                    <div class="alert alert-danger mt-2 mb-0" style="font-size:12.5px; border-radius:10px;">
                        ${escHtml(error.message)}
                    </div>`;
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalBtnHtml;
            }
        });
    }

    // ─── Init ─────────────────────────────────────────────────────
    loadAssets();
    loadStats();
</script>