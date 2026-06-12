<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
    /* ════════════════════════════════════════════════════
       DESIGN TOKENS — identik dengan halaman Aset Laptop
    ════════════════════════════════════════════════════ */
    :root {
        --clr-surface:   #ffffff;
        --clr-border:    #eaecf0;
        --clr-sep:       #f2f4f7;

        --clr-txt-900:   #0d1117;
        --clr-txt-600:   #4b5563;
        --clr-txt-400:   #9ca3af;

        --radius-card:   18px;
        --shadow-card:   0 0 0 1px rgba(0,0,0,.045),
                         0 2px 4px  rgba(0,0,0,.04),
                         0 10px 28px rgba(0,0,0,.07);
        --shadow-hover:  0 0 0 1px rgba(0,0,0,.05),
                         0 4px 8px  rgba(0,0,0,.06),
                         0 16px 36px rgba(0,0,0,.1);
        --transition:    all .18s cubic-bezier(.4,0,.2,1);
    }

    /* ════════════════════════════════════════════════════
       PAGE HEADER
    ════════════════════════════════════════════════════ */
    .report-page-title {
        font-size: 1.2rem;
        font-weight: 800;
        color: var(--clr-txt-900);
        letter-spacing: -0.45px;
        line-height: 1.2;
        margin: 0 0 4px;
    }
    .report-page-sub {
        font-size: 12.5px;
        color: var(--clr-txt-400);
        font-weight: 400;
        margin: 0;
    }

    /* ── Export button ── */
    .btn-export {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: var(--clr-txt-900);
        color: #fff;
        border: none;
        padding: 9px 18px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: -0.1px;
        cursor: pointer;
        transition: var(--transition);
        white-space: nowrap;
    }
    .btn-export:hover  { background: #1a2232; color: #fff; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(0,0,0,.2); }
    .btn-export:active { transform: translateY(0); box-shadow: none; }

    /* ════════════════════════════════════════════════════
       STAT CARDS
    ════════════════════════════════════════════════════ */
    .stat-card {
        background: var(--clr-surface) !important;
        border: none !important;
        border-radius: var(--radius-card) !important;
        box-shadow: var(--shadow-card) !important;
        transition: var(--transition);
    }
    .stat-card:hover {
        box-shadow: var(--shadow-hover) !important;
        transform: translateY(-2px);
    }
    .stat-card .card-body {
        padding: 22px !important;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    /* Circular icon — identik Aset Laptop */
    .icon-circle {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.25rem;
        background: var(--ic-bg);
    }
    .icon-circle i { color: var(--ic-clr); }

    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1;
        letter-spacing: -1.5px;
        color: var(--clr-txt-900);
        font-variant-numeric: tabular-nums;  /* ← angka rapi sejajar */
    }
    .stat-value-sm {
        font-size: 1.1rem;
        font-weight: 800;
        line-height: 1.15;
        letter-spacing: -0.5px;
        color: var(--clr-txt-900);
        font-variant-numeric: tabular-nums;
    }
    .stat-label {
        font-size: 11.5px;
        color: var(--clr-txt-400);
        font-weight: 500;
        margin-top: 5px;
        letter-spacing: 0.01em;
    }

    /* ════════════════════════════════════════════════════
       TABLE CARD SHELL
    ════════════════════════════════════════════════════ */
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
        background: #3b82f6;
        flex-shrink: 0;
    }
    .table-card-header small {
        font-size: 11.5px;
        color: var(--clr-txt-400);
    }

    /* ════════════════════════════════════════════════════
       TABLE — CLEAN MINIMAL
    ════════════════════════════════════════════════════ */
    #reportTable {
        width: 100%;
        border-collapse: collapse;
    }

    /* thead: white bg, muted all-caps labels */
    #reportTable thead th {
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

    /* tbody rows */
    #reportTable tbody tr {
        border-bottom: 1px solid var(--clr-sep);
        transition: background .12s;
    }
    #reportTable tbody tr:last-child { border-bottom: none; }
    #reportTable tbody tr:hover      { background: #fafbfc; }
    #reportTable tbody td {
        padding: 15px 14px;
        border: none !important;
        vertical-align: middle;
        font-size: 13.5px;
        color: var(--clr-txt-600);
    }

    /* Kode pill */
    .kode-pill {
        display: inline-block;
        background: #eff6ff;
        color: #2563eb;
        padding: 3px 9px;
        border-radius: 6px;
        font-size: 11.5px;
        font-family: ui-monospace, 'Cascadia Code', monospace;
        font-weight: 700;
        letter-spacing: 0.02em;
    }

    /* Merk/model stacked */
    .cell-merk  { font-weight: 600; color: var(--clr-txt-900); }
    .cell-model { font-size: 12.5px; color: var(--clr-txt-400); margin-top: 1px; }

    /* Perbaikan chip */
    .chip-perbaikan {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #f3f4f6;
        color: #6b7280;
        font-size: 12px;
        font-weight: 600;
        padding: 3px 9px;
        border-radius: 999px;
        font-variant-numeric: tabular-nums;
    }

    /* Harga beli: rata kanan */
    #reportTable thead th.th-harga { text-align: right; padding-right: 24px; }
    #reportTable tbody td.td-harga {
        text-align: right;
        padding-right: 24px;
        font-weight: 600;
        font-variant-numeric: tabular-nums;
        color: var(--clr-txt-900);
    }

    /* ════════════════════════════════════════════════════
       SOFT PILL BADGES — WCAG AA
    ════════════════════════════════════════════════════ */
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
    .badge-soft-success   { background: #dcfce7; color: #15803d; }
    .badge-soft-danger    { background: #fee2e2; color: #b91c1c; }
    .badge-soft-warning   { background: #fef3c7; color: #92400e; }   /* dark amber — WCAG AA */
    .badge-soft-secondary { background: #f3f4f6; color: #4b5563; }

    /* ════════════════════════════════════════════════════
       PAGINATION — identik Aset Laptop
    ════════════════════════════════════════════════════ */
    #tablePagination { border-top: 1px solid var(--clr-sep); }

    #tablePagination .page-link {
        border-radius: 8px;
        font-size: 12.5px;
        border-color: var(--clr-border);
        color: var(--clr-txt-600);
        padding: 5px 11px;
        transition: var(--transition);
    }
    #tablePagination .page-item.active .page-link {
        background: var(--clr-txt-900);
        border-color: var(--clr-txt-900);
        color: #fff;
        box-shadow: 0 2px 8px rgba(13,17,23,.25);
    }
    #tablePagination .page-link:hover:not(.active) {
        background: var(--clr-sep);
        color: var(--clr-txt-900);
        border-color: var(--clr-border);
    }
    #tablePagination .pagination { gap: 3px; }
</style>

<!-- ══════════════════════════════════════════════════════
     PAGE HEADER
══════════════════════════════════════════════════════ -->
<div class="d-flex justify-content-between align-items-start mb-4 gap-3">
    <div>
        <h1 class="report-page-title">Laporan Aset Laptop</h1>
        <p class="report-page-sub">Laporan keseluruhan inventaris laptop Abhati Group</p>
    </div>
    <button class="btn-export" onclick="generatePDF()" id="btnExport">
        <i class="bi bi-file-earmark-pdf"></i> Export PDF
    </button>
</div>

<!-- ══════════════════════════════════════════════════════
     STAT CARDS — diisi oleh renderStats() via JS
══════════════════════════════════════════════════════ -->
<div class="row g-3 mb-4" id="reportStats">
    <!-- Skeleton loader saat pertama load -->
    <div class="col-6 col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body justify-content-center">
                <div class="spinner-border text-primary" role="status" style="width:1.5rem;height:1.5rem;"></div>
            </div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════
     TABLE CARD
══════════════════════════════════════════════════════ -->
<div class="table-card">

    <div class="table-card-header">
        <div class="table-card-header-title">
            <span class="dot"></span>
            Detail Semua Aset
        </div>
        <small>Data real-time</small>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0" id="reportTable">
            <thead>
                <tr>
                    <th class="ps-4" style="width:48px;">No</th>
                    <th>Kode Aset</th>
                    <th>Merk / Model</th>
                    <th>Pengguna</th>
                    <th>Kondisi</th>
                    <th class="text-center" style="width:96px;">Perbaikan</th>
                    <th>Tanggal Beli</th>
                    <th class="th-harga">Harga Beli</th>
                </tr>
            </thead>
            <tbody id="reportTableBody">
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                        <span class="text-muted" style="font-size:13px;">Memuat data...</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div id="tablePagination"></div>

</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
<script>
    let reportData  = null;
    let allAssets   = [];
    let displayPage = 1;

    const BATCH        = 100;
    const MAX_PDF      = 500;
    const DISPLAY_SIZE = 20;

    const kondisiLabel = k => ({
        baik: 'Baik', rusak: 'Rusak',
        dalam_perbaikan: 'Dalam Perbaikan', tidak_aktif: 'Tidak Aktif',
    }[k] ?? k);

    // ── Badge pill — seragam dengan halaman Aset Laptop ─────────
    const kondisiBadge = k => ({
        baik:            '<span class="badge-soft badge-soft-success">Baik</span>',
        rusak:           '<span class="badge-soft badge-soft-danger">Rusak</span>',
        dalam_perbaikan: '<span class="badge-soft badge-soft-warning">Dalam Perbaikan</span>',
        tidak_aktif:     '<span class="badge-soft badge-soft-secondary">Tidak Aktif</span>',
    }[k] ?? `<span class="badge-soft badge-soft-secondary">${k}</span>`);

    const rupiah = v => v ? 'Rp\u00a0' + Number(v).toLocaleString('id-ID') : '—';
    const tgl    = v => v ? new Date(v).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) : '—';

    // ── Pagination helper — identik buildPageNumbers Aset Laptop ─
    function buildPageNumbers(current, total) {
        let start = Math.max(1, current - 2);
        let end   = Math.min(total, start + 4);
        if (end - start < 4) start = Math.max(1, end - 4);

        let pages = [];
        for (let i = start; i <= end; i++) {
            pages.push(`
                <li class="page-item ${i === current ? 'active' : ''}">
                    <button class="page-link" onclick="changePage(${i})">${i}</button>
                </li>`);
        }
        return pages.join('');
    }

    // ── Render table rows + pagination ───────────────────────────
    function renderTable() {
        const start      = (displayPage - 1) * DISPLAY_SIZE;
        const slice      = allAssets.slice(start, start + DISPLAY_SIZE);
        const total      = allAssets.length;
        const totalPages = Math.ceil(total / DISPLAY_SIZE);

        // ── Rows ────────────────────────────────────────────────
        document.getElementById('reportTableBody').innerHTML = slice.length
            ? slice.map((a, i) => `
                <tr>
                    <td class="ps-4 text-muted" style="font-size:12px;font-variant-numeric:tabular-nums;">${start + i + 1}</td>
                    <td><span class="kode-pill">${a.kode_aset}</span></td>
                    <td>
                        <div class="cell-merk">${a.merk}</div>
                        <div class="cell-model">${a.model}</div>
                    </td>
                    <td>${a.pengguna ?? '<span class="text-muted">—</span>'}</td>
                    <td>${kondisiBadge(a.kondisi)}</td>
                    <td class="text-center">
                        <span class="chip-perbaikan">
                            <i class="bi bi-wrench" style="font-size:10px;"></i>${a.total_perbaikan ?? 0}×
                        </span>
                    </td>
                    <td style="font-size:13px;">${tgl(a.tanggal_beli)}</td>
                    <td class="td-harga">${rupiah(a.harga_beli)}</td>
                </tr>`).join('')
            : '<tr><td colspan="8" class="text-center text-muted py-5" style="font-size:13px;"><i class="bi bi-inbox me-2 opacity-50"></i>Tidak ada data aset</td></tr>';

        // ── Pagination — Bootstrap numbered, identik Aset Laptop ─
        if (totalPages <= 1) {
            document.getElementById('tablePagination').innerHTML = '';
            return;
        }

        const maxBadge = reportData && reportData.total_aset > MAX_PDF
            ? `<span class="badge ms-2" style="background:#fef3c7;color:#92400e;font-size:10px;font-weight:600;border-radius:20px;padding:2px 8px;">Maks ${MAX_PDF}</span>`
            : '';

        document.getElementById('tablePagination').innerHTML = `
            <div class="d-flex justify-content-between align-items-center px-4 py-3">
                <small class="text-muted" style="font-size:12px;">
                    Menampilkan
                    <strong>${start + 1}–${Math.min(start + DISPLAY_SIZE, total)}</strong>
                    dari <strong>${total}</strong> aset${maxBadge}
                </small>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item ${displayPage === 1 ? 'disabled' : ''}">
                        <button class="page-link" onclick="changePage(${displayPage - 1})">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                    </li>
                    ${buildPageNumbers(displayPage, totalPages)}
                    <li class="page-item ${displayPage >= totalPages ? 'disabled' : ''}">
                        <button class="page-link" onclick="changePage(${displayPage + 1})">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </li>
                </ul>
            </div>`;
    }

    function changePage(p) {
        displayPage = p;
        renderTable();
    }

    function setTableLoading(msg) {
        document.getElementById('reportTableBody').innerHTML = `
            <tr><td colspan="8" class="text-center py-5">
                <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                <span class="text-muted" style="font-size:13px;">${msg}</span>
            </td></tr>`;
        document.getElementById('tablePagination').innerHTML = '';
    }

    // ── Render stats cards — circular icon, tabular-nums ─────────
    function renderStats() {
        const stats    = reportData.kondisi_stats;
        const getTotal = k => stats.find(s => s.kondisi === k)?.total ?? 0;

        document.getElementById('reportStats').innerHTML = `
            <div class="col-6 col-md-3">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <div class="icon-circle" style="--ic-bg:rgba(59,130,246,.1); --ic-clr:#3b82f6;">
                            <i class="bi bi-laptop"></i>
                        </div>
                        <div>
                            <div class="stat-value">${reportData.total_aset}</div>
                            <div class="stat-label">Total Aset</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <div class="icon-circle" style="--ic-bg:rgba(34,197,94,.1); --ic-clr:#22c55e;">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div>
                            <div class="stat-value">${getTotal('baik')}</div>
                            <div class="stat-label">Kondisi Baik</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <div class="icon-circle" style="--ic-bg:rgba(239,68,68,.1); --ic-clr:#ef4444;">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <div>
                            <div class="stat-value">${getTotal('rusak')}</div>
                            <div class="stat-label">Rusak</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <div class="icon-circle" style="--ic-bg:rgba(245,158,11,.1); --ic-clr:#f59e0b;">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <div>
                            <div class="stat-value-sm">${rupiah(reportData.total_biaya_perbaikan)}</div>
                            <div class="stat-label">Total Biaya Perbaikan</div>
                        </div>
                    </div>
                </div>
            </div>`;
    }

    // ════════════════════════════════════════════════════
    // ZONA TERLARANG — JANGAN UBAH FUNGSI DI BAWAH INI
    // ════════════════════════════════════════════════════

    async function loadReport() {
        try {
            const resStats = await apiFetch('<?= base_url('api/report/summary') ?>');
            if (!resStats?.ok) throw new Error('Gagal memuat ringkasan statistik.');
            reportData = (await resStats.json()).data;
            renderStats();

            setTableLoading('Memuat data aset...');
            allAssets   = [];
            displayPage = 1;

            let page = 1, totalPages = 1;

            do {
                const res = await apiFetch(`<?= base_url('api/report/assets') ?>?limit=${BATCH}&page=${page}`);
                if (!res?.ok) throw new Error('Gagal memuat data aset.');

                const json = await res.json();
                allAssets.push(...(json.data ?? []));
                totalPages = json.pager?.total_pages ?? 1;

                if (allAssets.length >= MAX_PDF) {
                    allAssets = allAssets.slice(0, MAX_PDF);
                    break;
                }

                setTableLoading(`Memuat data aset... (${allAssets.length} dimuat)`);
                page++;
            } while (page <= totalPages);

            renderTable();

        } catch (err) {
            console.error(err);
            document.getElementById('reportStats').innerHTML = `
                <div class="col-12">
                    <div class="alert alert-danger border-0 rounded-4 mb-0" style="box-shadow:var(--shadow-card);">
                        <i class="bi bi-exclamation-octagon me-2"></i>${err.message}
                    </div>
                </div>`;
            document.getElementById('reportTableBody').innerHTML = `
                <tr><td colspan="8" class="text-center text-danger py-5" style="font-size:13px;">
                    <i class="bi bi-wifi-off me-2"></i>Gagal memuat data laporan.
                </td></tr>`;
        }
    }

    function generatePDF() {
        if (!reportData || !allAssets.length) return alert('Data belum dimuat, tunggu sebentar.');

        const { jsPDF } = window.jspdf;
        const doc       = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });
        const pageW     = doc.internal.pageSize.getWidth();
        const pageH     = doc.internal.pageSize.getHeight();

        doc.setFillColor(26, 26, 46);
        doc.rect(0, 0, pageW, 35, 'F');
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(18).setFont('helvetica', 'bold');
        doc.text('LAPORAN INVENTARIS ASET LAPTOP', pageW / 2, 14, { align: 'center' });
        doc.setFontSize(10).setFont('helvetica', 'normal');
        doc.text('Abhati Group — Departemen IT', pageW / 2, 22, { align: 'center' });
        doc.setFontSize(8);
        doc.text(`Digenerate: ${new Date().toLocaleString('id-ID')}`, pageW / 2, 29, { align: 'center' });

        const stats    = reportData.kondisi_stats;
        const getTotal = k => stats.find(s => s.kondisi === k)?.total ?? 0;
        const boxes = [
            { label: 'Total Aset',           value: String(reportData.total_aset),            color: [59, 130, 246] },
            { label: 'Kondisi Baik',          value: String(getTotal('baik')),                 color: [34, 197, 94]  },
            { label: 'Rusak',                 value: String(getTotal('rusak')),                color: [239, 68, 68]  },
            { label: 'Total Biaya Perbaikan', value: rupiah(reportData.total_biaya_perbaikan), color: [234, 179, 8]  },
        ];

        boxes.forEach(({ label, value, color }, i) => {
            const x = 10 + i * 69;
            doc.setFillColor(...color);
            doc.roundedRect(x, 38, 66, 18, 3, 3, 'F');
            doc.setTextColor(255, 255, 255);
            doc.setFontSize(7).setFont('helvetica', 'normal');
            doc.text(label, x + 33, 44, { align: 'center' });
            doc.setFontSize(11).setFont('helvetica', 'bold');
            doc.text(value, x + 33, 52, { align: 'center' });
        });

        let startY = 60;
        if (reportData.total_aset > MAX_PDF) {
            doc.setTextColor(180, 100, 0);
            doc.setFontSize(7).setFont('helvetica', 'italic');
            doc.text(
                `* PDF menampilkan ${MAX_PDF} dari ${reportData.total_aset} aset. Gunakan ekspor server untuk data lengkap.`,
                pageW / 2, 58, { align: 'center' }
            );
            startY = 63;
        }

        doc.setTextColor(0, 0, 0);
        doc.autoTable({
            startY,
            head: [['No', 'Kode Aset', 'Merk / Model', 'Pengguna', 'Kondisi', 'Perbaikan', 'Tgl Beli', 'Harga Beli']],
            body: allAssets.map((a, i) => [
                i + 1, a.kode_aset, `${a.merk} ${a.model}`,
                a.pengguna ?? '-', kondisiLabel(a.kondisi),
                `${a.total_perbaikan ?? 0}x`, tgl(a.tanggal_beli), rupiah(a.harga_beli),
            ]),
            styles: { fontSize: 8, cellPadding: 2.5, overflow: 'linebreak' },
            headStyles: { fillColor: [26, 26, 46], textColor: 255, fontStyle: 'bold' },
            alternateRowStyles: { fillColor: [245, 246, 250] },
            columnStyles: {
                0: { cellWidth: 10, halign: 'center' },
                5: { cellWidth: 18, halign: 'center' },
            },
        });

        const pageCount = doc.internal.getNumberOfPages();
        for (let i = 1; i <= pageCount; i++) {
            doc.setPage(i);
            doc.setFontSize(7).setTextColor(150);
            doc.text(
                `Halaman ${i} dari ${pageCount}  •  Abhati Group — Sistem Inventaris Laptop`,
                pageW / 2, pageH - 4, { align: 'center' }
            );
        }

        doc.save(`Laporan_Aset_Laptop_Abhati_${new Date().toISOString().slice(0, 10)}.pdf`);
    }

    loadReport();
</script>
<?= $this->endSection() ?>