<?php
$current_uri = uri_string();
?>
<aside id="app-sidebar" role="navigation" aria-label="Main Sidebar">

  <!-- macOS Traffic Lights -->
  <div class="sb-traffic-lights" aria-hidden="true">
    <span class="sb-dot-red" title="Close"></span>
    <span class="sb-dot-yellow" title="Minimize"></span>
    <span class="sb-dot-green" title="Maximize"></span>
  </div>

  <!-- Profile Header -->
  <div class="sb-header">
    <div class="sb-avatar d-flex align-items-center justify-content-center"
      id="sb-toggle"
      role="button"
      aria-label="Toggle Sidebar">
      <i class="bi bi-person-fill"></i>
    </div>
    <div class="sb-profile-info">
      <div class="sb-role">Abhati Group</div>
      <div class="sb-name"><?= esc(auth()->user()?->username ?? 'User') ?></div>
    </div>
  </div>

  <!-- Nav Body -->
  <div class="sb-body" id="sb-body">

    <div class="sb-section-label">Main</div>

    <!-- Aset Laptop -->
    <!-- FIX #4: str_starts_with agar nested route data-aset/1 ikut aktif -->
    <div class="sb-nav-item">
      <a href="<?= base_url('data-aset') ?>"
        class="sb-nav-link <?= (str_starts_with($current_uri, 'data-aset') || $current_uri === '') ? 'active' : '' ?>">
        <span class="sb-nav-icon"><i class="bi bi-pc-display" aria-hidden="true"></i></span>
        <span class="sb-nav-text">Aset Laptop</span>
      </a>
      <span class="sb-tooltip">Aset Laptop</span>
    </div>

    <!-- FIX #1: Pusat Laporan hanya untuk reports.export -->
    <?php if (auth()->user()?->can('reports.export') ?? false) : ?>
      <div class="sb-nav-item">
        <a href="<?= base_url('laporan') ?>"
          class="sb-nav-link <?= $current_uri === 'laporan' ? 'active' : '' ?>">
          <span class="sb-nav-icon"><i class="bi bi-folder2-open" aria-hidden="true"></i></span>
          <span class="sb-nav-text">Pusat Laporan</span>
        </a>
        <span class="sb-tooltip">Pusat Laporan</span>
      </div>
    <?php endif ?>

    <?php if (auth()->user()?->can('users.manage') ?? false) : ?>
      <div class="sb-nav-item">
        <a href="<?= base_url('user-management') ?>"
          class="sb-nav-link <?= str_starts_with($current_uri, 'user-management') ? 'active' : '' ?>">
          <span class="sb-nav-icon"><i class="bi bi-people-fill" aria-hidden="true"></i></span>
          <span class="sb-nav-text">User Management</span>
        </a>
        <span class="sb-tooltip">User Management</span>
      </div>
    <?php endif ?>

    <!-- Bottom Nav -->
    <div class="sb-bottom-nav">

      <div class="sb-nav-item">
        <a href="<?= base_url('logout') ?>" class="sb-nav-link sb-nav-link--danger">
          <span class="sb-nav-icon"><i class="bi bi-box-arrow-right" aria-hidden="true"></i></span>
          <span class="sb-nav-text">Logout</span>
        </a>
        <span class="sb-tooltip sb-tooltip--danger">Logout</span>
      </div>

    </div>

  </div>

</aside>