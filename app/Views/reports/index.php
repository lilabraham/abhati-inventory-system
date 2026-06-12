<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
    /* ── Soft shadow utility ── */
    .shadow-soft {
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06), 0 1px 3px rgba(0, 0, 0, 0.04) !important;
    }

    /* ── Stats cards ── */
    .stat-card .icon-wrap {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.35rem;
    }

    .stat-card .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        line-height: 1.1;
        letter-spacing: -0.5px;
    }

    .stat-card .stat-label {
        font-size: 0.75rem;
        color: #94a3b8;
        font-weight: 500;
        margin-top: 2px;
    }

    /* ── Table modern ── */
    #reportTable thead th {
        background: #f8fafc;
        color: #64748b;
        font-size: 11.5px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        border-bottom: 1px solid #e9edf2;
        border-top: none;
        padding: 13px 14px;
        white-space: nowrap;
    }

    #reportTable tbody tr {
        border-bottom: 1px solid #f1f4f8;
        transition: background 0.15s;
    }

    #reportTable tbody tr:last-child {
        border-bottom: none;
    }

    #reportTable tbody tr:hover {
        background: #f8fafc;
    }

    #reportTable tbody td {
        padding: 12px 14px;
        vertical-align: middle;
        border: none;
        font-size: 13.5px;
        color: #374151;
    }

    /* ── Kondisi badge ── */
    .badge-kondisi {
        font-size: 11px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
        letter-spacing: 0.02em;
    }

    /* ── Pagination ── */
    #tablePagination .btn {
        font-size: 12px;
        padding: 5px 12px;
        border-radius: 8px;
    }

    /* ── Export button ── */
    .btn-export {
        background: #1e293b;
        color: #fff;
        border: none;
        padding: 9px 20px;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 7px;
        transition: background 0.2s, transform 0.15s;
    }

    .btn-export:hover {
        background: #0f172a;
        color: #fff;
        transform: translateY(-1px);
    }

    .btn-export:active {
        transform: translateY(0);
    }

    /* ── Page header ── */
    .report-page-title {
        font-size: 1.35rem;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.3px;
        margin-bottom: 2px;
    }

    .report-page-sub {
        font-size: 13px;
        color: #94a3b8;
    }

    /* ── Table card wrapper ── */
    .table-card {
        background: #fff;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06), 0 1px 3px rgba(0,0,0,0.04);
    }

    .table-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f1f4f8;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        background: #fff;
    }
</style>

<!-- ── Page Header ── -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="report-page-title">Laporan Aset Laptop</div>
        <div class="report-page-sub">Laporan keseluruhan inventaris laptop Abhati Group</div>
    </div>
    <button class="btn-export" onclick="generatePDF()" id="btnExport">
        <i class="bi bi-file-earmark-pdf"></i> Export PDF
    </button>
</div>

<!-- ── Stats Cards ── -->
<div class="row g-3 mb-4" id="reportStats">
    <div class="col-md-3">
        <div class="card border-0 rounded-4 shadow-soft p-1">
            <div class="card-body text-center py-4">
                <div class="spinner-border text-primary" role="status" style="width:1.5rem;height:1.5rem;"></div>
            </div>
        </div>
    </div>
</div>

<!-- ── Table Card ── -->
<div class="table-card">
    <div class="table-card-header">
        <i class="bi bi-table text-primary" style="font-size:15px;"></i>
        Detail Semua Aset
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-sm align-middle mb-0" id="reportTable">
            <thead>
                <tr>
                    <th class="ps-3" style="width:48px;">No</th>
                    <th>Kode Aset</th>
                    <th>Merk / Model</th>
                    <th>Pengguna</th>
                    <th>Kondisi</th>
                    <th class="text-center" style="width:90px;">Perbaikan</th>
                    <th>Tanggal Beli</th>
                    <th>Harga Beli</th>
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

    const kondisiBadge = k => ({
        baik:            '<span class="badge-kondisi" style="background:#dcfce7;color:#16a34a;">Baik</span>',
        rusak:           '<span class="badge-kondisi" style="background:#fee2e2;color:#dc2626;">Rusak</span>',
        dalam_perbaikan: '<span class="badge-kondisi" style="background:#fef9c3;color:#ca8a04;">Dalam Perbaikan</span>',
        tidak_aktif:     '<span class="badge-kondisi" style="background:#f1f5f9;color:#64748b;">Tidak Aktif</span>',
    }[k] ?? `<span class="badge-kondisi bg-secondary text-white">${k}</span>`);

    const rupiah = v => v ? 'Rp ' + Number(v).toLocaleString('id-ID') : '-';
    const tgl    = v => v ? new Date(v).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) : '-';

    function renderTable() {
        const start      = (displayPage - 1) * DISPLAY_SIZE;
        const slice      = allAssets.slice(start, start + DISPLAY_SIZE);
        const total      = allAssets.length;
        const totalPages = Math.ceil(total / DISPLAY_SIZE);

        document.getElementById('reportTableBody').innerHTML = slice.length
            ? slice.map((a, i) => `
                <tr>
                    <td class="ps-3 text-muted" style="font-size:12px;">${start + i + 1}</td>
                    <td><code style="background:#f1f5f9;color:#3b82f6;padding:3px 8px;border-radius:6px;font-size:12px;">${a.kode_aset}</code></td>
                    <td><span style="font-weight:600;color:#1e293b;">${a.merk}</span> <span class="text-muted">${a.model}</span></td>
                    <td>${a.pengguna ?? '<span class="text-muted">-</span>'}</td>
                    <td>${kondisiBadge(a.kondisi)}</td>
                    <td class="text-center">
                        <span style="font-size:12px;font-weight:600;color:#64748b;">${a.total_perbaikan ?? 0}x</span>
                    </td>
                    <td style="font-size:13px;">${tgl(a.tanggal_beli)}</td>
                    <td style="font-size:13px;font-weight:500;">${rupiah(a.harga_beli)}</td>
                </tr>`).join('')
            : '<tr><td colspan="8" class="text-center text-muted py-5" style="font-size:13px;">Tidak ada data aset</td></tr>';

        document.getElementById('tablePagination').innerHTML = totalPages <= 1 ? '' : `
            <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-top:1px solid #f1f4f8;">
                <small class="text-muted">
                    Menampilkan <strong>${start + 1}–${Math.min(start + DISPLAY_SIZE, total)}</strong> dari <strong>${total}</strong> aset
                    ${reportData && reportData.total_aset > MAX_PDF
                        ? `<span class="badge bg-warning text-dark ms-2" style="font-size:10px;">Maks ${MAX_PDF}</span>`
                        : ''}
                </small>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-secondary" ${displayPage === 1 ? 'disabled' : ''}
                        onclick="changePage(${displayPage - 1})">‹ Prev</button>
                    <span class="btn btn-sm btn-primary disabled" style="min-width:70px;">${displayPage} / ${totalPages}</span>
                    <button class="btn btn-sm btn-outline-secondary" ${displayPage >= totalPages ? 'disabled' : ''}
                        onclick="changePage(${displayPage + 1})">Next ›</button>
                </div>
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

    function renderStats() {
        const stats    = reportData.kondisi_stats;
        const getTotal = k => stats.find(s => s.kondisi === k)?.total ?? 0;

        document.getElementById('reportStats').innerHTML = `
            <div class="col-md-3">
                <div class="card stat-card border-0 rounded-4 shadow-soft h-100">
                    <div class="card-body d-flex align-items-center gap-3 p-4">
                        <div class="icon-wrap" style="background:rgba(59,130,246,0.1);">
                            <i class="bi bi-laptop" style="color:#3b82f6;"></i>
                        </div>
                        <div>
                            <div class="stat-value">${reportData.total_aset}</div>
                            <div class="stat-label">Total Aset</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card border-0 rounded-4 shadow-soft h-100">
                    <div class="card-body d-flex align-items-center gap-3 p-4">
                        <div class="icon-wrap" style="background:rgba(34,197,94,0.1);">
                            <i class="bi bi-check-circle" style="color:#22c55e;"></i>
                        </div>
                        <div>
                            <div class="stat-value">${getTotal('baik')}</div>
                            <div class="stat-label">Kondisi Baik</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card border-0 rounded-4 shadow-soft h-100">
                    <div class="card-body d-flex align-items-center gap-3 p-4">
                        <div class="icon-wrap" style="background:rgba(239,68,68,0.1);">
                            <i class="bi bi-exclamation-triangle" style="color:#ef4444;"></i>
                        </div>
                        <div>
                            <div class="stat-value">${getTotal('rusak')}</div>
                            <div class="stat-label">Rusak</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card border-0 rounded-4 shadow-soft h-100">
                    <div class="card-body d-flex align-items-center gap-3 p-4">
                        <div class="icon-wrap" style="background:rgba(234,179,8,0.1);">
                            <i class="bi bi-cash-stack" style="color:#eab308;"></i>
                        </div>
                        <div>
                            <div class="stat-value" style="font-size:1.1rem;">${rupiah(reportData.total_biaya_perbaikan)}</div>
                            <div class="stat-label">Total Biaya Perbaikan</div>
                        </div>
                    </div>
                </div>
            </div>`;
    }

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
                    <div class="alert alert-danger border-0 rounded-4 shadow-soft mb-0">
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