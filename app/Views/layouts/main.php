<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <title><?= esc($title ?? 'Inventaris Laptop — Abhati Group') ?></title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Pastikan file CSS ini benar-benar ada di folder public/ui/css/ -->
    <link rel="stylesheet" href="<?= base_url('ui/css/sidebar.css') ?>">

    <style>
        * { box-sizing: border-box; }

        body {
            background: #f0f2f7;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            margin: 0;
            min-height: 100vh;
            color: #1e293b;
            background-image:
                radial-gradient(ellipse at 20% 0%, rgba(148,163,184,0.12) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 100%, rgba(99,102,241,0.05) 0%, transparent 60%);
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

        .flash-wrapper { margin-bottom: 1.25rem; }
        .flash-wrapper .alert {
            border-radius: 14px;
            font-size: 13.5px;
            font-weight: 500;
            padding: 14px 18px;
            border: none;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06), 0 1px 3px rgba(0, 0, 0, 0.04);
        }
        .flash-wrapper .alert-danger { background: #fff1f2; color: #be123c; }
        .flash-wrapper .alert-danger .btn-close { filter: invert(20%) sepia(80%) saturate(500%) hue-rotate(320deg); }
        .flash-wrapper .alert-success { background: #f0fdf4; color: #15803d; }
        .flash-wrapper .alert-success .btn-close { filter: invert(30%) sepia(60%) saturate(400%) hue-rotate(100deg); }

        .card {
            border-radius: 16px;
            border: none;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06), 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb {
            background: rgba(100, 116, 139, 0.25);
            border-radius: 99px;
        }
        ::-webkit-scrollbar-thumb:hover { background: rgba(100, 116, 139, 0.45); }

        .btn { font-size: 13.5px; font-weight: 600; border-radius: 10px; }
        .btn-sm { font-size: 12px; border-radius: 8px; }
        .badge { font-weight: 600; letter-spacing: 0.02em; }
        code { background: #f1f5f9; color: #3b82f6; padding: 2px 7px; border-radius: 6px; font-size: 12px; }

        .modal-content { border-radius: 18px; border: none; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15); }
        .modal-header { border-bottom: 1px solid #f1f4f8; padding: 20px 24px 16px; }
        .modal-footer { border-top: 1px solid #f1f4f8; padding: 16px 24px 20px; }
        .modal-body { padding: 20px 24px; }
        .modal-title { font-size: 15px; font-weight: 700; color: #0f172a; }

        .form-control, .form-select {
            border-radius: 10px;
            border-color: #e2e8f0;
            font-size: 13.5px;
            padding: 9px 13px;
            color: #1e293b;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
        }
        .form-label { font-size: 12.5px; font-weight: 600; color: #475569; margin-bottom: 6px; }

        .table > thead > tr > th {
            font-size: 11.5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #64748b;
            border-bottom-width: 1px;
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
    window.apiFetch = async (url, options = {}) => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const defaultHeaders = {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken,
        };

        const res = await fetch(url, {
            ...options,
            credentials: 'same-origin',
            headers: { ...defaultHeaders, ...(options.headers ?? {}) },
        });

        if (res.status === 401 || res.status === 403 || res.redirected) {
            window.location.href = '<?= base_url('login') ?>';
            return null;
        }
        return res;
    };

    (function syncBodyClass() {
        const STORAGE_KEY = 'sb_collapsed';
        let saved = '0';
        try { saved = localStorage.getItem(STORAGE_KEY) ?? '0'; } catch (_) {}
        if (saved === '1') document.body.classList.add('sidebar-collapsed');

        const sidebar = document.getElementById('app-sidebar');
        if (!sidebar) return;
        new MutationObserver(() => {
            document.body.classList.toggle('sidebar-collapsed', sidebar.classList.contains('collapsed'));
        }).observe(sidebar, { attributes: true, attributeFilter: ['class'] });
    })();
</script>

<script src="<?= base_url('ui/js/sidebar.js') ?>"></script>

<?= $this->renderSection('scripts') ?>
</body>
</html>