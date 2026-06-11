<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Laporan Aset Laptop</h4>
        <small class="text-muted">Laporan keseluruhan inventaris laptop Abhati Group</small>
    </div>
    <button class="btn btn-danger d-flex align-items-center gap-2" onclick="generatePDF()" id="btnExport">
        <i class="bi bi-file-earmark-pdf"></i> Export PDF
    </button>
</div>

<!-- Stats -->
<div class="row g-3 mb-4" id="reportStats">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-4">
                <div class="spinner-border text-primary" role="status"></div>
            </div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom fw-semibold">
        <i class="bi bi-table me-2 text-primary"></i>Detail Semua Aset
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm align-middle mb-0" id="reportTable">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-3">No</th>
                        <th>Kode Aset</th>
                        <th>Merk / Model</th>
                        <th>Pengguna</th>
                        <th>Kondisi</th>
                        <th class="text-center">Perbaikan</th>
                        <th>Tanggal Beli</th>
                        <th>Harga Beli</th>
                    </tr>
                </thead>
                <tbody id="reportTableBody">
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                            Memuat data...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
<script>
    let reportData = null;

    const kondisiLabel = k => ({
        baik: 'Baik', rusak: 'Rusak',
        dalam_perbaikan: 'Dalam Perbaikan', tidak_aktif: 'Tidak Aktif',
    }[k] ?? k);

    const rupiah = v => v ? 'Rp ' + Number(v).toLocaleString('id-ID') : '-';
    const tgl    = v => v ? new Date(v).toLocaleDateString('id-ID', { day:'2-digit', month:'short', year:'numeric' }) : '-';

    async function loadReport() {
        const res = await apiFetch('/api/report/summary');
        if (!res) return;

        const json = await res.json();
        reportData = json.data;

        const stats    = reportData.kondisi_stats;
        const getTotal = k => stats.find(s => s.kondisi === k)?.total ?? 0;

        document.getElementById('reportStats').innerHTML = `
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-primary bg-opacity-10 p-3">
                            <i class="bi bi-laptop fs-4 text-primary"></i>
                        </div>
                        <div>
                            <div class="fs-2 fw-bold">${reportData.total_aset}</div>
                            <div class="text-muted small">Total Aset</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-success bg-opacity-10 p-3">
                            <i class="bi bi-check-circle fs-4 text-success"></i>
                        </div>
                        <div>
                            <div class="fs-2 fw-bold">${getTotal('baik')}</div>
                            <div class="text-muted small">Kondisi Baik</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-danger bg-opacity-10 p-3">
                            <i class="bi bi-exclamation-triangle fs-4 text-danger"></i>
                        </div>
                        <div>
                            <div class="fs-2 fw-bold">${getTotal('rusak')}</div>
                            <div class="text-muted small">Rusak</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-warning bg-opacity-10 p-3">
                            <i class="bi bi-cash-stack fs-4 text-warning"></i>
                        </div>
                        <div>
                            <div class="fs-2 fw-bold" style="font-size:1.2rem!important">${rupiah(reportData.total_biaya_perbaikan)}</div>
                            <div class="text-muted small">Total Biaya Perbaikan</div>
                        </div>
                    </div>
                </div>
            </div>`;

        document.getElementById('reportTableBody').innerHTML = reportData.assets.length
            ? reportData.assets.map((a, i) => `
                <tr>
                    <td class="ps-3">${i + 1}</td>
                    <td><code class="text-primary">${a.kode_aset}</code></td>
                    <td><strong>${a.merk}</strong> ${a.model}</td>
                    <td>${a.pengguna ?? '-'}</td>
                    <td>${kondisiLabel(a.kondisi)}</td>
                    <td class="text-center">${a.total_perbaikan}x</td>
                    <td>${tgl(a.tanggal_beli)}</td>
                    <td>${rupiah(a.harga_beli)}</td>
                </tr>`).join('')
            : '<tr><td colspan="8" class="text-center text-muted py-4">Tidak ada data</td></tr>';
    }

    function generatePDF() {
        if (!reportData) return alert('Data belum dimuat, tunggu sebentar.');

        const { jsPDF } = window.jspdf;
        const doc       = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });
        const pageW     = doc.internal.pageSize.getWidth();
        const pageH     = doc.internal.pageSize.getHeight();

        // ── Header ──
        doc.setFillColor(26, 26, 46);
        doc.rect(0, 0, pageW, 35, 'F');

        doc.setTextColor(255, 255, 255);
        doc.setFontSize(18).setFont('helvetica', 'bold');
        doc.text('LAPORAN INVENTARIS ASET LAPTOP', pageW / 2, 14, { align: 'center' });

        doc.setFontSize(10).setFont('helvetica', 'normal');
        doc.text('Abhati Group — Departemen IT', pageW / 2, 22, { align: 'center' });

        doc.setFontSize(8);
        doc.text(`Digenerate: ${new Date().toLocaleString('id-ID')}`, pageW / 2, 29, { align: 'center' });

        // ── Summary Boxes ──
        doc.setTextColor(0, 0, 0);
        const stats    = reportData.kondisi_stats;
        const getTotal = k => stats.find(s => s.kondisi === k)?.total ?? 0;

        const boxes = [
            { label: 'Total Aset',         value: String(reportData.total_aset),                    color: [59, 130, 246] },
            { label: 'Kondisi Baik',        value: String(getTotal('baik')),                         color: [34, 197, 94] },
            { label: 'Rusak',               value: String(getTotal('rusak')),                        color: [239, 68, 68] },
            { label: 'Total Biaya Perbaikan', value: rupiah(reportData.total_biaya_perbaikan),       color: [234, 179, 8] },
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

        // ── Table ──
        doc.setTextColor(0, 0, 0);
        doc.autoTable({
            startY: 60,
            head: [['No', 'Kode Aset', 'Merk / Model', 'Pengguna', 'Kondisi', 'Perbaikan', 'Tgl Beli', 'Harga Beli']],
            body: reportData.assets.map((a, i) => [
                i + 1,
                a.kode_aset,
                `${a.merk} ${a.model}`,
                a.pengguna ?? '-',
                kondisiLabel(a.kondisi),
                `${a.total_perbaikan}x`,
                tgl(a.tanggal_beli),
                rupiah(a.harga_beli),
            ]),
            styles: {
                fontSize: 8,
                cellPadding: 2.5,
                overflow: 'linebreak',
            },
            headStyles: {
                fillColor: [26, 26, 46],
                textColor: 255,
                fontStyle: 'bold',
            },
            alternateRowStyles: { fillColor: [245, 246, 250] },
            columnStyles: {
                0: { cellWidth: 10, halign: 'center' },
                5: { cellWidth: 18, halign: 'center' },
            },
        });

        // ── Footer per page ──
        const pageCount = doc.internal.getNumberOfPages();
        for (let i = 1; i <= pageCount; i++) {
            doc.setPage(i);
            doc.setFontSize(7).setTextColor(150);
            doc.text(
                `Halaman ${i} dari ${pageCount}  •  Abhati Group — Sistem Inventaris Laptop`,
                pageW / 2,
                pageH - 4,
                { align: 'center' }
            );
        }

        doc.save(`Laporan_Aset_Laptop_Abhati_${new Date().toISOString().slice(0, 10)}.pdf`);
    }

    loadReport();
</script>
<?= $this->endSection() ?>