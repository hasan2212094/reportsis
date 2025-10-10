@extends('kerangka.master')
@section('title', 'Report Actual')
@section('content')
    <div class="container mt-4">
        <h5 class="fw-bold mb-3">Report Actual Direct Cost,diluar RAB & PPN</h5>

        <div class="mb-3">
            <label for="wo_select" class="form-label fw-bold">PILIH NO. WO</label>
            <div class="d-flex">
                <select id="wo_select" class="form-select" style="max-width: 300px;">
                    <option value="">-- Pilih Work Order --</option>
                    @foreach ($workorders as $wo)
                        <option value="{{ $wo->id }}">{{ $wo->kode_wo }}</option>
                    @endforeach
                </select>
                <button id="btnLoad" class="btn btn-primary ms-2">Tampilkan</button>
            </div>
        </div>

        <table class="table table-bordered table-striped mt-3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Tanggal Actual</th>
                    <th>Toko</th>
                    <th>Total</th>
                    <th>Sumber</th>
                </tr>
            </thead>
            <tbody id="dataTable">
                <tr>
                    <td colspan="7" class="text-center text-muted">Silakan pilih WO terlebih dahulu</td>
                </tr>
            </tbody>
        </table>
    </div>

    <script>
        document.getElementById('btnLoad').addEventListener('click', function() {
            const woId = document.getElementById('wo_select').value;
            const tbody = document.getElementById('dataTable');

            if (!woId) {
                tbody.innerHTML =
                    '<tr><td colspan="7" class="text-center text-warning">Pilih Work Order terlebih dahulu</td></tr>';
                return;
            }

            tbody.innerHTML = '<tr><td colspan="7" class="text-center text-secondary">Memuat data...</td></tr>';

            fetch(`{{ url('/report/actual') }}/${woId}`)
                .then(res => res.json())
                .then(data => {
                    if (!Array.isArray(data) || data.length === 0) {
                        tbody.innerHTML =
                            '<tr><td colspan="7" class="text-center text-warning">Tidak ada data</td></tr>';
                        return;
                    }

                    tbody.innerHTML = data.map((item, i) => `
     <tr>
        <td>${i + 1}</td>
        <td>${item.item ?? '-'}</td>
        <td>${item.qty ?? '-'}</td>
        <td>${item.tanggal_actual ?? '-'}</td>
        <td>${item.toko ?? '-'}</td>
        <td>${item.total ? Number(item.total).toLocaleString('id-ID') : '-'}</td>
        <td>${item.source ?? '-'}</td>
    </tr>
`).join('');
                })
                .catch(() => {
                    tbody.innerHTML =
                        '<tr><td colspan="7" class="text-center text-danger">Terjadi kesalahan memuat data.</td></tr>';
                });
        });
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('btnLoad').addEventListener('click', function() {
                // isi kode fetch di sini...
            });
        });
    </script>
@endsection
