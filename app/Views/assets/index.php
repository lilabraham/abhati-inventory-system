<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
    /* ════════════════════════════════════════════════════
       DESIGN TOKENS
    ════════════════════════════════════════════════════ */
    :root {
        --clr-bg: #f0f2f5;
        --clr-surface: #ffffff;
        --clr-border: #eaecf0;
        --clr-sep: #f2f4f7;

        --clr-txt-900: #0d1117;
        --clr-txt-600: #4b5563;
        --clr-txt-400: #9ca3af;

        --clr-blue: #3b82f6;
        --clr-green: #22c55e;
        --clr-red: #ef4444;
        --clr-amber: #f59e0b;

        --radius-card: 18px;
        --radius-btn: 9px;
        --shadow-card: 0 0 0 1px rgba(0, 0, 0, .045),
            0 2px 4px rgba(0, 0, 0, .04),
            0 10px 28px rgba(0, 0, 0, .07);
        --shadow-hover: 0 0 0 1px rgba(0, 0, 0, .05),
            0 4px 8px rgba(0, 0, 0, .06),
            0 16px 36px rgba(0, 0, 0, .1);
        --transition: all .18s cubic-bezier(.4, 0, .2, 1);
    }

    /* ════════════════════════════════════════════════════
       PAGE HEADER
    ════════════════════════════════════════════════════ */
    .page-title {
        font-size: 1.2rem;
        font-weight: 800;
        color: var(--clr-txt-900);
        letter-spacing: -0.45px;
        line-height: 1.2;
        margin: 0 0 4px;
    }

    .page-subtitle {
        font-size: 12.5px;
        color: var(--clr-txt-400);
        font-weight: 400;
        margin: 0;
    }

    /* ── CTA Button ── */
    .btn-tambah {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: var(--clr-txt-900);
        color: #fff;
        border: none;
        padding: 9px 18px;
        border-radius: var(--radius-btn);
        font-size: 13px;
        font-weight: 600;
        letter-spacing: -0.1px;
        cursor: pointer;
        transition: var(--transition);
        white-space: nowrap;
    }

    .btn-tambah:hover {
        background: #1a2232;
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, .18);
    }

    .btn-tambah:active {
        transform: translateY(0);
        box-shadow: none;
    }

    /* ════════════════════════════════════════════════════
       excel tombol
    ════════════════════════════════════════════════════ */

    .ghost-excel-btn {
        color: #3d8b5e;
        border-color: #3d8b5e;
        transition: all 0.2s ease;
    }

    .ghost-excel-btn:hover {
        background-color: #3d8b5e;
        color: #fff;
        box-shadow: 0 2px 8px rgba(61, 139, 94, 0.25);
    }

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

    /* Circular icon container */
    .icon-circle {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.25rem;
        /* Each card overrides --ic-bg & --ic-clr */
        background: var(--ic-bg);
    }

    .icon-circle i {
        color: var(--ic-clr);
    }

    /* Numbers */
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
       TABLE CARD SHELL
    ════════════════════════════════════════════════════ */
    .table-card {
        background: var(--clr-surface);
        border-radius: var(--radius-card);
        box-shadow: var(--shadow-card);
        overflow: hidden;
    }

    /* Card header bar */
    .table-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 24px 17px;
        border-bottom: 1px solid var(--clr-sep);
        background: var(--clr-surface);
    }

    .table-card-header-title {
        display: flex;
        align-items: center;
        gap: 9px;
        font-size: 13.5px;
        font-weight: 700;
        color: var(--clr-txt-900);
        letter-spacing: -0.15px;
    }

    .table-card-header-title .dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: var(--clr-blue);
        flex-shrink: 0;
    }

    .table-card-header small {
        font-size: 11.5px;
        color: var(--clr-txt-400);
    }

    /* ════════════════════════════════════════════════════
       TABLE — CLEAN MINIMAL
    ════════════════════════════════════════════════════ */
    .asset-table {
        width: 100%;
        border-collapse: collapse;
    }

    /* thead: white bg, all-caps muted labels */
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

    /* tbody rows */
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

    /* Kode aset mono pill */
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

    /* Merk/model cell */
    .cell-merk {
        font-weight: 600;
        color: var(--clr-txt-900);
    }

    .cell-model {
        font-size: 12.5px;
        color: var(--clr-txt-400);
        margin-top: 1px;
    }

    /* Perbaikan count chip */
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

    /* ════════════════════════════════════════════════════
       SOFT PILL BADGES — WCAG AA text contrast
    ════════════════════════════════════════════════════ */
    .badge-soft {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11.5px;
        font-weight: 600;
        padding: 4px 11px;
        border-radius: 999px;
        /* true pill */
        letter-spacing: 0.02em;
        white-space: nowrap;
    }

    .badge-soft::before {
        content: '';
        width: 5px;
        height: 5px;
        border-radius: 50%;
        flex-shrink: 0;
        background: currentColor;
    }

    .badge-soft-success {
        background: #dcfce7;
        color: #15803d;
    }

    .badge-soft-danger {
        background: #fee2e2;
        color: #b91c1c;
    }

    .badge-soft-warning {
        background: #fef3c7;
        color: #92400e;
    }

    /* dark amber — WCAG AA */
    .badge-soft-secondary {
        background: #f3f4f6;
        color: #4b5563;
    }

    /* ════════════════════════════════════════════════════
       GHOST ACTION BUTTONS
    ════════════════════════════════════════════════════ */
    .btn-action {
        width: 30px;
        height: 30px;
        padding: 0;
        border-radius: 8px;
        border: none;
        background: transparent;
        /* ghost — no bg by default */
        color: #c4cdd6;
        /* very muted icon */
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13.5px;
        cursor: pointer;
        text-decoration: none;
        transition: var(--transition);
        line-height: 1;
    }

    .btn-action:hover {
        transform: translateY(-1px);
    }

    .btn-action:active {
        transform: translateY(0);
    }

    /* Color blooms on hover */
    .btn-action-view:hover {
        background: #eff6ff;
        color: #2563eb;
    }

    .btn-action-edit:hover {
        background: #fffbeb;
        color: #d97706;
    }

    .btn-action-delete:hover {
        background: #fef2f2;
        color: #dc2626;
    }

    /* ════════════════════════════════════════════════════
       PAGINATION
    ════════════════════════════════════════════════════ */
    #paginationContainer {
        border-top: 1px solid var(--clr-sep);
    }

    #paginationContainer .page-link {
        border-radius: 8px;
        font-size: 12.5px;
        border-color: var(--clr-border);
        color: var(--clr-txt-600);
        padding: 5px 11px;
        transition: var(--transition);
    }

    #paginationContainer .page-item.active .page-link {
        background: var(--clr-txt-900);
        border-color: var(--clr-txt-900);
        color: #fff;
        box-shadow: 0 2px 8px rgba(13, 17, 23, .25);
    }

    #paginationContainer .page-link:hover:not(.active) {
        background: var(--clr-sep);
        color: var(--clr-txt-900);
        border-color: var(--clr-border);
    }

    #paginationContainer .pagination {
        gap: 3px;
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
        <button class="btn btn-outline-success fw-semibold" style="border-radius: var(--radius-btn); font-size: 13px;" data-bs-toggle="modal" data-bs-target="#modalImport">
            <i class="bi bi-file-earmark-excel me-1"></i> Import Excel
        </button>
        <button class="btn-tambah" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg"></i> Tambah Aset
        </button>
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
     MODAL TAMBAH
══════════════════════════════════════════════════════ -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0" style="border-radius:16px; box-shadow:0 20px 60px rgba(0,0,0,.18); overflow:hidden;">
            <div class="modal-header" style="border-bottom:1px solid #f2f4f7; padding:20px 24px;">
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

<!-- ══════════════════════════════════════════════════════
     MODAL EDIT
══════════════════════════════════════════════════════ -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0" style="border-radius:16px; box-shadow:0 20px 60px rgba(0,0,0,.18); overflow:hidden;">
            <div class="modal-header" style="border-bottom:1px solid #f2f4f7; padding:20px 24px;">
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

<div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="modalImportLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0" style="border-radius:16px; box-shadow:0 20px 60px rgba(0,0,0,.18); overflow:hidden;">
            <div class="modal-header" style="border-bottom:1px solid #f2f4f7; padding:20px 24px;">
                <h5 class="modal-title fw-bold fs-6" id="modalImportLabel">
                    <i class="bi bi-file-earmark-excel me-2 text-success"></i>Import Data Aset
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formImportAset" enctype="multipart/form-data">
                <div class="modal-body px-4 py-4">
                    <div class="alert alert-info" style="font-size: 13px; border-radius: 10px;">
                        <strong>Panduan Import:</strong><br>
                        1. Pastikan file berformat <strong>.xlsx</strong>.<br>
                        2. Kolom baris pertama (Header) harus sesuai dengan format sistem.<br>
                        3. Jangan biarkan Kode Aset kosong atau duplikat.
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih File Excel <span class="text-danger">*</span></label>
                        <input type="file" name="file_excel" id="file_excel" class="form-control" accept=".xlsx" required>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #f2f4f7; padding:16px 24px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success fw-semibold">
                        <i class="bi bi-cloud-upload me-1"></i> Upload Data
                    </button>
                    <div id="importErrorContainer"></div>
                </div>
        </div>
        </form>
    </div>
</div>
</div>
<!-- ══════════════════════════════════════════════════════
     TOAST
══════════════════════════════════════════════════════ -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index:9999;">
    <div id="toastNotif" class="toast align-items-center border-0" role="alert"
        style="border-radius:12px; min-width:260px; box-shadow:0 8px 32px rgba(0,0,0,.18);">
        <div class="d-flex">
            <div class="toast-body fw-semibold" id="toastMessage" style="font-size:13.5px;"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    // ─── Helpers ────────────────────────────────────────────────
    const kondisiConfig = {
        baik: {
            badge: 'success',
            label: 'Baik'
        },
        rusak: {
            badge: 'danger',
            label: 'Rusak'
        },
        dalam_perbaikan: {
            badge: 'warning',
            label: 'Dalam Perbaikan'
        },
        tidak_aktif: {
            badge: 'secondary',
            label: 'Tidak Aktif'
        },
    };

    const showToast = (message, type = 'success') => {
        const el = document.getElementById('toastNotif');
        const msg = document.getElementById('toastMessage');
        el.className = `toast align-items-center border-0 text-white bg-${type}`;
        msg.textContent = message;
        new bootstrap.Toast(el, {
            delay: 3000
        }).show();
    };

    const kondisiBadge = k => {
        const map = {
            baik: '<span class="badge-soft badge-soft-success">Baik</span>',
            rusak: '<span class="badge-soft badge-soft-danger">Rusak</span>',
            dalam_perbaikan: '<span class="badge-soft badge-soft-warning">Dalam Perbaikan</span>',
            tidak_aktif: '<span class="badge-soft badge-soft-secondary">Tidak Aktif</span>',
        };
        return map[k] ?? `<span class="badge-soft badge-soft-secondary">${k}</span>`;
    };

    // ─── State ───────────────────────────────────────────────────
    let currentPage = 1;
    const perPage = 15;

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
    const hideSpinner = () => {};

    // ─── Load Assets ─────────────────────────────────────────────
    async function loadAssets(page = 1) {
        currentPage = page;
        showSpinner();

        const res = await apiFetch(`/api/assets?page=${page}&per_page=${perPage}`);
        if (!res) return;

        const data = await res.json();

        renderTable(data.data);
        renderPagination(data.pager);
    }

    function renderTable(assets) {
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
                <td><span class="kode-pill">${a.kode_aset}</span></td>
                <td>
                    <div class="cell-merk">${a.merk}</div>
                    <div class="cell-model">${a.model}</div>
                </td>
                <td>${a.pengguna ?? '<span class="text-muted">—</span>'}</td>
                <td>${a.lokasi   ?? '<span class="text-muted">—</span>'}</td>
                <td>${kondisiBadge(a.kondisi)}</td>
                <td class="text-center">
                    <span class="chip-perbaikan">
                        <i class="bi bi-wrench" style="font-size:10px;"></i>${a.total_perbaikan}×
                    </span>
                </td>
                <td class="text-center pe-4">
                    <div class="d-flex align-items-center justify-content-center gap-1">
                        <a href="<?= base_url('data-aset/') ?>${a.id}" class="btn-action btn-action-view" title="Detail">
                            <i class="bi bi-eye"></i>
                        </a>
                        <button class="btn-action btn-action-edit" onclick="openEditModal(${a.id})" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn-action btn-action-delete" onclick="deleteAsset(${a.id}, '${a.kode_aset}')" title="Hapus">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    function renderPagination(pager) {
        const container = document.getElementById('paginationContainer');
        if (!pager || pager.total_pages <= 1) {
            container.innerHTML = '';
            return;
        }

        const {
            current_page,
            total_pages,
            has_previous,
            has_next,
            total
        } = pager;

        container.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted" style="font-size:12px;">
                    Halaman <strong>${current_page}</strong> dari <strong>${total_pages}</strong>
                    &mdash; Total <strong>${total}</strong> aset
                </small>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item ${!has_previous ? 'disabled' : ''}">
                        <button class="page-link" onclick="loadAssets(${current_page - 1})">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                    </li>
                    ${buildPageNumbers(current_page, total_pages)}
                    <li class="page-item ${!has_next ? 'disabled' : ''}">
                        <button class="page-link" onclick="loadAssets(${current_page + 1})">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </li>
                </ul>
            </div>`;
    }

    function buildPageNumbers(current, total) {
        let start = Math.max(1, current - 2);
        let end = Math.min(total, start + 4);
        if (end - start < 4) start = Math.max(1, end - 4);

        let pages = [];
        for (let i = start; i <= end; i++) {
            pages.push(`
                <li class="page-item ${i === current ? 'active' : ''}">
                    <button class="page-link" onclick="loadAssets(${i})">${i}</button>
                </li>`);
        }
        return pages.join('');
    }

    // ─── Tambah Asset ────────────────────────────────────────────
    document.getElementById('formTambahAset').addEventListener('submit', async e => {
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

        if (res.ok) {
            bootstrap.Modal.getInstance(document.getElementById('modalTambah')).hide();
            e.target.reset();
            loadAssets();
            loadStats();
            showToast('Asset berhasil ditambahkan!', 'success');
        } else {
            const errMsg = Object.values(json.errors ?? {}).join('\n');
            showToast(errMsg || 'Terjadi kesalahan.', 'danger');
        }
    });

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
        const a = json.data;

        const kondisiOptions = ['baik', 'rusak', 'dalam_perbaikan', 'tidak_aktif']
            .map(k => `<option value="${k}" ${a.kondisi === k ? 'selected' : ''}>${kondisiConfig[k]?.label ?? k}</option>`)
            .join('');

        document.getElementById('editFormContainer').innerHTML = `
            <form id="formEditAset">
                <input type="hidden" name="_asset_id" value="${a.id}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Kode Aset <span class="text-danger">*</span></label>
                        <input name="kode_aset" class="form-control" value="${a.kode_aset}" required maxlength="20">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Merk <span class="text-danger">*</span></label>
                        <input name="merk" class="form-control" value="${a.merk}" required maxlength="100">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Model <span class="text-danger">*</span></label>
                        <input name="model" class="form-control" value="${a.model}" required maxlength="100">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Serial Number</label>
                        <input name="serial_number" class="form-control" value="${a.serial_number ?? ''}" maxlength="100">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Pengguna</label>
                        <input name="pengguna" class="form-control" value="${a.pengguna ?? ''}" maxlength="100">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Kondisi <span class="text-danger">*</span></label>
                        <select name="kondisi" class="form-select" required>${kondisiOptions}</select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Lokasi</label>
                        <input name="lokasi" class="form-control" value="${a.lokasi ?? ''}" maxlength="150">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Tanggal Beli</label>
                        <input type="date" name="tanggal_beli" class="form-control" value="${a.tanggal_beli ?? ''}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Harga Beli (Rp)</label>
                        <input type="number" name="harga_beli" class="form-control" value="${a.harga_beli ?? ''}" min="0">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Spesifikasi</label>
                        <textarea name="spesifikasi" class="form-control" rows="3">${a.spesifikasi ?? ''}</textarea>
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

            if (res.ok) {
                bootstrap.Modal.getInstance(document.getElementById('modalEdit')).hide();
                loadAssets();
                loadStats();
                showToast('Asset berhasil diperbarui!', 'success');
            } else {
                const json = await res.json();
                const errMsg = Object.values(json.errors ?? {}).join('\n');
                showToast(errMsg || 'Terjadi kesalahan.', 'danger');
            }
        });
    }

    // ─── Delete Asset ────────────────────────────────────────────
    async function deleteAsset(id, kode) {
        if (!confirm(`Hapus aset "${kode}"?\nData yang dihapus tidak dapat dikembalikan.`)) return;

        const res = await apiFetch(`/api/assets/${id}`, {
            method: 'DELETE'
        });
        if (!res) return;

        if (res.ok) {
            loadAssets();
            loadStats();
            showToast(`Asset ${kode} berhasil dihapus.`, 'success');
        } else {
            showToast('Gagal menghapus asset.', 'danger');
        }
    }

    // ─── Load Stats ──────────────────────────────────────────────
    async function loadStats() {
        const res = await apiFetch('/api/reports/summary');
        if (!res) return;

        const json = await res.json();
        const d = json.data;

        const byKondisi = Object.fromEntries(
            d.kondisi_stats.map(k => [k.kondisi, parseInt(k.total)])
        );

        document.getElementById('statTotal').textContent = d.total_aset ?? '-';
        document.getElementById('statBaik').textContent = byKondisi['baik'] ?? 0;
        document.getElementById('statRusak').textContent = byKondisi['rusak'] ?? 0;
        document.getElementById('statPerbaikan').textContent = byKondisi['dalam_perbaikan'] ?? 0;
    }

    // ─── Import Asset (Upload Excel) ──────────────────────────────
    // Reset modal setiap kali dibuka
    document.getElementById('modalImport').addEventListener('show.bs.modal', () => {
        document.getElementById('importErrorContainer').innerHTML = '';
        document.getElementById('formImportAset').reset();
    });

    document.getElementById('formImportAset').addEventListener('submit', async e => {
        e.preventDefault();

        const fileInput = document.getElementById('file_excel');
        const file = fileInput.files[0];

        // Validasi di frontend sebelum kirim
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
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Mengunggah...';
        document.getElementById('importErrorContainer').innerHTML = '';

        try {
            const res = await fetch('/api/assets/import', {
                method: 'POST',
                body: new FormData(e.target),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const json = await res.json();

            if (!res.ok) {
                // 422 validasi / 500 server error
                throw new Error(json.message || 'Gagal mengunggah file.');
            }

            // Sukses penuh (200) — tutup modal
            if (json.failed_count === 0) {
                bootstrap.Modal.getInstance(document.getElementById('modalImport')).hide();
                showToast(`Berhasil! ${json.imported_count} aset telah diimport.`, 'success');
            } else {
                // Partial success (207) — tampilkan error di dalam modal, jangan tutup
                const errorItems = json.errors
                    .map(e => `<li>Baris ${e.row}: ${e.errors.join(', ')}</li>`)
                    .join('');

                document.getElementById('importErrorContainer').innerHTML = `
                <div class="alert alert-warning mt-2 mb-0" style="font-size:12.5px; border-radius:10px;">
                    <strong>${json.imported_count} data berhasil diimport.</strong>
                    ${json.failed_count} baris berikut gagal dan dilewati:
                    <ul class="mb-0 mt-1 ps-3">${errorItems}</ul>
                </div>`;

                showToast(`${json.imported_count} berhasil, ${json.failed_count} baris gagal.`, 'warning');
            }

            loadAssets();
            loadStats();

        } catch (error) {
            showToast(error.message, 'danger');
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalBtnHtml;
        }
    });

    // Init
    loadAssets();
    loadStats();
</script>
<?= $this->endSection() ?>