@extends('kerangka.master')
@section('title', 'Report Actual')

@section('content')
    <div class="container mt-4">
        <h5 class="fw-bold mb-3">Report Actual Direct Cost, diluar RAB & PPN</h5>

        {{-- FILTER --}}
        <div class="mb-3">
            <label class="form-label fw-bold">PILIH NO. WO</label>
            <div class="d-flex flex-wrap gap-2">
                <select id="wo_select" class="form-select" style="max-width:300px">
                    <option value="">-- Pilih Work Order --</option>
                    @foreach ($workorders as $wo)
                        <option value="{{ $wo->id }}">{{ $wo->kode_wo }}</option>
                    @endforeach
                </select>

                <button id="btnLoad" class="btn btn-primary">Tampilkan</button>
                <button id="btnExport" class="btn btn-success">Export Excel</button>
            </div>
        </div>

        {{-- ================= DESKTOP TABLE ================= --}}
        <div class="d-none d-lg-block">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Tanggal</th>
                            <th>Toko</th>
                            <th>Total</th>
                            <th>Sumber</th>
                        </tr>
                    </thead>
                    <tbody id="desktopTable">
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                Pilih Work Order terlebih dahulu
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ================= MOBILE CARD ================= --}}
        <div class="d-block d-lg-none" id="mobileList">
            <div class="text-center text-muted">
                Pilih Work Order terlebih dahulu
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('btnLoad').addEventListener('click', function() {
            const woId = document.getElementById('wo_select').value;
            const desktop = document.getElementById('desktopTable');
            const mobile = document.getElementById('mobileList');

            if (!woId) {
                desktop.innerHTML = `<tr><td colspan="7" class="text-center text-warning">Pilih WO dulu</td></tr>`;
                mobile.innerHTML = `<div class="text-warning text-center">Pilih WO dulu</div>`;
                return;
            }

            desktop.innerHTML = `<tr><td colspan="7" class="text-center">Loading...</td></tr>`;
            mobile.innerHTML = `<div class="text-center">Loading...</div>`;

            fetch(`/report/actual/${woId}`)
                .then(res => res.json())
                .then(data => {
                    if (!Array.isArray(data) || data.length === 0) {
                        desktop.innerHTML =
                            `<tr><td colspan="7" class="text-center text-muted">Tidak ada data</td></tr>`;
                        mobile.innerHTML = `<div class="text-center text-muted">Tidak ada data</div>`;
                        return;
                    }

                    // ===== DESKTOP =====
                    desktop.innerHTML = data.map((item, i) => `
                <tr>
                    <td>${i + 1}</td>
                    <td>${item.item ?? '-'}</td>
                    <td>${item.qty ?? '-'}</td>
                    <td>${item.tanggal_actual ?? '-'}</td>
                    <td>${item.toko ?? '-'}</td>
                    <td>Rp ${item.total ? Number(item.total).toLocaleString('id-ID') : '-'}</td>
                    <td>
                        <span class="badge bg-secondary">${item.source ?? '-'}</span>
                    </td>
                </tr>
            `).join('');

                    // ===== MOBILE =====
                    mobile.innerHTML = data.map(item => `
                <div class="card mb-2 shadow-sm">
                    <div class="card-body">
                        <div class="fw-bold">${item.item ?? '-'}</div>
                        <div class="small text-muted mb-1">
                            ${item.tanggal_actual ?? '-'}
                        </div>
                        <hr class="my-2">
                        <div><strong>Qty:</strong> ${item.qty ?? '-'}</div>
                        <div><strong>Toko:</strong> ${item.toko ?? '-'}</div>
                        <div class="fw-bold text-success mt-1">
                            Rp ${item.total ? Number(item.total).toLocaleString('id-ID') : '-'}
                        </div>
                        <span class="badge bg-secondary mt-2">
                            ${item.source ?? '-'}
                        </span>
                    </div>
                </div>
            `).join('');
                })
                .catch(() => {
                    desktop.innerHTML =
                        `<tr><td colspan="7" class="text-danger text-center">Gagal memuat data</td></tr>`;
                    mobile.innerHTML = `<div class="text-danger text-center">Gagal memuat data</div>`;
                });
        });

        document.getElementById('btnExport').addEventListener('click', function() {
            const woId = document.getElementById('wo_select').value;
            if (!woId) return alert('Pilih WO dulu');
            window.location.href = `/report/export/${woId}`;
        });
    </script>
@endpush
