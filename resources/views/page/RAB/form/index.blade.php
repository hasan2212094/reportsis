@extends('kerangka.master')
@section('title', 'Pengajuan RAB')

@section('content')

    @php
        $now = now()->setTimezone('Asia/Jakarta');
        $role = auth()->user()->role;

        $day = $now->dayOfWeek; // 0=Sunday, 1=Monday, 4=Thursday
        $time = $now->format('H:i');

        // Kondisi dibatasi (hanya LUAR RAB boleh)
        $isLimitedPeriod =
            $role === 'user' &&
            // Kamis setelah 17:00
            (($day == 4 && $time > '17:00') ||
                // Jumat & Sabtu
                ($day == 5 || $day == 6) ||
                // Minggu
                $day == 0 ||
                // Senin sebelum 08:00
                ($day == 1 && $time < '08:00'));
    @endphp


    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- ================= NOTIFIKASI ================= --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bx bx-error-circle me-1"></i> <strong>Gagal menyimpan data</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if ($errors->has('waktu'))
            <div class="alert alert-danger">
                {{ $errors->first('waktu') }}
            </div>
        @endif

        {{-- ================= FORM CREATE ================= --}}
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="fw-bold mb-4">Tambah Pengajuan DANA</h4>
                <form action="{{ route('page.rab.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Tipe Pengajuan --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tipe Pengajuan</label>
                        <div class="d-flex gap-2">

                            <button type="button" id="btnDirect" class="btn btn-outline-primary"
                                {{ $isLimitedPeriod ? 'disabled' : '' }}>
                                Direct Cost
                            </button>

                            <button type="button" id="btnIndirect" class="btn btn-outline-primary"
                                {{ $isLimitedPeriod ? 'disabled' : '' }}>
                                Indirect Cost
                            </button>

                            <button type="button" id="btnLuarrab" class="btn btn-outline-primary">
                                Luar RAB
                            </button>

                        </div>

                        @if ($isLimitedPeriod)
                            <small class="text-warning d-block mt-2">
                                Mulai Kamis jam 17:01 sampai Senin jam 08:00 hanya menu LUAR RAB yang dapat digunakan.
                            </small>
                        @endif
                    </div>
                    <input type="hidden" name="tipe_pengajuan" id="tipe_pengajuan">


                    <div class="row g-3">
                        {{-- No RAB --}}
                        <div class="col-md-6">
                            <label class="form-label">No Pengajuan DANA</label>
                            <input type="text" class="form-control" value="Otomatis oleh sistem" readonly>
                            <small class="text-muted">
                                Nomor akan dibuat otomatis saat pengajuan disimpan
                            </small>
                        </div>

                        {{-- Workorder --}}
                        <div class="col-md-6" id="woField">
                            <label class="form-label">Workorder</label>
                            <select name="workorder_id" id="workorderInput" class="form-select">
                                <option value="">-- Pilih Workorder --</option>
                                @foreach ($workorders as $wo)
                                    <option value="{{ $wo->id }}">{{ $wo->kode_wo }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- No PR --}}
                        <div class="col-md-6">
                            <label class="form-label">No PR</label>
                            <input type="text" name="no_pr" class="form-control" value="{{ old('no_pr') }}">
                        </div>

                        {{-- Nama Barang --}}
                        <div class="col-md-6">
                            <label class="form-label">Nama Barang</label>
                            <input type="text" name="nama_barang" class="form-control" value="{{ old('nama_barang') }}">
                        </div>

                        {{-- Qty --}}
                        <div class="col-md-3">
                            <label class="form-label">Qty</label>
                            <input type="number" name="qty" class="form-control" value="{{ old('qty') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Unit</label>
                            <input type="text" name="unit" class="form-control" value="{{ old('unit') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Nominal</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" id="total_display" class="form-control" placeholder="0"
                                    autocomplete="off">
                            </div>

                            <!-- WAJIB ADA -->
                            <input type="hidden" name="total" id="total">
                        </div>

                        {{-- Nama Toko --}}
                        <div class="col-md-6">
                            <label class="form-label">Nama Toko</label>
                            <input type="text" name="nama_toko" class="form-control" value="{{ old('nama_toko') }}">
                        </div>

                        {{-- Status PPN --}}
                        <div class="col-md-4">
                            <label class="form-label">Pajak</label>
                            <select name="status_ppn" class="form-select">
                                <option value="0" {{ old('status_ppn') == 0 ? 'selected' : '' }}>Non PPN</option>
                                <option value="1" {{ old('status_ppn') == 1 ? 'selected' : '' }}>PPN</option>
                            </select>
                        </div>

                        {{-- Bank --}}
                        <div class="col-md-4">
                            <label class="form-label">Bank</label>
                            <input type="text" name="bank" class="form-control" value="{{ old('bank') }}">
                        </div>

                        {{-- No Rekening --}}
                        <div class="col-md-4">
                            <label class="form-label">No Rekening</label>
                            <input type="text" name="no_rek" class="form-control" value="{{ old('no_rek') }}">
                        </div>

                        {{-- Atas Nama --}}
                        <div class="col-md-4">
                            <label class="form-label">Atas Nama</label>
                            <input type="text" name="atas_nama" class="form-control" value="{{ old('atas_nama') }}">
                        </div>

                        {{-- Kebutuhan --}}
                        <div class="col-12">
                            <label class="form-label">Kebutuhan</label>
                            <textarea name="kebutuhan" class="form-control" rows="3">{{ old('kebutuhan') }}</textarea>
                        </div>

                        {{-- File Keterangan --}}
                        <div class="col-md-6">
                            <label class="form-label">File Kebutuhan</label>
                            <input type="file" name="file_keterangan" class="form-control">
                        </div>

                        {{-- Invoice --}}
                        <div class="col-md-6">
                            <label class="form-label">Invoice</label>
                            <input type="file" name="invoice_file" class="form-control">
                        </div>

                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-send"></i> Kirim Pengajuan
                        </button>
                    </div>

                </form>
            </div>
        </div>

        {{-- ================= TABEL INDEX ================= --}}
        <div class="card">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Daftar Pengajuan DANA</h5>
                @php
                    $statusLabels = [
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ];
                @endphp
                <div class="table-responsive position-relative rab-scroll-wrapper">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>No Pengajuan DANA</th>
                                <th>nama pengajuan</th>
                                <th>Type pengajuan</th>
                                <th>WO</th>
                                <th>Barang</th>
                                <th>Qty</th>
                                <th>Unit</th>
                                <th>Nominal</th>
                                <th>Status</th>
                                <th>Ket Approval</th>
                                <th>Ket Partial</th>
                                <th>Ket Reject</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rabpengajuans as $i => $rab)
                                <tr>
                                    <td>{{ $rabpengajuans->firstItem() + $i }}</td>
                                    <td>{{ $rab->kode_rab }}</td>
                                    <td>{{ optional($rab->user)->name ?? '-' }}</td>
                                    <td>
                                        <span
                                            class="badge
                                           {{ $rab->tipe_pengajuan == 'direct'
                                               ? 'bg-primary'
                                               : ($rab->tipe_pengajuan == 'luarrab'
                                                   ? 'bg-danger'
                                                   : 'bg-warning') }}">
                                            {{ ucfirst($rab->tipe_pengajuan) }}
                                        </span>
                                    </td>
                                    <td>{{ optional($rab->workorder)->kode_wo ?? '-' }}</td>
                                    <td>{{ $rab->nama_barang }}</td>
                                    <td>{{ $rab->qty }}</td>
                                    <td>{{ $rab->unit }}</td>
                                    <td>Rp {{ number_format($rab->total, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $rab->status_pengajuan_color }}">
                                            {{ $rab->status_pengajuan_label }}
                                        </span>
                                    </td>
                                    <td>{{ $rab->comment_approved ?? '-' }}</td>
                                    <td>{{ $rab->keterangan_partial ?? '-' }}</td>
                                    <td>{{ $rab->note_reject ?? '-' }}</td>
                                    <td>{{ $rab->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('page.rab.edit', $rab->id) }}"
                                            class="btn btn-sm btn-warning mb-1">Edit</a>
                                        <form action="{{ route('page.rab.destroy', $rab->id) }}" method="POST"
                                            class="d-inline-block" onsubmit="return confirm('Yakin ingin menghapus?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger mb-1">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted">Belum ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{-- <div class="mt-3 d-flex justify-content-end">
                        <nav>
                            <ul class="pagination" id="pagination"></ul>
                        </nav>
                    </div> --}}

                </div>

                {{-- {{ $rabpengajuans->links() }} --}}
                @if ($rabpengajuans->hasPages())
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                            <small class="text-muted">
                                Menampilkan {{ $rabpengajuans->firstItem() }}
                                - {{ $rabpengajuans->lastItem() }}
                                dari {{ $rabpengajuans->total() }} data
                            </small>

                            {{ $rabpengajuans->links() }}

                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            const isLimitedPeriod = @json($isLimitedPeriod);

            document.addEventListener('DOMContentLoaded', function() {

                const wrapper = document.querySelector('.rab-scroll-wrapper');
                const table = wrapper.querySelector('table');

                // =============================
                // Biar tidak turun baris
                // =============================
                table.querySelectorAll('th, td').forEach(cell => {
                    cell.style.whiteSpace = 'nowrap';
                    cell.style.verticalAlign = 'middle';
                });

                // =============================
                // AUTO WIDTH SESUAI TEKS TERPANJANG
                // =============================
                const rows = table.querySelectorAll('tr');

                if (rows.length > 0) {
                    const columnCount = rows[0].children.length;

                    for (let col = 0; col < columnCount; col++) {

                        let maxWidth = 0;

                        rows.forEach(row => {
                            const cell = row.children[col];
                            if (!cell) return;

                            const width = cell.scrollWidth;
                            if (width > maxWidth) {
                                maxWidth = width;
                            }
                        });

                        rows.forEach(row => {
                            const cell = row.children[col];
                            if (cell) {
                                cell.style.minWidth = (maxWidth + 20) + 'px';
                            }
                        });
                    }
                }

                // =============================
                // SCROLL HORIZONTAL PAKAI MOUSE
                // =============================
                wrapper.addEventListener('wheel', function(e) {
                    if (e.deltaY !== 0) {
                        e.preventDefault();
                        wrapper.scrollLeft += e.deltaY;
                    }
                });

            });

            document.addEventListener('DOMContentLoaded', function() {
                const wrapper = document.querySelector('.rab-scroll-wrapper');

                if (wrapper) {
                    wrapper.addEventListener('wheel', function(e) {
                        if (e.deltaY !== 0) {
                            e.preventDefault();
                            wrapper.scrollLeft += e.deltaY;
                        }
                    });
                }
            });

            document.addEventListener('DOMContentLoaded', function() {
                const btnDirect = document.getElementById('btnDirect');
                const btnIndirect = document.getElementById('btnIndirect');
                const btnLuarrab = document.getElementById('btnLuarrab');

                const tipeInput = document.getElementById('tipe_pengajuan');
                const woField = document.getElementById('woField');
                const woInput = document.getElementById('workorderInput');

                const nomorimput = document.getElementById('RabInput');

                function resetButton() {
                    btnDirect.classList.remove('active');
                    btnIndirect.classList.remove('active');
                    btnLuarrab.classList.remove('active');
                }

                // =============================
                // DEFAULT MODE = DIRECT
                // =============================
                resetButton();

                if (isLimitedPeriod) {
                    // Kalau masuk periode terbatas → default LUAR RAB
                    btnLuarrab.classList.add('active');
                    tipeInput.value = 'luarrab';
                } else {
                    // Normal → default DIRECT
                    btnDirect.classList.add('active');
                    tipeInput.value = 'direct';
                }


                // DIRECT COST (relasi WO)
                btnDirect.addEventListener('click', function() {
                    if (btnDirect.disabled) return;
                    tipeInput.value = 'direct';

                    resetButton();
                    btnDirect.classList.add('active');

                    woField.classList.remove('d-none');
                });

                // INDIRECT COST (tanpa WO)
                btnIndirect.addEventListener('click', function() {
                    if (btnDirect.disabled) return;
                    tipeInput.value = 'indirect';

                    resetButton();
                    btnIndirect.classList.add('active');

                    woField.classList.add('d-none');
                    woInput.value = '';

                });

                // LUAR RAB (WO sebagai keterangan)
                btnLuarrab.addEventListener('click', function() {
                    tipeInput.value = 'luarrab';

                    resetButton();
                    btnLuarrab.classList.add('active');

                    woField.classList.remove('d-none');
                });
                document.getElementById('total_display').addEventListener('input', function(e) {

                    let value = e.target.value.replace(/\D/g, '');

                    // set ke hidden input (untuk disimpan)
                    document.getElementById('total').value = value;

                    // format rupiah
                    e.target.value = new Intl.NumberFormat('id-ID').format(value);
                });
            });
        </script>
    @endpush

@endsection
