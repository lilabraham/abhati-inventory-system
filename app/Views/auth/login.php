<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — Abhati Group</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --clr-txt-900: #0d1117;
            --clr-txt-600: #4b5563;
            --clr-txt-400: #9ca3af;
            --clr-border: #e2e8f0;
            --clr-sep: #f2f4f7;
            --transition: all .18s cubic-bezier(.4, 0, .2, 1);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: #f0f2f7;
            background-image:
                radial-gradient(ellipse at 20% 0%, rgba(148, 163, 184, .12) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 100%, rgba(99, 102, 241, .06) 0%, transparent 60%);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            color: var(--clr-txt-900);
        }

        .login-card {
            background: #fff;
            border-radius: 20px;
            box-shadow:
                0 0 0 1px rgba(0, 0, 0, .045),
                0 2px 4px rgba(0, 0, 0, .04),
                0 10px 28px rgba(0, 0, 0, .07);
            width: 100%;
            max-width: 400px;
            padding: 36px 32px 32px;
        }

        .login-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 28px;
        }

        .login-brand-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--clr-txt-900);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 17px;
            flex-shrink: 0;
        }

        .login-brand-name {
            font-size: 15px;
            font-weight: 800;
            color: var(--clr-txt-900);
            letter-spacing: -0.3px;
            line-height: 1.1;
        }

        .login-brand-sub {
            font-size: 11.5px;
            color: var(--clr-txt-400);
            font-weight: 400;
        }

        .login-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--clr-txt-900);
            letter-spacing: -0.4px;
            margin: 0 0 4px;
        }

        .login-sub {
            font-size: 12.5px;
            color: var(--clr-txt-400);
            margin: 0 0 24px;
        }

        .form-label {
            font-size: 12.5px;
            font-weight: 600;
            color: var(--clr-txt-600);
            margin-bottom: 6px;
        }

        .form-control {
            border-radius: 10px;
            border: 1.5px solid var(--clr-border);
            font-size: 13.5px;
            padding: 9px 13px;
            color: var(--clr-txt-900);
            transition: var(--transition);
        }

        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .12);
            outline: none;
        }

        .password-wrap {
            position: relative;
        }

        .password-wrap .form-control {
            padding-right: 42px;
        }

        .btn-toggle-pw {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            padding: 0;
            color: var(--clr-txt-400);
            font-size: 15px;
            cursor: pointer;
            line-height: 1;
            transition: var(--transition);
        }

        .btn-toggle-pw:hover {
            color: var(--clr-txt-600);
        }

        .btn-login {
            width: 100%;
            padding: 10px;
            border-radius: 10px;
            border: none;
            background: var(--clr-txt-900);
            color: #fff;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            letter-spacing: -0.1px;
            margin-top: 4px;
        }

        .btn-login:hover {
            background: #1a2232;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, .18);
        }

        .btn-login:active {
            transform: translateY(0);
            box-shadow: none;
        }

        .btn-login:disabled {
            opacity: .7;
            cursor: not-allowed;
            transform: none;
        }

        .alert {
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            padding: 11px 14px;
            border: none;
            margin-bottom: 20px;
        }

        .alert-danger {
            background: #fff1f2;
            color: #be123c;
        }

        .alert-success {
            background: #f0fdf4;
            color: #15803d;
        }
    </style>
</head>

<body>

    <div class="login-card">

        <!-- Brand -->
        <div class="login-brand">
            <div class="login-brand-icon">
                <i class="bi bi-pc-display"></i>
            </div>
            <div>
                <div class="login-brand-name">Abhati Group</div>
                <div class="login-brand-sub">Inventaris Laptop</div>
            </div>
        </div>

        <h1 class="login-title">Selamat datang</h1>
        <p class="login-sub">Masuk untuk melanjutkan ke sistem</p>

        <!-- Alerts -->
        <?php if (session('error') !== null): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-octagon me-2"></i><?= esc(session('error')) ?>
            </div>
        <?php elseif (session('errors') !== null): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-octagon me-2"></i>
                <?php if (is_array(session('errors'))): ?>
                    <?= esc(implode('<br>', session('errors'))) ?>
                <?php else: ?>
                    <?= esc(session('errors')) ?>
                <?php endif ?>
            </div>
        <?php endif ?>

        <?php if (session('message') !== null): ?>
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-2"></i><?= esc(session('message')) ?>
            </div>
        <?php endif ?>

        <!-- Form -->
        <form action="<?= url_to('login') ?>" method="post" novalidate>
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label" for="inputUsername">Username</label>
                <input
                    type="text"
                    id="inputUsername"
                    name="username"
                    class="form-control"
                    autocomplete="username"
                    placeholder="Masukkan username"
                    value="<?= esc(old('username')) ?>"
                    required>
            </div>

            <div class="mb-4">
                <label class="form-label" for="inputPassword">Password</label>
                <div class="password-wrap">
                    <input
                        type="password"
                        id="inputPassword"
                        name="password"
                        class="form-control"
                        autocomplete="current-password"
                        placeholder="Masukkan password"
                        required>
                    <button type="button" class="btn-toggle-pw" id="btnTogglePw" aria-label="Tampilkan password">
                        <i class="bi bi-eye" id="togglePwIcon"></i>
                    </button>
                </div>
            </div>

            <?php if (setting('Auth.sessionConfig')['allowRemembering']): ?>
                <div class="form-check mb-3" style="font-size:13px;">
                    <input type="checkbox" name="remember" id="rememberMe" class="form-check-input"
                        <?php if (old('remember')): ?>checked<?php endif ?>>
                    <label class="form-check-label" for="rememberMe" style="color:var(--clr-txt-600);">
                        <?= lang('Auth.rememberMe') ?>
                    </label>
                </div>
            <?php endif ?>

            <button type="submit" class="btn-login" id="btnLogin">
                Masuk
            </button>
        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        const pwInput = document.getElementById('inputPassword');
        const pwIcon = document.getElementById('togglePwIcon');
        document.getElementById('btnTogglePw').addEventListener('click', () => {
            const show = pwInput.type === 'password';
            pwInput.type = show ? 'text' : 'password';
            pwIcon.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
        });

        // Disable submit on click to prevent double-submit
        document.getElementById('btnLogin').addEventListener('click', function() {
            const form = this.closest('form');
            if (form.checkValidity()) {
                this.disabled = true;
                this.textContent = 'Memproses...';
                form.submit();
            }
        });
    </script>

</body>

</html>