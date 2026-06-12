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
    <div class="sb-nav-item">
      <a href="<?= base_url('data-aset') ?>" class="sb-nav-link <?= in_array($current_uri, ['data-aset', '']) ? 'active' : '' ?>">
        <span class="sb-nav-icon"><i class="bi bi-pc-display" aria-hidden="true"></i></span>
        <span class="sb-nav-text">Aset Laptop</span>
      </a>
      <span class="sb-tooltip">Aset Laptop</span>
    </div>

    <!-- Laporan PDF -->
    <div class="sb-nav-item">
      <a href="<?= base_url('data-aset/report') ?>" class="sb-nav-link <?= $current_uri === 'data-aset/report' ? 'active' : '' ?>">
        <span class="sb-nav-icon"><i class="bi bi-file-earmark-pdf" aria-hidden="true"></i></span>
        <span class="sb-nav-text">Laporan PDF</span>
      </a>
      <span class="sb-tooltip">Laporan PDF</span>
    </div>
<!-- Bottom Nav -->
<div class="sb-bottom-nav">

  <div class="sb-nav-item">
    <a href="<?= base_url('it-support') ?>" class="sb-nav-link sb-nav-link--muted <?= $current_uri === 'it-support' ? 'active' : '' ?>">
      <span class="sb-nav-icon"><i class="bi bi-question-circle" aria-hidden="true"></i></span>
      <span class="sb-nav-text">IT Support</span>
    </a>
    <span class="sb-tooltip">IT Support</span>
  </div>

  <div class="sb-nav-item">
    <a href="<?= base_url('logout') ?>" class="sb-nav-link sb-nav-link--danger">
      <span class="sb-nav-icon"><i class="bi bi-box-arrow-right" aria-hidden="true"></i></span>
      <span class="sb-nav-text">Logout</span>
    </a>
    <span class="sb-tooltip sb-tooltip--danger">Logout</span>
  </div>

</div>
</aside>