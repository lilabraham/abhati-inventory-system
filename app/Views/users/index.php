<?php // Akses halaman sudah di-guard penuh via auth()->user()->can('users.manage') di UserManagement::index() 
?>

<style>
    /* ── Avatar ── */
    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12.5px;
        font-weight: 700;
        flex-shrink: 0;
        color: #fff;
        letter-spacing: 0.03em;
        user-select: none;
    }

    .avatar-superadmin {
        background: linear-gradient(135deg, #7c3aed, #a855f7);
    }

    .avatar-editor {
        background: linear-gradient(135deg, #2563eb, #3b82f6);
    }

    /* ── Stat Chips ── */
    .user-stats-row {
        display: flex;
        gap: 8px;
        margin-top: 14px;
        flex-wrap: wrap;
    }

    .user-stat-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 13px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
    }

    .user-stat-chip .chip-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
        flex-shrink: 0;
    }

    .chip-total {
        background: #f1f5f9;
        color: #475569;
    }

    .chip-aktif {
        background: #dcfce7;
        color: #15803d;
    }

    .chip-banned {
        background: #fee2e2;
        color: #b91c1c;
    }

    /* ── Search ── */
    .table-search-wrap {
        position: relative;
    }

    .table-search {
        padding: 7px 12px 7px 34px;
        border-radius: 10px;
        border: 1px solid var(--clr-border);
        font-size: 12.5px;
        color: var(--clr-txt-600);
        background: var(--clr-surface);
        transition: var(--transition);
        width: 220px;
        outline: none;
    }

    .table-search:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, .1);
    }

    .table-search-icon {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--clr-txt-400);
        font-size: 13px;
        pointer-events: none;
    }

    /* ── User Table ── */
    .user-table {
        width: 100%;
        border-collapse: collapse;
    }

    .user-table thead th {
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

    .user-table tbody tr {
        border-bottom: 1px solid var(--clr-sep);
        transition: background .12s;
    }

    .user-table tbody tr:last-child {
        border-bottom: none;
    }

    .user-table tbody tr:hover {
        background: #fafbfc;
    }

    .user-table tbody td {
        padding: 12px 14px;
        border: none !important;
        vertical-align: middle;
        font-size: 13.5px;
        color: var(--clr-txt-600);
    }

    /* ── User Cell ── */
    .user-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .user-cell-name {
        font-weight: 600;
        color: var(--clr-txt-900);
        font-size: 13.5px;
        line-height: 1.3;
    }

    .user-cell-sub {
        font-size: 11.5px;
        color: var(--clr-txt-400);
        margin-top: 1px;
    }
</style>

<!-- ══════════════════════════════════════════════════════
     PAGE HEADER
══════════════════════════════════════════════════════ -->
<div class="d-flex justify-content-between align-items-start mb-4 gap-3">
    <div>
        <h1 class="page-title">User Management</h1>
        <p class="page-subtitle">Kelola akun pengguna yang dapat mengakses sistem Abhati Group</p>
        <div class="user-stats-row">
            <span class="user-stat-chip chip-total">
                <span class="chip-dot"></span>
                <span id="chipTotalVal">—</span> Total
            </span>
            <span class="user-stat-chip chip-aktif">
                <span class="chip-dot"></span>
                <span id="chipAktifVal">—</span> Aktif
            </span>
            <span class="user-stat-chip chip-banned">
                <span class="chip-dot"></span>
                <span id="chipBannedVal">—</span> Banned
            </span>
        </div>
    </div>
    <button class="btn-tambah" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
        <i class="bi bi-person-plus-fill"></i> Tambah User
    </button>
</div>

<!-- ══════════════════════════════════════════════════════
     TABLE CARD
══════════════════════════════════════════════════════ -->
<div class="table-card">

    <div class="table-card-header">
        <div class="table-card-header-title">
            <span class="dot"></span>
            Daftar Pengguna
        </div>
        <div class="table-search-wrap">
            <i class="bi bi-search table-search-icon"></i>
            <input type="text" id="searchUser" class="table-search" placeholder="Cari username atau email...">
        </div>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0 user-table">
            <thead>
                <tr>
                    <th class="ps-4" style="width:48px;">#</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th class="text-center pe-4" style="width:100px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                <tr>
                    <td colspan="6" class="text-center py-5">
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
     MODAL TAMBAH USER
══════════════════════════════════════════════════════ -->
<div class="modal fade" id="modalTambahUser" tabindex="-1" aria-labelledby="modalTambahUserLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahUserLabel">
                    <i class="bi bi-person-plus-fill me-2 text-primary"></i>Tambah User Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info mb-4" style="font-size:12.5px; border-radius:10px;">
                    <i class="bi bi-info-circle me-1"></i>
                    Akun langsung aktif dengan role <strong>Editor</strong>.
                </div>
                <div class="mb-3">
                    <label class="form-label">Username <span class="text-danger">*</span></label>
                    <input type="text" id="inputUsername" class="form-control" placeholder="min. 3 karakter" maxlength="30" autocomplete="off">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" id="inputEmail" class="form-control" placeholder="contoh@email.com" autocomplete="off">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" id="inputPassword" class="form-control" placeholder="min. 8 karakter" autocomplete="new-password">
                </div>
                <div id="formUserError" class="d-none"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="btnSimpanUser" class="btn btn-primary fw-semibold">
                    <i class="bi bi-person-check me-1"></i> Buat Akun
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalConfirmAction" tabindex="-1" aria-labelledby="modalConfirmActionLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold fs-6" id="modalConfirmActionLabel">
                    <i class="bi bi-exclamation-triangle me-2 text-danger"></i>Konfirmasi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0" style="font-size:13.5px;" id="confirmActionText"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger fw-semibold" id="btnConfirmAction">Lanjutkan</button>
            </div>
        </div>
    </div>
</div>

<script>
    // ─── Helpers ────────────────────────────────────────────────
    const escHtml = window.escHtml;

    const avatarInitials = name =>
        String(name).trim().slice(0, 2).toUpperCase();

    function confirmAction(message) {
        return new Promise(resolve => {
            const modalEl = document.getElementById('modalConfirmAction');
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            document.getElementById('confirmActionText').textContent = message;

            const btnConfirm = document.getElementById('btnConfirmAction');
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

    // ─── State ───────────────────────────────────────────────────
    let currentPage = 1;
    let allUsers = [];
    let filteredUsers = [];
    const perPage = 15;

    // ─── Stat Chips ──────────────────────────────────────────────
    function updateStatChips(users) {
        const total = users.length;
        const banned = users.filter(u => u.banned).length;
        const aktif = total - banned;
        document.getElementById('chipTotalVal').textContent = total;
        document.getElementById('chipAktifVal').textContent = aktif;
        document.getElementById('chipBannedVal').textContent = banned;
    }

    // ─── Render ──────────────────────────────────────────────────
    function renderTable(users) {
        const tbody = document.getElementById('userTableBody');

        if (!users || users.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted" style="font-size:13px;">
                        <i class="bi bi-people me-2 opacity-50"></i>Tidak ada user ditemukan.
                    </td>
                </tr>`;
            return;
        }

        const start = (currentPage - 1) * perPage;
        const paged = users.slice(start, start + perPage);

        tbody.innerHTML = paged.map((u, i) => {
            const isSuperadmin = u.group === 'superadmin';
            const isBanned = u.banned;

            const avatarClass = isSuperadmin ? 'avatar-superadmin' : 'avatar-editor';
            const roleBadge = isSuperadmin ?
                `<span class="badge-soft badge-soft-purple">Superadmin</span>` :
                `<span class="badge-soft badge-soft-indigo">Editor</span>`;
            const statusBadge = isBanned ?
                `<span class="badge-soft badge-soft-danger">Banned</span>` :
                `<span class="badge-soft badge-soft-success">Aktif</span>`;

            // AFTER
            const banBtn = isBanned ?
                `<button class="btn-action btn-action-unban"
                data-id="${u.id}" data-action="unban" title="Aktifkan User">
                    <i class="bi bi-person-check"></i>
                </button>` :
                `<button class="btn-action btn-action-ban"
                    data-id="${u.id}" data-action="ban" title="Ban User">
                    <i class="bi bi-slash-circle"></i>
                </button>`;

            const actions = isSuperadmin ?
                `<span class="text-muted" style="font-size:11px;">—</span>` :
                `<div class="d-flex align-items-center justify-content-center gap-1">
                        ${banBtn}
                        <button class="btn-action btn-action-delete"
                            data-id="${u.id}" data-action="delete" title="Hapus User">
                            <i class="bi bi-trash"></i>
                        </button>
                   </div>`;

            return `
                <tr>
                    <td class="ps-4 text-muted" style="font-size:12px;">${start + i + 1}</td>
                    <td>
                        <div class="user-cell">
                            <div class="user-avatar ${avatarClass}">
                                ${avatarInitials(u.username)}
                            </div>
                            <div>
                                <div class="user-cell-name">${escHtml(u.username)}</div>
                                ${isSuperadmin ? '<div class="user-cell-sub">Administrator</div>' : ''}
                            </div>
                        </div>
                    </td>
                    <td style="color:var(--clr-txt-600);">${escHtml(u.email)}</td>
                    <td>${roleBadge}</td>
                    <td>${statusBadge}</td>
                    <td class="text-center pe-4">${actions}</td>
                </tr>`;
        }).join('');

        tbody.querySelectorAll('[data-action]').forEach(btn => {
            btn.addEventListener('click', () => {
                const {
                    id,
                    action
                } = btn.dataset;
                const user = allUsers.find(u => String(u.id) === id);
                const username = user?.username ?? '';
                if (action === 'ban') handleBan(id, username);
                if (action === 'unban') handleUnban(id, username);
                if (action === 'delete') handleDelete(id, username);
            });
        });
    }

    function renderPagination(total) {
        const container = document.getElementById('paginationContainer');
        const totalPages = Math.ceil(total / perPage);
        if (totalPages <= 1) {
            container.innerHTML = '';
            return;
        }

        const hasPrev = currentPage > 1;
        const hasNext = currentPage < totalPages;

        let start = Math.max(1, currentPage - 2);
        let end = Math.min(totalPages, start + 4);
        if (end - start < 4) start = Math.max(1, end - 4);

        let pageNums = '';
        for (let i = start; i <= end; i++) {
            pageNums += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <button class="page-link" data-page="${i}">${i}</button>
                </li>`;
        }

        container.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted" style="font-size:12px;">
                    Halaman <strong>${currentPage}</strong> dari <strong>${totalPages}</strong>
                    &mdash; Total <strong>${total}</strong> user
                </small>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item ${!hasPrev ? 'disabled' : ''}">
                        <button class="page-link" data-page="${currentPage - 1}">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                    </li>
                    ${pageNums}
                    <li class="page-item ${!hasNext ? 'disabled' : ''}">
                        <button class="page-link" data-page="${currentPage + 1}">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </li>
                </ul>
            </div>`;

        container.querySelectorAll('[data-page]').forEach(btn => {
            btn.addEventListener('click', () => {
                const p = parseInt(btn.dataset.page);
                if (p >= 1 && p <= totalPages) {
                    currentPage = p;
                    renderTable(filteredUsers);
                    renderPagination(filteredUsers.length);
                }
            });
        });
    }

    // ─── Search ──────────────────────────────────────────────────
    document.getElementById('searchUser').addEventListener('input', function() {
        const q = this.value.trim().toLowerCase();
        filteredUsers = q ?
            allUsers.filter(u =>
                u.username.toLowerCase().includes(q) ||
                u.email.toLowerCase().includes(q)
            ) :
            allUsers;
        currentPage = 1;
        renderTable(filteredUsers);
        renderPagination(filteredUsers.length);
    });

    // ─── Load Users ──────────────────────────────────────────────
    async function loadUsers(targetPage = 1) {
        document.getElementById('userTableBody').innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-5">
                    <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                    <span class="text-muted" style="font-size:13px;">Memuat data...</span>
                </td>
            </tr>`;

        // AFTER
        const res = await apiFetch('/api/users');
        if (!res) {
            document.getElementById('userTableBody').innerHTML = `
        <tr><td colspan="6" class="text-center py-5 text-muted" style="font-size:13px;">
            <i class="bi bi-wifi-off me-2 opacity-50"></i>Sesi habis. Silakan <a href="/login">login ulang</a>.
        </td></tr>`;
            return;
        }
        if (res.status === 403) {
            document.getElementById('userTableBody').innerHTML = `
            <tr><td colspan="6" class="text-center py-5 text-muted" style="font-size:13px;">
            <i class="bi bi-shield-lock me-2 opacity-50"></i>Anda tidak memiliki akses untuk melihat data ini.
            </td></tr>`;
            return;
        }

        const json = await res.json();
        if (!json.success) {
            showToast(json.message ?? 'Gagal memuat data.', 'danger');
            return;
        }

        allUsers = json.data.data ?? [];
        filteredUsers = allUsers;
        currentPage = targetPage;

        // Reset search input saat reload
        document.getElementById('searchUser').value = '';

        updateStatChips(allUsers);
        renderTable(filteredUsers);
        renderPagination(filteredUsers.length);
    }

    // ─── Tambah User ─────────────────────────────────────────────
    document.getElementById('btnSimpanUser').addEventListener('click', async () => {
        const btn = document.getElementById('btnSimpanUser');
        const errEl = document.getElementById('formUserError');
        const username = document.getElementById('inputUsername').value.trim();
        const email = document.getElementById('inputEmail').value.trim();
        const password = document.getElementById('inputPassword').value;

        errEl.className = 'd-none';
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';

        const res = await apiFetch('/api/users', {
            method: 'POST',
            body: JSON.stringify({
                username,
                email,
                password
            }),
        });

        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-person-check me-1"></i> Buat Akun';
        if (!res) return;

        const json = await res.json();

        if (json.success) {
            bootstrap.Modal.getInstance(document.getElementById('modalTambahUser')).hide();
            showToast(`User "${escHtml(username)}" berhasil dibuat.`, 'success');
            loadUsers(1);
        } else {
            const errors = json.data ?
                Object.values(json.data).flat().map(escHtml).join('<br>') :
                escHtml(json.message ?? 'Terjadi kesalahan.');
            errEl.className = 'alert alert-danger mt-2';
            errEl.style.cssText = 'font-size:12.5px; border-radius:10px;';
            errEl.innerHTML = errors;
        }
    });

    document.getElementById('modalTambahUser').addEventListener('hidden.bs.modal', () => {
        document.getElementById('inputUsername').value = '';
        document.getElementById('inputEmail').value = '';
        document.getElementById('inputPassword').value = '';
        document.getElementById('formUserError').className = 'd-none';
    });

    // ─── Ban ─────────────────────────────────────────────────────
    async function handleBan(id, username) {
        const confirmed = await confirmAction(`Ban user "${username}"? User tidak dapat login hingga di-unban.`);
        if (!confirmed) return;
        const res = await apiFetch(`/api/users/${id}/ban`, {
            method: 'PUT'
        });
        if (!res) return;
        const json = await res.json();
        json.success ?
            (showToast(`User "${username}" berhasil di-ban.`, 'warning'), loadUsers(currentPage)) :
            showToast(json.message ?? 'Gagal ban user.', 'danger');
    }

    // ─── Unban ───────────────────────────────────────────────────
    async function handleUnban(id, username) {
        const confirmed = await confirmAction(`Aktifkan kembali user "${username}"?`);
        if (!confirmed) return;
        const res = await apiFetch(`/api/users/${id}/unban`, {
            method: 'PUT'
        });
        if (!res) return;
        const json = await res.json();
        json.success ?
            (showToast(`User "${username}" berhasil diaktifkan.`, 'success'), loadUsers(currentPage)) :
            showToast(json.message ?? 'Gagal unban user.', 'danger');
    }

    // ─── Delete ──────────────────────────────────────────────────
    async function handleDelete(id, username) {
        const confirmed = await confirmAction(`Hapus user "${username}"? Aksi ini tidak dapat dibatalkan.`);
        if (!confirmed) return;
        const res = await apiFetch(`/api/users/${id}`, {
            method: 'DELETE'
        });
        if (!res) return;
        const json = await res.json();
        json.success ?
            (showToast(`User "${username}" berhasil dihapus.`, 'success'), loadUsers(currentPage)) :
            showToast(json.message ?? 'Gagal hapus user.', 'danger');
    }

    // ─── Init ─────────────────────────────────────────────────────
    loadUsers();
</script>