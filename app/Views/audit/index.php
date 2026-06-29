<?php // Guard via AuditController::index() — can('audit.view') ?>

<style>
    /* ── Filter Bar ── */
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
        box-shadow: 0 0 0 3px rgba(99, 102, 241, .1);
    }

    .filter-select { min-width: 130px; }
    .filter-date   { min-width: 140px; }

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

    /* ── Audit Table ── */
    .audit-table {
        width: 100%;
        border-collapse: collapse;
    }

    .audit-table thead th {
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

    .audit-table tbody tr {
        border-bottom: 1px solid var(--clr-sep);
        transition: background .12s;
    }

    .audit-table tbody tr:last-child { border-bottom: none; }
    .audit-table tbody tr:hover      { background: #fafbfc; }

    .audit-table tbody td {
        padding: 11px 14px;
        border: none !important;
        vertical-align: middle;
        font-size: 13px;
        color: var(--clr-txt-600);
    }

    .desc-cell {
        max-width: 320px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: var(--clr-txt-600);
    }

    .ip-cell {
        font-family: 'Courier New', monospace;
        font-size: 12px;
        color: var(--clr-txt-400);
    }

    .ts-cell {
        font-size: 12px;
        color: var(--clr-txt-400);
        white-space: nowrap;
    }
</style>

<!-- ══ PAGE HEADER ══ -->
<div class="d-flex justify-content-between align-items-start mb-4 gap-3">
    <div>
        <h1 class="page-title">Audit Trail</h1>
        <p class="page-subtitle">Riwayat seluruh aktivitas sistem Abhati Group — immutable, read-only</p>
    </div>
</div>

<!-- ══ TABLE CARD ══ -->
<div class="table-card">

    <!-- Header -->
    <div class="table-card-header">
        <div class="table-card-header-title">
            <span class="dot"></span>
            Log Aktivitas
        </div>
        <small id="auditMeta">—</small>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <div class="filter-group">
            <span class="filter-label">Action</span>
            <select id="filterAction" class="filter-control filter-select">
                <option value="">Semua Action</option>
                <option value="CREATE">CREATE</option>
                <option value="UPDATE">UPDATE</option>
                <option value="DELETE">DELETE</option>
                <option value="IMPORT">IMPORT</option>
                <option value="EXPORT">EXPORT</option>
                <option value="LOGIN">LOGIN</option>
                <option value="LOGOUT">LOGOUT</option>
            </select>
        </div>
        <div class="filter-group">
            <span class="filter-label">Module</span>
            <select id="filterModule" class="filter-control filter-select">
                <option value="">Semua Module</option>
                <option value="assets">Assets</option>
                <option value="repairs">Repairs</option>
                <option value="users">Users</option>
            </select>
        </div>
        <div class="filter-group">
            <span class="filter-label">Dari</span>
            <input type="date" id="filterDateFrom" class="filter-control filter-date">
        </div>
        <div class="filter-group">
            <span class="filter-label">Sampai</span>
            <input type="date" id="filterDateTo" class="filter-control filter-date">
        </div>
        <button id="btnApplyFilter" class="btn-filter-apply">
            <i class="bi bi-funnel-fill"></i> Filter
        </button>
        <button id="btnResetFilter" class="btn-filter-reset">Reset</button>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="audit-table">
            <thead>
                <tr>
                    <th class="ps-4" style="width:60px;">#</th>
                    <th style="width:110px;">Action</th>
                    <th style="width:130px;">Module</th>
                    <th style="width:100px;">Record</th>
                    <th>Deskripsi</th>
                    <th style="width:120px;">User</th>
                    <th style="width:120px;">IP Address</th>
                    <th style="width:150px;">Waktu</th>
                </tr>
            </thead>
            <tbody id="auditTableBody">
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

<script>
    // ── Config ──────────────────────────────────────────────────
    const PAGE_SIZE = 20;

    const ACTION_BADGE = {
        CREATE  : 'badge-soft-success',
        UPDATE  : 'badge-soft-warning',
        DELETE  : 'badge-soft-danger',
        LOGIN   : 'badge-soft-indigo',
        LOGOUT  : 'badge-soft-secondary',
        IMPORT  : 'badge-soft-purple',
        EXPORT  : 'badge-soft-purple',
    };

    // ── State ────────────────────────────────────────────────────
    let currentPage = 1;

    function getFilters() {
        return {
            action    : document.getElementById('filterAction').value,
            module    : document.getElementById('filterModule').value,
            date_from : document.getElementById('filterDateFrom').value,
            date_to   : document.getElementById('filterDateTo').value,
        };
    }

    // ── Render ───────────────────────────────────────────────────
    function renderTable(logs, offset) {
        const tbody = document.getElementById('auditTableBody');

        if (!logs || logs.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted" style="font-size:13px;">
                        <i class="bi bi-journal-x me-2 opacity-50"></i>Tidak ada log ditemukan.
                    </td>
                </tr>`;
            return;
        }

        tbody.innerHTML = logs.map((log, i) => {
            const badgeCls = ACTION_BADGE[log.action] ?? 'badge-soft-secondary';
            const ts = log.created_at
                ? log.created_at.replace('T', ' ').substring(0, 16)
                : '—';

            return `
                <tr>
                    <td class="ps-4 text-muted" style="font-size:12px;">${offset + i + 1}</td>
                    <td><span class="badge-soft ${escHtml(badgeCls)}">${escHtml(log.action)}</span></td>
                    <td style="font-size:12.5px;">${escHtml(log.module ?? '—')}</td>
                    <td>
                        <code style="font-size:11.5px;">${escHtml(log.record_type ?? '')}:${escHtml(String(log.record_id ?? ''))}</code>
                    </td>
                    <td class="desc-cell" title="${escHtml(log.description ?? '')}">${escHtml(log.description ?? '—')}</td>
                    <td style="font-size:12.5px; font-weight:600; color:var(--clr-txt-900);">${escHtml(log.username ?? '—')}</td>
                    <td class="ip-cell">${escHtml(log.ip_address ?? '—')}</td>
                    <td class="ts-cell">${escHtml(ts)}</td>
                </tr>`;
        }).join('');
    }

    function renderPagination(page, totalPages, total) {
        const container = document.getElementById('paginationContainer');

        document.getElementById('auditMeta').textContent =
            `Total ${total} log`;

        if (totalPages <= 1) {
            container.innerHTML = '';
            return;
        }

        const hasPrev = page > 1;
        const hasNext = page < totalPages;

        let start = Math.max(1, page - 2);
        let end   = Math.min(totalPages, start + 4);
        if (end - start < 4) start = Math.max(1, end - 4);

        let pageNums = '';
        for (let p = start; p <= end; p++) {
            pageNums += `
                <li class="page-item ${p === page ? 'active' : ''}">
                    <button class="page-link" data-page="${p}">${p}</button>
                </li>`;
        }

        container.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted" style="font-size:12px;">
                    Halaman <strong>${page}</strong> dari <strong>${totalPages}</strong>
                    &mdash; Total <strong>${total}</strong> log
                </small>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item ${!hasPrev ? 'disabled' : ''}">
                        <button class="page-link" data-page="${page - 1}">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                    </li>
                    ${pageNums}
                    <li class="page-item ${!hasNext ? 'disabled' : ''}">
                        <button class="page-link" data-page="${page + 1}">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </li>
                </ul>
            </div>`;

        container.querySelectorAll('[data-page]').forEach(btn => {
            btn.addEventListener('click', () => {
                const p = parseInt(btn.dataset.page);
                if (p >= 1 && p <= totalPages) loadAudit(p);
            });
        });
    }

    // ── Load ─────────────────────────────────────────────────────
    async function loadAudit(page = 1) {
        currentPage = page;

        document.getElementById('auditTableBody').innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-5">
                    <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                    <span class="text-muted" style="font-size:13px;">Memuat data...</span>
                </td>
            </tr>`;

        const f = getFilters();
        const params = new URLSearchParams({
            page,
            size : PAGE_SIZE,
            ...(f.action    && { action    : f.action }),
            ...(f.module    && { module    : f.module }),
            ...(f.date_from && { date_from : f.date_from }),
            ...(f.date_to   && { date_to   : f.date_to }),
        });

        const res = await apiFetch(`/api/audit?${params}`);
        if (!res) return;

        if (res.status === 403) {
            document.getElementById('auditTableBody').innerHTML = `
                <tr><td colspan="8" class="text-center py-5 text-muted" style="font-size:13px;">
                    <i class="bi bi-shield-lock me-2 opacity-50"></i>Akses ditolak.
                </td></tr>`;
            return;
        }

        const json = await res.json();
        if (!json.success) {
            showToast(json.message ?? 'Gagal memuat audit log.', 'danger');
            return;
        }

        const { logs, total, total_pages } = json.data;
        const offset = (page - 1) * PAGE_SIZE;

        renderTable(logs, offset);
        renderPagination(page, total_pages, total);
    }

    // ── Filter Events ────────────────────────────────────────────
    document.getElementById('btnApplyFilter').addEventListener('click', () => loadAudit(1));

    document.getElementById('btnResetFilter').addEventListener('click', () => {
        document.getElementById('filterAction').value   = '';
        document.getElementById('filterModule').value   = '';
        document.getElementById('filterDateFrom').value = '';
        document.getElementById('filterDateTo').value   = '';
        loadAudit(1);
    });

    // ── Init ─────────────────────────────────────────────────────
    loadAudit();
</script>