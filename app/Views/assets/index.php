<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Aset Laptop</h4>
        <small class="text-muted">Manajemen inventaris laptop kantor Abhati Group</small>
    </div>
    <button class="btn btn-primary d-flex align-items-center gap-2"
            data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg"></i> Tambah Aset
    </button>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4" id="statsCards">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 bg-primary bg-opacity-10 p-3">
                    <i class="bi bi-laptop fs-4 text-primary"></i>
                </div>
                <div>
                    <div class="fs-2 fw-bold" id="statTotal">-</div>
                    <div class="text-muted small">Total Aset</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 bg-success bg-opacity-10 p-3">
                    <i class="bi bi-check-circle fs-4 text-success"></i>
                </div>
                <div>
                    <div class="fs-2 fw-bold" id="statBaik">-</div>
                    <div class="text-muted small">Kondisi Baik</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 bg-danger bg-opacity-10 p-3">
                    <i class="bi bi-exclamation-triangle fs-4 text-danger"></i>
                </div>
                <div>
                    <div class="fs-2 fw-bold" id="statRusak">-</div>
                    <div class="text-muted small">Rusak</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 bg-warning bg-opacity-10 p-3">
                    <i class="bi bi-tools fs-4 text-warning"></i>
                </div>
                <div>
                    <div class="fs-2 fw-bold" id="statPerbaikan">-</div>
                    <div class="text-muted small">Dalam Perbaikan</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Kode Aset</th>
                        <th>Merk / Model</th>
                        <th>Pengguna</th>
                        <th>Lokasi</th>
                        <th>Kondisi</th>
                        <th class="text-center">Perbaikan</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody id="assetTableBody">
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                            Memuat data...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div id="paginationContainer" class="px-3 pb-3"></div>
    </div>
</div>

<!-- Modal Tambah -->
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

<!-- Modal Edit -->
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

<!-- Toast Notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
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
        baik:              { badge: 'success',   label: 'Baik' },
        rusak:             { badge: 'danger',    label: 'Rusak' },
        dalam_perbaikan:   { badge: 'warning',   label: 'Dalam Perbaikan' },
        tidak_aktif:       { badge: 'secondary', label: 'Tidak Aktif' },
    };

    const showToast = (message, type = 'success') => {
        const el  = document.getElementById('toastNotif');
        const msg = document.getElementById('toastMessage');
        el.className = `toast align-items-center border-0 text-white bg-${type}`;
        msg.textContent = message;
        new bootstrap.Toast(el, { delay: 3000 }).show();
    };

    const kondisiBadge = k => {
        const c = kondisiConfig[k] ?? { badge: 'secondary', label: k };
        return `<span class="badge bg-${c.badge}">${c.label}</span>`;
    };

// ─── State ───────────────────────────────────────────────────
    let currentPage = 1;
    const perPage   = 15;

    // ─── Spinner ─────────────────────────────────────────────────
    const showSpinner = () => {
        document.getElementById('assetTableBody').innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-5 text-muted">
                    <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                    Memuat data...
                </td>
            </tr>`;
    };
    const hideSpinner = () => {}; // data sudah dirender oleh renderTable

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
            tbody.innerHTML = `<tr><td colspan="8" class="text-center py-4 text-muted">Tidak ada data.</td></tr>`;
            return;
        }
        tbody.innerHTML = assets.map((a, i) => `
            <tr>
                <td class="ps-4">${((currentPage - 1) * perPage) + i + 1}</td>
                <td>${a.kode_aset}</td>
                <td>${a.merk} ${a.model}</td>
                <td>${a.pengguna ?? '-'}</td>
                <td>${a.lokasi ?? '-'}</td>
                <td>${kondisiBadge(a.kondisi)}</td>
                <td class="text-center">${a.total_perbaikan}</td>
                <td class="text-center pe-4">
                    <a href="/assets/${a.id}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                    <button class="btn btn-sm btn-warning" onclick="openEditModal(${a.id})"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-sm btn-danger" onclick="deleteAsset(${a.id}, '${a.kode_aset}')"><i class="bi bi-trash"></i></button>
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

        const { current_page, total_pages, has_previous, has_next, total } = pager;

        container.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mt-2">
                <small class="text-muted">
                    Halaman ${current_page} dari ${total_pages} &mdash; Total ${total} aset
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
        let end   = Math.min(total, start + 4);
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

        const res  = await apiFetch(`/api/assets/${id}`);
        if (!res) return;

        const json = await res.json();
        const a    = json.data;

        const kondisiOptions = ['baik','rusak','dalam_perbaikan','tidak_aktif']
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
            const assetId  = formData._asset_id;
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
                const json   = await res.json();
                const errMsg = Object.values(json.errors ?? {}).join('\n');
                showToast(errMsg || 'Terjadi kesalahan.', 'danger');
            }
        });
    }

    // ─── Delete Asset ────────────────────────────────────────────
    async function deleteAsset(id, kode) {
        if (!confirm(`Hapus aset "${kode}"?\nData yang dihapus tidak dapat dikembalikan.`)) return;

        const res = await apiFetch(`/api/assets/${id}`, { method: 'DELETE' });
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

        const json  = await res.json();
        const d     = json.data;

        // Bangun lookup: { baik: 51, rusak: 13, ... }
        const byKondisi = Object.fromEntries(
            d.kondisi_stats.map(k => [k.kondisi, parseInt(k.total)])
        );

        document.getElementById('statTotal').textContent    = d.total_aset ?? '-';
        document.getElementById('statBaik').textContent     = byKondisi['baik']            ?? 0;
        document.getElementById('statRusak').textContent    = byKondisi['rusak']           ?? 0;
        document.getElementById('statPerbaikan').textContent = byKondisi['dalam_perbaikan'] ?? 0;
    }

    // Init
    loadAssets();
    loadStats();
</script>
<?= $this->endSection() ?>