<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <title><?= esc($title ?? 'Inventaris Laptop — Abhati Group') ?></title>

    <script>
        window.KONDISI_CONFIG = {
            baik: {
                label: 'Baik',
                cls: 'success'
            },
            rusak: {
                label: 'Rusak',
                cls: 'danger'
            },
            dalam_perbaikan: {
                label: 'Dalam Perbaikan',
                cls: 'warning'
            },
            tidak_aktif: {
                label: 'Tidak Aktif',
                cls: 'secondary'
            },
        };

        window.escHtml = function(str) {
            if (str == null) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        };

        window.apiFetch = async (url, options = {}) => {
            const getMeta = () =>
                document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            const defaultHeaders = {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': getMeta(),
            };

            try {
                const res = await fetch(url, {
                    ...options,
                    credentials: 'same-origin',
                    headers: {
                        ...defaultHeaders,
                        ...(options.headers ?? {}),
                    },
                });

                // 401 = unauthenticated → redirect login
                // 403 = forbidden → kembalikan ke caller, bukan redirect
                if (res.status === 401) {
                    window.location.href = '<?= base_url('login') ?>';
                    return null;
                }

                return res;
            } catch (_) {
                return null;
            }
        };
    </script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Pastikan file CSS ini benar-benar ada di folder public/ui/css/ -->
    <link rel="stylesheet" href="<?= base_url('ui/css/sidebar.css') ?>">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background: #f0f2f7;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            margin: 0;
            min-height: 100vh;
            color: #1e293b;
            background-image:
                radial-gradient(ellipse at 20% 0%, rgba(148, 163, 184, 0.12) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 100%, rgba(99, 102, 241, 0.05) 0%, transparent 60%);
        }

        .main-content {
            margin-left: calc(var(--sb-width-expanded) + 24px);
            transition: margin-left var(--sb-transition);
            min-height: 100vh;
            padding: 32px 32px 40px 16px;
        }

        body.sidebar-collapsed .main-content {
            margin-left: calc(var(--sb-width-collapsed) + 24px);
        }

        .flash-wrapper {
            margin-bottom: 1.25rem;
        }

        .flash-wrapper .alert {
            border-radius: 14px;
            font-size: 13.5px;
            font-weight: 500;
            padding: 14px 18px;
            border: none;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06), 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .flash-wrapper .alert-danger {
            background: #fff1f2;
            color: #be123c;
        }

        .flash-wrapper .alert-danger .btn-close {
            filter: invert(20%) sepia(80%) saturate(500%) hue-rotate(320deg);
        }

        .flash-wrapper .alert-success {
            background: #f0fdf4;
            color: #15803d;
        }

        .flash-wrapper .alert-success .btn-close {
            filter: invert(30%) sepia(60%) saturate(400%) hue-rotate(100deg);
        }

        .card {
            border-radius: 16px;
            border: none;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06), 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(100, 116, 139, 0.25);
            border-radius: 99px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(100, 116, 139, 0.45);
        }

        .btn {
            font-size: 13.5px;
            font-weight: 600;
            border-radius: 10px;
        }

        .btn-sm {
            font-size: 12px;
            border-radius: 8px;
        }

        .badge {
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        code {
            background: #f1f5f9;
            color: #3b82f6;
            padding: 2px 7px;
            border-radius: 6px;
            font-size: 12px;
        }

        .modal-content {
            border-radius: 18px;
            border: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            border-bottom: 1px solid #f1f4f8;
            padding: 20px 24px 16px;
        }

        .modal-footer {
            border-top: 1px solid #f1f4f8;
            padding: 16px 24px 20px;
        }

        .modal-body {
            padding: 20px 24px;
        }

        .modal-title {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            border-color: #e2e8f0;
            font-size: 13.5px;
            padding: 9px 13px;
            color: #1e293b;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
        }

        .form-label {
            font-size: 12.5px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 6px;
        }

        .table>thead>tr>th {
            font-size: 11.5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #64748b;
            border-bottom-width: 1px;
        }

        /* ══════════════════════════════════════
           SHARED DESIGN TOKENS
        ══════════════════════════════════════ */
        /* FIX — restore nilai aslinya */
        :root {
            --clr-surface: #ffffff;
            --clr-border: #eaecf0;
            --clr-sep: #f2f4f7;
            --clr-txt-900: #0d1117;
            --clr-txt-600: #4b5563;
            --clr-txt-400: #9ca3af;
            --clr-blue: #3b82f6;
            --radius-card: 18px;
            --radius-btn: 9px;
            --shadow-card: 0 0 0 1px rgba(0, 0, 0, .045), 0 2px 4px rgba(0, 0, 0, .04), 0 10px 28px rgba(0, 0, 0, .07);
            --shadow-hover: 0 0 0 1px rgba(0, 0, 0, .05), 0 4px 8px rgba(0, 0, 0, .06), 0 16px 36px rgba(0, 0, 0, .1);
            --transition: all .18s cubic-bezier(.4, 0, .2, 1);
        }

        /* ══ PAGE HEADER ══ */
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

        /* ══════════════════════════════════════
           TABLE CARD SHELL
        ══════════════════════════════════════ */
        .table-card {
            background: var(--clr-surface);
            border-radius: var(--radius-card);
            box-shadow: var(--shadow-card);
            overflow: hidden;
        }

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

        /* ══════════════════════════════════════
           SOFT BADGES
        ══════════════════════════════════════ */
        .badge-soft {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 11.5px;
            font-weight: 600;
            padding: 4px 11px;
            border-radius: 999px;
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

        .badge-soft-secondary {
            background: #f3f4f6;
            color: #4b5563;
        }

        .badge-soft-purple {
            background: #f3e8ff;
            color: #7c3aed;
        }

        .badge-soft-indigo {
            background: #eef2ff;
            color: #4338ca;
        }

        /* ══════════════════════════════════════
           ACTION BUTTONS
        ══════════════════════════════════════ */
        .btn-action {
            width: 30px;
            height: 30px;
            padding: 0;
            border-radius: 8px;
            border: none;
            background: transparent;
            color: #c4cdd6;
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

        .btn-action-unban:hover {
            background: #dcfce7;
            color: #15803d;
        }

        .btn-action-ban:hover {
            background: #fef3c7;
            color: #d97706;
        }

        /* ══════════════════════════════════════
           PAGINATION
        ══════════════════════════════════════ */
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
</head>

<body>

    <!-- Pastikan file app/Views/layout/sidebar.php benar-benar ada -->
    <?= $this->include('layouts/sidebar') ?>

    <main class="main-content" id="main-content">

        <?php if (session()->getFlashdata('error')): ?>
            <div class="flash-wrapper">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-octagon me-2"></i>
                    <?= esc(session()->getFlashdata('error')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        <?php endif ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="flash-wrapper">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    <?= esc(session()->getFlashdata('success')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        <?php endif ?>

        <!-- INI BAGIAN HYBRID NYA -->
        <?php
        if (isset($content)) {
            echo $content;
        } else {
            $this->renderSection('content');
        }
        ?>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        (function syncBodyClass() {
            const STORAGE_KEY = 'sb_collapsed';
            let saved = '0';
            try {
                saved = localStorage.getItem(STORAGE_KEY) ?? '0';
            } catch (_) {}
            if (saved === '1') document.body.classList.add('sidebar-collapsed');

            const sidebar = document.getElementById('app-sidebar');
            if (!sidebar) return;
            new MutationObserver(() => {
                document.body.classList.toggle('sidebar-collapsed', sidebar.classList.contains('collapsed'));
            }).observe(sidebar, {
                attributes: true,
                attributeFilter: ['class']
            });
        })();
    </script>

    <script src="<?= base_url('ui/js/sidebar.js') ?>"></script>

    <?= $this->renderSection('scripts') ?>

    <!-- ── Global Toast — tersedia di semua view ── -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index:9999;">
        <div id="toastNotif" class="toast align-items-center border-0"
            role="alert" style="border-radius:12px; min-width:280px; box-shadow:0 8px 32px rgba(0,0,0,.18);">
            <div class="d-flex">
                <div class="toast-body fw-semibold" id="toastMessage" style="font-size:13.5px;"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <script>
        // showToast — dual signature:
        // 1) showToast('pesan', 'success')                         
        // 2) showToast({ title, message, type, icon })
        window.showToast = function(msgOrObj, type = 'success') {
            const isObj = typeof msgOrObj === 'object' && msgOrObj !== null;
            const message = isObj ? (msgOrObj.message ?? '') : msgOrObj;
            const title = isObj ? (msgOrObj.title ?? null) : null;
            const rawType = isObj ? (msgOrObj.type ?? 'success') : type;
            // Bootstrap tidak kenal 'error' — map ke 'danger'
            const bsType = rawType === 'error' ? 'danger' : rawType;

            const el = document.getElementById('toastNotif');
            const msg = document.getElementById('toastMessage');
            el.className = `toast align-items-center border-0 text-white bg-${bsType}`;
            const esc = s => s == null ? '' : String(s)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
            msg.innerHTML = title ?
                `<div style="font-size:11.5px;opacity:.8;margin-bottom:2px;">${esc(title)}</div><div>${esc(message)}</div>` :
                esc(message);
            new bootstrap.Toast(el, {
                delay: 3500
            }).show();
        };
    </script>
</body>

</html>