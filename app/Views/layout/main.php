<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <title><?= esc($title ?? 'Inventaris Laptop — Abhati Group') ?></title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background: #f5f6fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .sidebar {
            min-height: 100vh;
            background: #1a1a2e;
            position: sticky;
            top: 0;
            height: 100vh;
        }

        .sidebar-brand {
            color: #fff;
            font-weight: 700;
            font-size: 1.1rem;
            text-decoration: none;
        }

        .sidebar .nav-link {
            color: #8892b0;
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            transition: all .2s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar .nav-link.text-danger:hover {
            background: rgba(220, 53, 69, 0.15);
            color: #ff6b6b !important;
        }

        .main-content {
            min-height: 100vh;
        }
    </style>
</head>
<body>

<div class="container-fluid px-0">
    <div class="row g-0">

        <!-- Sidebar -->
        <nav class="col-md-2 sidebar d-flex flex-column p-3">
            <a href="/" class="sidebar-brand mb-4 d-flex align-items-center gap-2">
                <i class="bi bi-laptop fs-5"></i> Abhati IT
            </a>

            <div class="d-flex flex-column gap-1 flex-grow-1">
                <a href="/assets"
                   class="nav-link d-flex align-items-center gap-2 <?= in_array(uri_string(), ['assets', '']) ? 'active' : '' ?>">
                    <i class="bi bi-pc-display"></i> Aset Laptop
                </a>
                <a href="/assets/report"
                   class="nav-link d-flex align-items-center gap-2 <?= uri_string() === 'assets/report' ? 'active' : '' ?>">
                    <i class="bi bi-file-earmark-pdf"></i> Laporan PDF
                </a>
            </div>

            <hr class="border-secondary">

            <div class="mb-2 px-2">
                <small class="text-secondary">
                    <i class="bi bi-person-circle me-1"></i>
                    <?= esc(auth()->user()?->username ?? 'User') ?>
                </small>
            </div>

            <a href="/logout" class="nav-link text-danger d-flex align-items-center gap-2">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </nav>

        <!-- Main Content -->
        <main class="col-md-10 main-content p-4">

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= esc(session()->getFlashdata('error')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= esc(session()->getFlashdata('success')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif ?>

            <?= $this->renderSection('content') ?>
        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Global fetch wrapper — auto handle 401 redirect & CSRF
    window.apiFetch = async (url, options = {}) => {
        const defaultHeaders = {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        };

        const res = await fetch(url, {
            ...options,
            headers: { ...defaultHeaders, ...(options.headers ?? {}) },
        });

        if (res.status === 401 || res.redirected) {
            window.location.href = '/login';
            return null;
        }

        return res;
    };
</script>

<?= $this->renderSection('scripts') ?>
</body>
</html>