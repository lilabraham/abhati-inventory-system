<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
  .support-card {
    background: #fff;
    border: 1px solid #f0f0f0;
    border-radius: 16px;
    padding: 28px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
  }

  .support-card .card-eyebrow {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #9ca3af;
    margin-bottom: 6px;
  }

  .support-card .card-title {
    font-size: 16px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 20px;
  }

  /* Contact list */
  .contact-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #f3f4f6;
  }

  .contact-row:last-child { border-bottom: none; }

  .contact-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    color: #374151;
    flex-shrink: 0;
  }

  .contact-label {
    font-size: 11px;
    color: #9ca3af;
    line-height: 1;
    margin-bottom: 3px;
  }

  .contact-value {
    font-size: 13.5px;
    font-weight: 600;
    color: #111827;
  }

  /* Guide list */
  .guide-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 11px 0;
    border-bottom: 1px solid #f3f4f6;
  }

  .guide-row:last-child { border-bottom: none; }

  .guide-name {
    font-size: 13px;
    font-weight: 500;
    color: #374151;
  }

  .guide-meta {
    font-size: 11px;
    color: #9ca3af;
    margin-top: 2px;
  }

  .btn-ghost {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 14px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    background: transparent;
    color: #374151;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: background 0.15s, border-color 0.15s;
    white-space: nowrap;
  }

  .btn-ghost:hover {
    background: #f3f4f6;
    border-color: #d1d5db;
    color: #111827;
  }

  /* Form */
  .form-control-soft {
    background: #f9fafb;
    border: 1px solid transparent;
    border-radius: 10px;
    padding: 11px 14px;
    font-size: 13.5px;
    color: #111827;
    transition: border-color 0.18s, box-shadow 0.18s, background 0.18s;
    width: 100%;
  }

  .form-control-soft::placeholder { color: #9ca3af; }

  .form-control-soft:focus {
    outline: none;
    background: #fff;
    border-color: #d1d5db;
    box-shadow: 0 0 0 3px rgba(17,24,39,0.06);
  }

  select.form-control-soft { appearance: none; cursor: pointer; }

  .form-label-soft {
    font-size: 12px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 6px;
    display: block;
  }

  .btn-submit-dark {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 28px;
    border-radius: 10px;
    background: #111827;
    color: #fff;
    font-size: 13.5px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: background 0.2s, transform 0.15s;
  }

  .btn-submit-dark:hover { background: #1f2937; transform: translateY(-1px); }
  .btn-submit-dark:active { transform: translateY(0); }

  .page-eyebrow {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #9ca3af;
    margin-bottom: 6px;
  }

  .page-title {
    font-size: 22px;
    font-weight: 800;
    color: #111827;
    margin-bottom: 4px;
  }

  .page-subtitle {
    font-size: 13.5px;
    color: #6b7280;
  }
</style>

<div class="container-fluid px-4 py-4">

  <!-- Page Header -->
  <div class="mb-4">
    <p class="page-eyebrow mb-1">Bantuan</p>
    <h1 class="page-title">IT Support</h1>
    <p class="page-subtitle">Hubungi tim IT atau kirim tiket bantuan untuk kendala teknis.</p>
  </div>

  <div class="row g-4 align-items-start">

    <!-- ── Kolom Kiri ── -->
    <div class="col-12 col-lg-4 d-flex flex-column gap-4">

      <!-- Kartu Kontak -->
      <div class="support-card">
        <p class="card-eyebrow">Kontak</p>
        <p class="card-title">Hubungi Tim IT</p>

        <div class="contact-row">
          <div class="contact-icon"><i class="bi bi-envelope-fill"></i></div>
          <div>
            <div class="contact-label">Email</div>
            <div class="contact-value">it.dept@abhatigroup.com</div>
          </div>
        </div>

        <div class="contact-row">
          <div class="contact-icon"><i class="bi bi-telephone-fill"></i></div>
          <div>
            <div class="contact-label">Ext. Telepon</div>
            <div class="contact-value">02121697052</div>
          </div>
        </div>

        <div class="contact-row">
          <div class="contact-icon bi-whatsapp" style="background:#f0fdf4;color:#16a34a;font-size:15px;width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-whatsapp"></i>
          </div>
          <div>
            <div class="contact-label">WhatsApp</div>
            <div class="contact-value">+62 812-0000-1234</div>
          </div>
        </div>
      </div>

      <!-- Kartu Panduan -->
      <div class="support-card">
        <p class="card-eyebrow">Dokumentasi</p>
        <p class="card-title">Buku Panduan Sistem</p>

        <div class="guide-row">
          <div>
            <div class="guide-name">Panduan Pengguna</div>
            <div class="guide-meta">PDF · 2.4 MB</div>
          </div>
          <a href="#" class="btn-ghost">
            <i class="bi bi-download"></i> Unduh
          </a>
        </div>

        <div class="guide-row">
          <div>
            <div class="guide-name">FAQ Sistem Aset</div>
            <div class="guide-meta">PDF · 890 KB</div>
          </div>
          <a href="#" class="btn-ghost">
            <i class="bi bi-download"></i> Unduh
          </a>
        </div>

        <div class="guide-row">
          <div>
            <div class="guide-name">Kebijakan IT</div>
            <div class="guide-meta">PDF · 1.1 MB</div>
          </div>
          <a href="#" class="btn-ghost">
            <i class="bi bi-download"></i> Unduh
          </a>
        </div>
      </div>

    </div>

    <!-- ── Kolom Kanan: Form Tiket ── -->
    <div class="col-12 col-lg-8">
      <div class="support-card">
        <p class="card-eyebrow">Formulir</p>
        <p class="card-title">Kirim Tiket Bantuan</p>

        <div class="row g-3">

          <div class="col-12">
            <label class="form-label-soft">Subjek</label>
            <input type="text"
                   class="form-control-soft"
                   placeholder="Contoh: Tidak bisa login ke sistem">
          </div>

          <div class="col-12 col-md-6">
            <label class="form-label-soft">Kategori Masalah</label>
            <select class="form-control-soft">
              <option value="" disabled selected>Pilih kategori...</option>
              <option>Akses & Login</option>
              <option>Perangkat Keras</option>
              <option>Jaringan / Internet</option>
              <option>Aplikasi / Software</option>
              <option>Data & Laporan</option>
              <option>Lainnya</option>
            </select>
          </div>

          <div class="col-12 col-md-6">
            <label class="form-label-soft">Tingkat Urgensi</label>
            <select class="form-control-soft">
              <option value="" disabled selected>Pilih urgensi...</option>
              <option>🟢 Rendah</option>
              <option>🟡 Sedang</option>
              <option>🔴 Tinggi</option>
            </select>
          </div>

          <div class="col-12">
            <label class="form-label-soft">Deskripsi Masalah</label>
            <textarea class="form-control-soft"
                      rows="6"
                      placeholder="Jelaskan kendala yang Anda alami secara detail..."></textarea>
          </div>

          <div class="col-12 d-flex align-items-center justify-content-between pt-2">
            <p class="mb-0" style="font-size:12px;color:#9ca3af;">
              <i class="bi bi-info-circle me-1"></i>
              Tiket akan direspons dalam 1×24 jam kerja.
            </p>
            <button type="submit" class="btn-submit-dark">
              <i class="bi bi-send-fill"></i>
              Kirim Tiket
            </button>
          </div>

        </div>
      </div>
    </div>

  </div>
</div>

<?= $this->endSection() ?>