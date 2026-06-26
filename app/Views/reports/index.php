<style>
    /* ── DESIGN TOKENS ── */
    :root {
        --clr-surface: #ffffff;
        --clr-bg: #f9fafb;
        --clr-border: #e5e7eb;
        --clr-sep: #f3f4f6;
        --clr-txt-900: #0d1117;
        --clr-txt-600: #4b5563;
        --clr-txt-400: #9ca3af;
        --radius-card: 14px;
        --shadow-card: 0 1px 3px rgba(0, 0, 0, .06), 0 8px 24px rgba(0, 0, 0, .07);
        --tr: all .16s cubic-bezier(.4, 0, .2, 1);
    }

    /* ── CARD SHELL ── */
    .report-card {
        background: var(--clr-surface);
        border: 1px solid var(--clr-border);
        border-radius: var(--radius-card);
        box-shadow: var(--shadow-card);
        overflow: hidden;
    }

    .report-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 20px 24px 18px;
        border-bottom: 1px solid var(--clr-sep);
    }

    .report-card-header .hd-icon {
        width: 36px;
        height: 36px;
        background: #f1f5f9;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: #475569;
        flex-shrink: 0;
    }

    .report-card-header h1 {
        font-size: 14.5px;
        font-weight: 700;
        color: var(--clr-txt-900);
        letter-spacing: -.2px;
        margin: 0 0 3px;
    }

    .report-card-header p {
        font-size: 11.5px;
        color: var(--clr-txt-400);
        margin: 0;
    }

    .report-card-body {
        padding: 26px 24px;
    }

    /* ── FORM LABEL ── */
    .form-label-upper {
        display: block;
        font-size: 11.5px;
        font-weight: 600;
        color: var(--clr-txt-600);
        letter-spacing: .04em;
        text-transform: uppercase;
        margin-bottom: 9px;
    }

    /* ── SELECT ── */
    .report-select {
        width: 100%;
        padding: 10px 36px 10px 14px;
        border: 1px solid var(--clr-border);
        border-radius: 10px;
        font-size: 13.5px;
        color: var(--clr-txt-900);
        background: var(--clr-surface) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath fill='%239ca3af' d='M7 10l5 5 5-5z'/%3E%3C/svg%3E") no-repeat right 12px center;
        appearance: none;
        cursor: pointer;
        transition: var(--tr);
        outline: none;
    }

    .report-select:focus {
        border-color: #64748b;
        box-shadow: 0 0 0 3px rgba(100, 116, 139, .12);
    }

    /* ── SEPARATOR ── */
    .form-sep {
        height: 1px;
        background: var(--clr-sep);
        margin: 22px 0;
    }

    /* ── FORMAT RADIO CARDS ── */
    .format-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .fmt-opt {
        position: relative;
    }

    .fmt-opt input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .fmt-card {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 13px 15px;
        border: 1.5px solid var(--clr-border);
        border-radius: 10px;
        cursor: pointer;
        transition: var(--tr);
        user-select: none;
    }

    .fmt-card:hover {
        border-color: #cbd5e1;
        background: #fafafa;
    }

    .fmt-icon {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        flex-shrink: 0;
        transition: var(--tr);
    }

    .fmt-card-text strong {
        display: block;
        font-size: 13.5px;
        font-weight: 700;
        color: var(--clr-txt-900);
        letter-spacing: -.1px;
    }

    .fmt-card-text span {
        display: block;
        font-size: 11.5px;
        color: var(--clr-txt-400);
        margin-top: 2px;
    }

    /* Checked states */
    .fmt-opt input:checked+.fmt-card {
        border-color: var(--clr-txt-900);
        background: #f8fafc;
    }

    .fmt-opt input:checked+.fmt-card .fmt-icon.is-excel {
        background: #dcfce7;
        color: #16a34a;
    }

    .fmt-opt input:checked+.fmt-card .fmt-icon.is-pdf {
        background: #fee2e2;
        color: #dc2626;
    }

    /* ── SUBMIT BUTTON ── */
    .btn-generate {
        width: 100%;
        margin-top: 22px;
        padding: 13px;
        background: var(--clr-txt-900);
        color: #fff;
        border: none;
        border-radius: 11px;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: -.15px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: var(--tr);
    }

    .btn-generate:hover {
        background: #1a2232;
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, .22);
    }

    .btn-generate:disabled {
        background: var(--clr-txt-400);
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }
</style>

<div class="mb-4">
    <h1 style="font-size:1.2rem;font-weight:800;color:var(--clr-txt-900);letter-spacing:-.4px;margin:0 0 4px;">Pusat Laporan</h1>
    <p style="font-size:12.5px;color:var(--clr-txt-400);margin:0;">Generate dan unduh dokumen inventaris Abhati Group</p>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="report-card">
            <div class="report-card-header">
                <div class="hd-icon"><i class="bi bi-file-earmark-arrow-down"></i></div>
                <div>
                    <h1>Unduh Dokumen &amp; Laporan Sistem</h1>
                    <p>Pilih kategori dan format ekspor yang diinginkan</p>
                </div>
            </div>

            <div class="report-card-body">
                <label class="form-label-upper" for="selectKategori">Kategori Data Laporan</label>
                <select class="report-select" id="selectKategori">
                    <option value="aset">Data Inventaris Aset Laptop</option>
                    <option value="repair" disabled>Log &amp; Riwayat Perbaikan Aset — Coming Soon</option>
                </select>

                <div class="form-sep"></div>

                <label class="form-label-upper">Format Dokumen</label>
                <div class="format-grid">
                    <label class="fmt-opt">
                        <input type="radio" name="docFormat" value="excel" checked>
                        <div class="fmt-card">
                            <div class="fmt-icon is-excel"><i class="bi bi-file-earmark-excel"></i></div>
                            <div class="fmt-card-text">
                                <strong>Excel</strong>
                                <span>.xlsx</span>
                            </div>
                        </div>
                    </label>

                    <label class="fmt-opt">
                        <input type="radio" name="docFormat" value="pdf">
                        <div class="fmt-card">
                            <div class="fmt-icon is-pdf"><i class="bi bi-file-earmark-pdf"></i></div>
                            <div class="fmt-card-text">
                                <strong>PDF</strong>
                                <span>.pdf</span>
                            </div>
                        </div>
                    </label>
                </div>

                <button class="btn-generate" id="btnGenerate">
                    <i class="bi bi-download"></i> <span id="btnText">Generate &amp; Unduh Laporan</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
    (function() {

        // ── 2. HELPER FORMATTER ──
        const rupiah = v => v ? 'Rp\u00a0' + Number(v).toLocaleString('id-ID') : '—';
        const tgl = v => v ? new Date(v + 'T00:00:00').toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        }) : '—';
        const kondisiLabel = k => window.KONDISI_CONFIG[k]?.label ?? k;

        // ── 2b. CONSTANTS & SHARED ASSET FETCHER ──
        const MAX_PDF = 500;
        const BATCH = 100;

        async function fetchAllAssets(limit = Infinity) {
            let allAssets = [],
                page = 1,
                totalPages = 1;
            do {
                const res = await apiFetch(`/api/reports/assets?size=${BATCH}&page=${page}`);
                if (!res) throw new Error('Sesi habis, silakan login ulang.');
                if (!res.ok) throw new Error('Gagal memuat data aset.');

                const json = await res.json();
                const items = json.data?.data ?? [];
                allAssets.push(...items);
                totalPages = json.data?.last_page ?? 1;

                if (allAssets.length >= limit) {
                    allAssets = allAssets.slice(0, limit);
                    break;
                }
                page++;
            } while (page <= totalPages);
            return allAssets;
        }

        // ── 3. FETCH & GENERATE PDF ──
        async function fetchAndGeneratePDF() {
            const btn = document.getElementById('btnGenerate');
            const btnText = document.getElementById('btnText');
            try {
                btn.disabled = true;
                btnText.innerText = 'Memproses Data...';
                showToast({
                    icon: 'bi-hourglass-split',
                    type: 'info',
                    title: 'Memproses PDF',
                    message: 'Mengambil data dari server, mohon tunggu...'
                });

                const resStats = await apiFetch('/api/reports/summary');
                if (!resStats) throw new Error('Sesi habis, silakan login ulang.');
                if (!resStats.ok) throw new Error('Gagal memuat ringkasan statistik.');
                const reportData = (await resStats.json()).data;

                const allAssets = await fetchAllAssets(MAX_PDF);
                buildPDF(reportData, allAssets);

                try {
                    await apiFetch('/api/audit/log', {
                        method: 'POST',
                        body: JSON.stringify({
                            action: 'EXPORT',
                            module: 'Pusat Laporan',
                            record_type: 'assets',
                            description: 'Mengunduh data seluruh aset laptop ke dalam format PDF.'
                        }),
                    });
                } catch (_) {}

                showToast({
                    icon: 'bi-check-circle',
                    type: 'info',
                    title: 'Berhasil',
                    message: 'File PDF berhasil diunduh.'
                });

            } catch (err) {
                console.error(err);
                showToast({
                    icon: 'bi-exclamation-triangle',
                    type: 'error',
                    title: 'Error',
                    message: err.message
                });
            } finally {
                btn.disabled = false;
                btnText.innerText = 'Generate & Unduh Laporan';
            }
        }

        // ── 4. BUILD PDF ──
        function buildPDF(reportData, allAssets) {
            if (!reportData || !allAssets.length) throw new Error('Data kosong.');

            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF({
                orientation: 'landscape',
                unit: 'mm',
                format: 'a4'
            });
            const pageW = doc.internal.pageSize.getWidth();
            const pageH = doc.internal.pageSize.getHeight();

            // Header corporate
            doc.setFillColor(26, 26, 46);
            doc.rect(0, 0, pageW, 35, 'F');
            doc.setTextColor(255, 255, 255);
            doc.setFontSize(18).setFont('helvetica', 'bold');
            doc.text('LAPORAN INVENTARIS ASET LAPTOP', pageW / 2, 14, {
                align: 'center'
            });
            doc.setFontSize(10).setFont('helvetica', 'normal');
            doc.text('Abhati Group — Departemen IT', pageW / 2, 22, {
                align: 'center'
            });
            doc.setFontSize(8);
            doc.text(`Digenerate: ${new Date().toLocaleString('id-ID')}`, pageW / 2, 29, {
                align: 'center'
            });

            // Kartu statistik
            const stats = reportData.kondisi_stats ?? [];
            const getTotal = k => stats.find(s => s.kondisi === k)?.total ?? 0;
            const boxes = [{
                    label: 'Total Aset',
                    value: String(reportData.total_aset),
                    color: [59, 130, 246]
                },
                {
                    label: 'Kondisi Baik',
                    value: String(getTotal('baik')),
                    color: [34, 197, 94]
                },
                {
                    label: 'Rusak',
                    value: String(getTotal('rusak')),
                    color: [239, 68, 68]
                },
                {
                    label: 'Total Biaya Perbaikan',
                    value: rupiah(reportData.total_biaya_perbaikan),
                    color: [234, 179, 8]
                },
            ];

            boxes.forEach(({
                label,
                value,
                color
            }, i) => {
                const x = 10 + i * 69;
                doc.setFillColor(...color);
                doc.roundedRect(x, 38, 66, 18, 3, 3, 'F');
                doc.setTextColor(255, 255, 255);
                doc.setFontSize(7).setFont('helvetica', 'normal');
                doc.text(label, x + 33, 44, {
                    align: 'center'
                });
                doc.setFontSize(11).setFont('helvetica', 'bold');
                doc.text(value, x + 33, 52, {
                    align: 'center'
                });
            });

            let startY = 60;
            if (reportData.total_aset > MAX_PDF) {
                doc.setTextColor(180, 100, 0);
                doc.setFontSize(7).setFont('helvetica', 'italic');
                doc.text(`* PDF menampilkan ${MAX_PDF} dari ${reportData.total_aset} aset. Gunakan ekspor Excel untuk data lengkap.`, pageW / 2, 58, {
                    align: 'center'
                });
                startY = 63;
            }

            doc.setTextColor(0, 0, 0);
            doc.autoTable({
                startY,
                head: [
                    ['No', 'Kode Aset', 'Merk / Model', 'Pengguna', 'Kondisi', 'Perbaikan', 'Tgl Beli', 'Harga Beli']
                ],
                body: allAssets.map((a, i) => [
                    i + 1, a.kode_aset, `${a.merk} ${a.model}`,
                    a.pengguna ?? '-', kondisiLabel(a.kondisi),
                    `${a.total_perbaikan ?? 0}x`, tgl(a.tanggal_beli), rupiah(a.harga_beli),
                ]),
                styles: {
                    fontSize: 8,
                    cellPadding: 2.5,
                    overflow: 'linebreak'
                },
                headStyles: {
                    fillColor: [26, 26, 46],
                    textColor: 255,
                    fontStyle: 'bold'
                },
                alternateRowStyles: {
                    fillColor: [245, 246, 250]
                },
                columnStyles: {
                    0: {
                        cellWidth: 10,
                        halign: 'center'
                    },
                    5: {
                        cellWidth: 18,
                        halign: 'center'
                    }
                },
            });

            // Footer pagination
            const pageCount = doc.internal.getNumberOfPages();
            for (let i = 1; i <= pageCount; i++) {
                doc.setPage(i);
                doc.setFontSize(7).setTextColor(150);
                doc.text(`Halaman ${i} dari ${pageCount}  •  Abhati Group — Sistem Inventaris Laptop`, pageW / 2, pageH - 4, {
                    align: 'center'
                });
            }

            doc.save(`Laporan_Aset_Laptop_Abhati_${new Date().toISOString().slice(0, 10)}.pdf`);
        }


        // ── 4b. FETCH & GENERATE EXCEL ──
        async function fetchAndGenerateExcel() {
            const btn = document.getElementById('btnGenerate');
            const btnText = document.getElementById('btnText');
            try {
                btn.disabled = true;
                btnText.innerText = 'Memproses Data...';
                showToast({
                    icon: 'bi-hourglass-split',
                    type: 'info',
                    title: 'Memproses Excel',
                    message: 'Mengambil data dari server, mohon tunggu...'
                });

                const allAssets = await fetchAllAssets();
                if (!allAssets.length) throw new Error('Data kosong.');

                buildExcel(allAssets);

                try {
                    await apiFetch('/api/audit/log', {
                        method: 'POST',
                        body: JSON.stringify({
                            action: 'EXPORT',
                            module: 'Pusat Laporan',
                            record_type: 'assets',
                            description: `Mengunduh ${allAssets.length} data aset laptop ke dalam format Excel.`
                        }),
                    });
                } catch (_) {}

                showToast({
                    icon: 'bi-check-circle',
                    type: 'info',
                    title: 'Berhasil',
                    message: 'File Excel berhasil diunduh.'
                });

            } catch (err) {
                console.error(err);
                showToast({
                    icon: 'bi-exclamation-triangle',
                    type: 'error',
                    title: 'Error',
                    message: err.message
                });
            } finally {
                btn.disabled = false;
                btnText.innerText = 'Generate & Unduh Laporan';
            }
        }

        // ── 4c. BUILD EXCEL ──
        function buildExcel(allAssets) {
            const rows = allAssets.map((a, i) => ({
                'No': i + 1,
                'Kode Aset': a.kode_aset,
                'Merk/Model': `${a.merk} ${a.model}`,
                'Pengguna': a.pengguna ?? '-',
                'Kondisi': kondisiLabel(a.kondisi),
                'Perbaikan': `${a.total_perbaikan ?? 0}x`,
                'Tanggal Beli': tgl(a.tanggal_beli),
                'Harga Beli': rupiah(a.harga_beli),
            }));

            const ws = XLSX.utils.json_to_sheet(rows);
            ws['!cols'] = [{
                    wch: 5
                }, {
                    wch: 14
                }, {
                    wch: 28
                }, {
                    wch: 18
                },
                {
                    wch: 16
                }, {
                    wch: 10
                }, {
                    wch: 14
                }, {
                    wch: 16
                },
            ];

            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Data Aset');
            XLSX.writeFile(wb, `Laporan_Aset_Laptop_Abhati_${new Date().toISOString().slice(0, 10)}.xlsx`);
        }

        // ── 5. EVENT LISTENER ──
        document.getElementById('btnGenerate').addEventListener('click', function() {
            const kategori = document.getElementById('selectKategori').value;
            const format = document.querySelector('input[name="docFormat"]:checked').value;

            if (kategori === 'aset') {
                if (format === 'excel') {
                    fetchAndGenerateExcel();
                } else if (format === 'pdf') {
                    fetchAndGeneratePDF();
                }
            }
        });

    }());
</script>