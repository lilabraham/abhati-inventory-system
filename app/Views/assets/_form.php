<form id="formTambahAset">
    <div class="row g-3">

        <div class="col-md-4">
            <label class="form-label fw-semibold">Kode Aset <span class="text-danger">*</span></label>
            <input name="kode_aset" class="form-control" placeholder="ABT-LP-001" required maxlength="20">
            <div class="form-text">Format: ABT-LP-XXX</div>
        </div>

        <div class="col-md-4">
            <label class="form-label fw-semibold">Merk <span class="text-danger">*</span></label>
            <input name="merk" class="form-control" placeholder="Lenovo, Dell, HP, Asus..." required maxlength="100">
        </div>

        <div class="col-md-4">
            <label class="form-label fw-semibold">Model <span class="text-danger">*</span></label>
            <input name="model" class="form-control" placeholder="ThinkPad X1 Carbon" required maxlength="100">
        </div>

        <div class="col-md-4">
            <label class="form-label fw-semibold">Serial Number</label>
            <input name="serial_number" class="form-control" placeholder="SN-XXXXXXXX" maxlength="100">
        </div>

        <div class="col-md-4">
            <label class="form-label fw-semibold">Pengguna</label>
            <input name="pengguna" class="form-control" placeholder="Nama karyawan" maxlength="100">
        </div>

        <div class="col-md-4">
            <label class="form-label fw-semibold">Kondisi <span class="text-danger">*</span></label>
            <select name="kondisi" id="tambahKondisi" class="form-select" required>
                <option value="">-- Pilih Kondisi --</option>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label fw-semibold">Lokasi</label>
            <input name="lokasi" class="form-control" placeholder="Ruang IT, Divisi Finance, Lantai 2..." maxlength="150">
        </div>

        <div class="col-md-3">
            <label class="form-label fw-semibold">Tanggal Beli</label>
            <input type="date" name="tanggal_beli" class="form-control">
        </div>

        <div class="col-md-3">
            <label class="form-label fw-semibold">Harga Beli (Rp)</label>
            <input type="number" name="harga_beli" class="form-control" placeholder="15000000" min="0" step="1">
            <div class="form-text">Contoh: 15000000 = Rp 15.000.000</div>
        </div>

        <div class="col-12">
            <label class="form-label fw-semibold">Spesifikasi</label>
            <textarea name="spesifikasi" class="form-control" rows="3"
                placeholder="Contoh: Intel Core i7-12th, RAM 16GB DDR4, SSD 512GB NVMe, Windows 11 Pro"></textarea>
        </div>

    </div>

    <div class="mt-4 d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i> Simpan Aset
        </button>
    </div>
</form>

<script>
    (function() {
        const sel = document.getElementById('tambahKondisi');
        if (!sel || !window.KONDISI_CONFIG) return;
        Object.entries(window.KONDISI_CONFIG).forEach(([val, cfg], i) => {
            const opt = document.createElement('option');
            opt.value = val;
            opt.textContent = cfg.label;
            if (i === 0) opt.selected = true;
            sel.appendChild(opt);
        });
    })();
</script>