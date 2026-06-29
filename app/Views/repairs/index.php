<?php // Guard via RepairController::index() — can('repairs.view') ?>

<style>
    .filter-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: flex-end;
        padding: 16px 20px;
        border-bottom: 1px solid var(--clr-sep);
        background: var(--clr-surface);
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .filter-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--clr-txt-400);
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .filter-control {
        padding: 6px 10px;
        border-radius: 9px;
        border: 1px solid var(--clr-border);
        font-size: 12.5px;
        color: var(--clr-txt-600);
        background: var(--clr-surface);
        outline: none;
        transition: var(--transition);
        height: 34px;
    }

    .filter-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,.1);
    }

    .filter-select { min-width: 140px; }

    .table-search-wrap { position: relative; }

    .table-search {
        padding: 6px 10px 6px 30px;
        border-radius: 9px;
        border: 1px solid var(--clr-border);
        font-size: 12.5px;
        color: var(--clr-txt-600);
        background: var(--clr-surface);
        outline: none;
        transition: var(--transition);
        height: 34px;
        width: 200px;
    }

    .table-search:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,.1);
    }

    .table-search-icon {
        position: absolute;
        left: 9px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--clr-txt-400);
        font-size: 12px;
        pointer-events: none;
    }

    .btn-filter-apply {
        height: 34px;
        padding: 0 14px;
        border-radius: 9px;
        border: none;
        background: var(--clr-txt-900);
        color: #fff;
        font-size: 12.5px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 5px;
        align-self: flex-end;
    }

    .btn-filter-apply:hover {
        background: #1a2232;
        transform: translateY(-1px);
    }

    .btn-filter-reset {
        height: 34px;
        padding: 0 12px;
        border-radius: 9px;
        border: 1px solid var(--clr-border);
        background: transparent;
        color: var(--clr-txt-400);
        font-size: 12.5px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        align-self: flex-end;
    }

    .btn-filter-reset:hover {
        border-color: #cbd5e1;
        color: var(--clr-txt-600);
    }

    .repair-table {
        width: 100%;
        border-collapse: collapse;
    }

    .repair-table thead th {
        background: var(--clr-surface);
        color: var(--clr-txt-400);
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        padding: 11px 14px;
        border-bottom: 1px solid var(--clr-sep) !important;
        white-space: nowrap;
        vertical-align: middle;
    }

    .repair-table tbody tr {
        border-bottom: 1px solid var(--clr-sep);
        transition: background .12s;
    }

    .repair-table tbody tr:last-child { border-bottom: none; }
    .repair-table tbody tr:hover      { background: #fafbfc; }

    .repair-table tbody td {
        padding: 11px 14px;
        border: none !important;
        vertical-align: middle;
        font-size: 13px;
        color: var(--clr-txt-600);
    }

    .asset-cell-code {
        font-weight: 700;
        font-size: 12.5px;
        color: var(--clr-txt-900);
    }

    .asset-cell-sub {
        font-size: 11.5px;
        color: var(--clr-txt-400);
        margin-top: 1px;
    }

    .desc-cell {
        max-width: 260px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .biaya-cell {
        font-size: 12.5px;
        font-weight: 600;
        color: var(--clr-txt-900);
        white-space: nowrap;
    }
</style>

<!-- ══ PAGE HEADER ══ -->
<div class="d-flex justify-content-between align-items-start mb-4 gap-3">
    <div>
        <h1 class="page-title">Riwayat Perbaikan</h1>
        <p class="page-subtitle">Seluruh riwayat perbaikan laptop lintas aset</p>
    </div>
</div>

<!-- ══ TABLE CARD ══ -->
<div class="table-card">

    <div class="table-card-header">
        <div class="table-card-header-title">
            <span class="dot"></span>
            Semua Perbaikan
        </div>
        <small id="repairMeta">—</small>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <div class="filter-group">
            <span class="filter-label">Status</span>
            <select id="filterStatus" class="filter-control filter-select">
                <option value="">Semua Status</option>
                <option value="selesai">Selesai</option>
                <option value="pending">Pending</option>
                <option value="gagal">Gagal</option>
            </select>
        </div>
        <div class="filter-group">
            <span class="filter-label">Kondisi Akhir</span>
            <select id="filterKondisi" class="filter-control filter-select">
                <option value="">Semua Kondisi</option>
                <option value="baik">Baik</option>
                <option value="rusak">Rusak</option>
                <option value="dalam_perbaikan">Dalam Perbaikan</option>
                <option value="tidak_aktif">Tidak Aktif</option>
            </select>
        </div>
        <div class="filter-group">
            <span class="filter-label">Cari</span>
            <div class="table-search-wrap">
                <i class="bi bi-search table-search-icon"></i>
                <input type="text" id="searchRepair" class="table-search" placeholder="Kode aset / deskripsi...">
            </div>
        </div>
        <button id="btnApplyFilter" class="btn-filter-apply">
            <i class="bi bi-funnel-fill"></i> Filter
        </button>
        <button id="btnResetFilter" class="btn-filter-reset">Reset</button>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="repair-table">
            <thead>
                <tr>
                    <th class="ps-4" style="width:50px;">#</th>
                    <th style="width:140px;">Aset</th>
                    <th style="width:110px;">Tanggal</th>
                    <th>Deskripsi</th>
                    <th style="width:120px;">Biaya</th>
                    <th style="width:110px;">Status</th>
                    <th style="width:130px;">Kondisi Akhir</th>
                </tr>
            </thead>
            <tbody id="repairTableBody">
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                        <span class="text-muted" style="font-size:13px;">Memuat data...</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div id="paginationContainer" class="px-4 py-3"></div>

</div>

<script>
    const PAGE_SIZE = 15;

    const STATUS_BADGE = {
        selesai : 'badge-soft-success',
        pending : 'badge-soft-warning',
        gagal   : 'badge-soft-danger',
    };

    const KONDISI_BADGE = {
        baik             : 'badge-soft-success',
        rusak            : 'badge-soft-danger',
        dalam_perbaikan  : 'badge-soft-warning',
        tidak_aktif      : 'badge-soft-secondary',
    };

    const KONDISI_LABEL = {
        baik             : 'Baik',
        rusak            : 'Rusak',
        dalam_perbaikan  : 'Dalam Perbaikan',
        tidak_aktif      : 'Tidak Aktif',
    };

    let currentPage = 1;

    function formatRupiah(val) {
        const n = parseFloat(val);
        if (isNaN(n) || n === 0) return '<span style="color:var(--clr-txt-400);">—</span>';
        return 'Rp ' + n.toLocaleString('id-ID');
    }

    function getFilters() {
        return {
            search  : document.getElementById('searchRepair').value.trim(),
            status  : document.getElementById('filterStatus').value,
            kondisi : document.getElementById('filterKondisi').value,
        };
    }

    function renderTable(rows, offset) {
        const tbody = document.getElementById('repairTableBody');

        if (!rows || rows.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted" style="font-size:13px;">
                        <i class="bi bi-wrench me-2 opacity-50"></i>Tidak ada data perbaikan.
                    </td>
                </tr>`;
            return;
        }

        tbody.innerHTML = rows.map((r, i) => {
            const statusBadge  = r.status_akhir
                ? `<span class="badge-soft ${STATUS_BADGE[r.status_akhir] ?? 'badge-soft-secondary'}">${escHtml(r.status_akhir)}</span>`
                : '<span style="color:var(--clr-txt-400);font-size:12px;">—</span>';

            const kondisiBadge = r.kondisi_akhir
                ? `<span class="badge-soft ${KONDISI_BADGE[r.kondisi_akhir] ?? 'badge-soft-secondary'}">${escHtml(KONDISI_LABEL[r.kondisi_akhir] ?? r.kondisi_akhir)}</span>`
                : '<span style="color:var(--clr-txt-400);font-size:12px;">—</span>';

            return `
                <tr>
                    <td class="ps-4 text-muted" style="font-size:12px;">${offset + i + 1}</td>
                    <td>
                        <div class="asset-cell-code">${escHtml(r.kode_aset ?? '—')}</div>
                        <div class="asset-cell-sub">${escHtml(r.merk ?? '')} ${escHtml(r.model ?? '')}</div>
                    </td>
                    <td style="font-size:12.5px; white-space:nowrap;">${escHtml(r.tanggal?.substring(0,10) ?? '—')}</td>
                    <td class="desc-cell" title="${escHtml(r.deskripsi ?? '')}">${escHtml(r.deskripsi ?? '—')}</td>
                    <td class="biaya-cell">${formatRupiah(r.biaya)}</td>
                    <td>${statusBadge}</td>
                    <td>${kondisiBadge}</td>
                </tr>`;
        }).join('');
    }

    function renderPagination(page, totalPages, total) {
        document.getElementById('repairMeta').textContent = `Total ${total} data`;

        const container = document.getElementById('paginationContainer');
        if (totalPages <= 1) { container.innerHTML = ''; return; }

        const hasPrev = page > 1;
        const hasNext = page < totalPages;

        let start = Math.max(1, page - 2);
        let end   = Math.min(totalPages, start + 4);
        if (end - start < 4) start = Math.max(1, end - 4);

        let pageNums = '';
        for (let p = start; p <= end; p++) {
            pageNums += `<li class="page-item ${p === page ? 'active' : ''}">
                <button class="page-link" data-page="${p}">${p}</button></li>`;
        }

        container.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted" style="font-size:12px;">
                    Halaman <strong>${page}</strong> dari <strong>${totalPages}</strong>
                    &mdash; Total <strong>${total}</strong> data
                </small>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item ${!hasPrev ? 'disabled' : ''}">
                        <button class="page-link" data-page="${page - 1}"><i class="bi bi-chevron-left"></i></button>
                    </li>
                    ${pageNums}
                    <li class="page-item ${!hasNext ? 'disabled' : ''}">
                        <button class="page-link" data-page="${page + 1}"><i class="bi bi-chevron-right"></i></button>
                    </li>
                </ul>
            </div>`;

        container.querySelectorAll('[data-page]').forEach(btn => {
            btn.addEventListener('click', () => {
                const p = parseInt(btn.dataset.page);
                if (p >= 1 && p <= totalPages) loadRepairs(p);
            });
        });
    }

    async function loadRepairs(page = 1) {
        currentPage = page;

        document.getElementById('repairTableBody').innerHTML = `
            <tr><td colspan="7" class="text-center py-5">
                <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                <span class="text-muted" style="font-size:13px;">Memuat data...</span>
            </td></tr>`;

        const f = getFilters();
        const filters = [];

        if (f.status) {
            filters.push({ field: 'status_akhir', value: f.status, type: 'exact' });
        }
        if (f.kondisi) {
            filters.push({ field: 'kondisi_akhir', value: f.kondisi, type: 'exact' });
        }

        const params = new URLSearchParams({
            page,
            size: PAGE_SIZE,
            ...(f.search  && { search: f.search }),
            ...(filters.length && { filters: JSON.stringify(filters) }),
        });

        const res = await apiFetch(`/api/repairs?${params}`);
        if (!res) return;

        if (res.status === 403) {
            document.getElementById('repairTableBody').innerHTML = `
                <tr><td colspan="7" class="text-center py-5 text-muted" style="font-size:13px;">
                    <i class="bi bi-shield-lock me-2 opacity-50"></i>Akses ditolak.
                </td></tr>`;
            return;
        }

        const json = await res.json();
        if (!json.success) {
            showToast(json.message ?? 'Gagal memuat data.', 'danger');
            return;
        }

        const { data, last_page, total } = json.data;
        const offset = (page - 1) * PAGE_SIZE;

        renderTable(data, offset);
        renderPagination(page, last_page, total);
    }

    document.getElementById('btnApplyFilter').addEventListener('click', () => loadRepairs(1));
    document.getElementById('btnResetFilter').addEventListener('click', () => {
        document.getElementById('filterStatus').value  = '';
        document.getElementById('filterKondisi').value = '';
        document.getElementById('searchRepair').value  = '';
        loadRepairs(1);
    });

    // Enter pada search field langsung trigger filter
    document.getElementById('searchRepair').addEventListener('keydown', e => {
        if (e.key === 'Enter') loadRepairs(1);
    });

    loadRepairs();
</script>