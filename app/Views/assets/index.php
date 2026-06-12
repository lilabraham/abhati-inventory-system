<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
    /* ── Soft shadow utility ── */
    .shadow-soft {
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06), 0 1px 3px rgba(0, 0, 0, 0.04) !important;
    }

    /* ── Stats cards ── */
    .stat-card .icon-wrap {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.3rem;
    }

    .stat-card .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        line-height: 1.1;
        letter-spacing: -0.5px;
        color: #0f172a;
    }

    .stat-card .stat-label {
        font-size: 0.72rem;
        color: #94a3b8;
        font-weight: 500;
        margin-top: 3px;
    }

    /* ── Table card ── */
    .table-card {
        background: #fff;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06), 0 1px 3px rgba(0, 0, 0, 0.04);
    }

    .table-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f1f4f8;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #fff;
    }

    .table-card-header-title {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* ── Table modern ── */
    #assetTableBody~thead th,
    .asset-table thead th {
        background: #f8fafc;
    }

    .asset-table thead th {
        background: #f8fafc;
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        border-bottom: 1px solid #e9edf2;
        border-top: none;
        padding: 13px 14px;
        white-space: nowrap;
    }

    .asset-table tbody tr {
        border-bottom: 1px solid #f1f4f8;
        transition: background 0.15s;
    }

    .asset-table tbody tr:last-child {
        border-bottom: none;
    }

    .asset-table tbody tr:hover {
        background: #f8fafc;
    }

    .asset-table tbody td {
        padding: 12px 14px;
        vertical-align: middle;
        border: none;
        font-size: 13.5px;
        color: #374151;
    }

    /* ── Soft badges ── */
    .badge-soft {
        font-size: 11px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
        letter-spacing: 0.02em;
        display: inline-block;
    }

    .badge-soft-success {
        background: #dcfce7;
        color: #16a34a;
    }

    .badge-soft-danger {
        background: #fee2e2;
        color: #dc2626;
    }

    .badge-soft-warning {
        background: #fef9c3;
        color: #ca8a04;
    }

    .badge-soft-secondary {
        background: #f1f5f9;
        color: #64748b;
    }

    /* ── Minimal action buttons ── */
    .btn-action {
        width: 32px;
        height: 32px;
        padding: 0;
        border-radius: 8px;
        border: none;
        background: #f1f5f9;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        transition: background 0.15s, color 0.15s, transform 0.1s;
        text-decoration: none;
    }

    .btn-action:hover {
        transform: translateY(-1px);
    }

    .btn-action-view:hover {
        background: #dbeafe;
        color: #2563eb;
    }

    .btn-action-edit:hover {
        background: #fef3c7;
        color: #d97706;
    }

    .btn-action-delete:hover {
        background: #fee2e2;
        color: #dc2626;
    }

    .btn-action:active {
        transform: translateY(0);
    }

    /* ── Tambah Aset button ── */
    .btn-tambah {
        background: #1e293b;
        color: #fff;
        border: none;
        padding: 9px 18px;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: background 0.2s, transform 0.15s;
    }

    .btn-tambah:hover {
        background: #0f172a;
        color: #fff;
        transform: translateY(-1px);
    }

    .btn-tambah:active {
        transform: translateY(0);
    }

    /* ── Pagination ── */
    #paginationContainer .page-link {
        border-radius: 8px;
        font-size: 12.5px;
        border-color: #e2e8f0;
        color: #475569;
        padding: 5px 11px;
    }

    #paginationContainer .page-item.active .page-link {
        background: #1e293b;
        border-color: #1e293b;
        color: #fff;
    }

    #paginationContainer .page-link:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    #paginationContainer .pagination {
        gap: 3px;
    }

    /* ── Kode aset pill ── */
    .kode-pill {
        background: #f1f5f9;
        color: #3b82f6;
        padding: 3px 9px;
        border-radius: 6px;
        font-size: 12px;
        font-family: monospace;
        font-weight: 600;
    }

    /* ── Page title ── */
    .page-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.3px;
        margin-bottom: 2px;
    }

    .page-subtitle {
        font-size: 13px;
        color: #94a3b8;
    }
</style>

<!-- ── Page Header ── -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="page-title">Aset Laptop</div>
        <div class="page-subtitle">Manajemen inventaris laptop kantor Abhati Group</div>
    </div>
    <button class="btn-tambah"
        data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg"></i> Tambah Aset
    </button>
</div>

<!-- ── Stats Cards ── -->
<div class="row g-3 mb-4" id="statsCards">
    <div class="col-md-3">
        <div class="card stat-card border-0 rounded-4 shadow-soft h-100">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="icon-wrap" style="background:rgba(59,130,246,0.1);">
                    <i class="bi bi-laptop" style="color:#3b82f6;"></i>
                </div>
                <div>
                    <div class="stat-value" id="statTotal">-</div>
                    <div class="stat-label">Total Aset</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card border-0 rounded-4 shadow-soft h-100">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="icon-wrap" style="background:rgba(34,197,94,0.1);">
                    <i class="bi bi-check-circle" style="color:#22c55e;"></i>
                </div>
                <div>
                    <div class="stat-value" id="statBaik">-</div>
                    <div class="stat-label">Kondisi Baik</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card border-0 rounded-4 shadow-soft h-100">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="icon-wrap" style="background:rgba(239,68,68,0.1);">
                    <i class="bi bi-exclamation-triangle" style="color:#ef4444;"></i>
                </div>
                <div>
                    <div class="stat-value" id="statRusak">-</div>
                    <div class="stat-label">Rusak</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card border-0 rounded-4 shadow-soft h-100">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="icon-wrap" style="background:rgba(234,179,8,0.1);">
                    <i class="bi bi-tools" style="color:#eab308;"></i>
                </div>
                <div>
                    <div class="stat-value" id="statPerbaikan">-</div>
                    <div class="stat-label">Dalam Perbaikan</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ── Table Card ── -->
<div class="table-card">
    <div class="table-card-header">
        <div class="table-card-header-title">
            <i class="bi bi-table text-primary" style="font-size:15px;"></i>
            Daftar Aset Laptop
        </div>
        <small class="text-muted" style="font-size:12px;">Data diperbarui otomatis</small>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 asset-table">
            <thead>
                <tr>
                    <th class="ps-4" style="width:52px;">#</th>
                    <th>Kode Aset</th>
                    <th>Merk / Model</th>
                    <th>Pengguna</th>
                    <th>Lokasi</th>
                    <th>Kondisi</th>
                    <th class="text-center" style="width:90px;">Perbaikan</th>
                    <th class="text-center pe-4" style="width:110px;">Aksi</th>
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
    <div id="paginationContainer" class="px-4 py-3" style="border-top:1px solid #f1f4f8;"></div>
</div>

<!-- ── Modal Tambah ── -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTambahLabel">
                    <i class="bi bi-plus-circle me-2 text-primary"></i>Tambah Aset Laptop
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <?= view('assets/_form') ?>
            </div>
        </div>
    </div>
</div>

<!-- ── Modal Edit ── -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalEditLabel">
                    <i class="bi bi-pencil-square me-2 text-warning"></i>Edit Aset Laptop
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="editFormContainer">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ── Toast ── -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index:9999">
    <div id="toastNotif" class="toast align-items-center border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body fw-semibold" id="toastMessage"></div>
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
                <td class="ps-4 text-muted" style="font-size:12px;">${((currentPage - 1) * perPage) + i + 1}</td>
                <td><span class="kode-pill">${a.kode_aset}</span></td>
                <td>
                    <span style="font-weight:600;color:#1e293b;">${a.merk}</span>
                    <span class="text-muted"> ${a.model}</span>
                </td>
                <td style="color:#475569;">${a.pengguna ?? '<span class="text-muted">-</span>'}</td>
                <td style="color:#475569;">${a.lokasi ?? '<span class="text-muted">-</span>'}</td>
                <td>${kondisiBadge(a.kondisi)}</td>
                <td class="text-center">
                    <span style="font-size:12px;font-weight:600;color:#64748b;">${a.total_perbaikan}x</span>
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
        const res = await apiFetch('/api/report/summary');
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

    // Init
    loadAssets();
    loadStats();
</script>
<?= $this->endSection() ?>