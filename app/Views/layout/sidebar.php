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
      style="background:rgba(255,255,255,0.15);font-size:18px;">
      <i class="bi bi-person-fill" style="color:rgba(255,255,255,0.7)"></i>
    </div>
    <div class="sb-profile-info">
      <div class="sb-role">Abhati Group</div>
      <div class="sb-name"><?= esc(auth()->user()?->username ?? 'User') ?></div>
    </div>
    <button class="sb-toggle-btn" id="sb-toggle" aria-label="Toggle Sidebar" type="button">
      <i class="bi bi-chevron-left" aria-hidden="true"></i>
    </button>
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
    </div>

    <!-- Laporan PDF -->
    <div class="sb-nav-item">
      <a href="<?= base_url('data-aset/report') ?>" class="sb-nav-link <?= $current_uri === 'data-aset/report' ? 'active' : '' ?>">
        <span class="sb-nav-icon"><i class="bi bi-file-earmark-pdf" aria-hidden="true"></i></span>
        <span class="sb-nav-text">Laporan PDF</span>
      </a>
    </div>

    <div class="sb-divider"></div>

    <!-- Logout -->
    <div class="sb-nav-item" style="margin-top:auto;">
      <a href="<?= base_url('logout') ?>" class="sb-nav-link" style="color:rgba(255,100,100,0.85);">
        <span class="sb-nav-icon"><i class="bi bi-box-arrow-right" aria-hidden="true"></i></span>
        <span class="sb-nav-text">Logout</span>
      </a>
    </div>

  </div>

  <!-- Footer CTA -->
  <div class="sb-footer">
    <!-- Expanded -->
    <div class="sb-cta-card">
      <div class="sb-cta-title">Inventaris</div>
      <p class="sb-cta-desc">Kelola aset laptop kantor dengan mudah</p>
      <button class="sb-cta-btn" type="button"
        data-bs-toggle="modal" data-bs-target="#modalTambah"
        id="sb-add-task-btn">
        <i class="bi bi-plus-circle-fill" aria-hidden="true"></i>
        Tambah Aset
      </button>
    </div>
    <!-- Collapsed mini -->
    <div class="sb-cta-card sb-cta-mini" style="display:none;">
      <button class="sb-cta-btn-mini" type="button"
        data-bs-toggle="modal" data-bs-target="#modalTambah"
        aria-label="Tambah Aset"
        id="sb-add-task-btn-mini">
        <i class="bi bi-plus" aria-hidden="true"></i>
      </button>
    </div>
  </div>

</aside>