@extends('kerangka.master')
@section('title', 'Summary Pengajuan RAB')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="fw-bold mb-4">
            <span class="text-muted fw-light">DANA /</span> Summary Pengajuan
        </h4>

        {{-- FILTER --}}
        <form method="GET" class="row g-3 mb-3 align-items-end">
            <div class="col-md-2">
                <select name="workorder" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua WO --</option>
                    @foreach ($workorders as $wo)
                        <option value="{{ $wo->id }}" {{ request('workorder') == $wo->id ? 'selected' : '' }}>
                            {{ $wo->kode_wo }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <input type="text" name="nama_barang" value="{{ request('nama_barang') }}" class="form-control"
                    placeholder="Nama Barang">
            </div>

            {{-- Pilih Tipe Tanggal --}}
            <div class="col-md-2">
                <select name="tanggal_type" class="form-select">
                    <option value="pengajuan" {{ request('tanggal_type') == 'pengajuan' ? 'selected' : '' }}>Tanggal
                        Pengajuan</option>
                    <option value="approved" {{ request('tanggal_type') == 'approved' ? 'selected' : '' }}>Tanggal Approved
                    </option>
                </select>
            </div>

            {{-- Date Range --}}
            <div class="col-md-2">
                <input type="date" name="tanggal_from" value="{{ request('tanggal_from') }}" class="form-control"
                    placeholder="Dari">
            </div>
            <div class="col-md-2">
                <input type="date" name="tanggal_to" value="{{ request('tanggal_to') }}" class="form-control"
                    placeholder="Sampai">
            </div>

            {{-- Status --}}
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">-- Status --</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Filter</button>
                <button type="submit" name="export" value="1" class="btn btn-success">
                    Export Excel
                </button>
                <a href="{{ route('page.RAB.summary.index') }}" class="btn btn-secondary">
                    Tampilkan Semua
                </a>
            </div>
        </form>


        {{-- TABLE --}}
        <div class="card">
            <div class="card-body">
                <div class="table-responsive position-relative summary-table-wrapper">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th>No</th>
                                <th>No Pengajuan DANA</th>
                                <th>No. WO</th>
                                <th>Nama Barang</th>
                                <th>Nama Pengaju</th>
                                <th>Qty</th>
                                <th>Unit</th>
                                <th>Nominal</th>
                                <th>No Rek</th>
                                <th>Nama Toko</th>
                                <th>No PR</th>
                                <th>Keterangan</th>
                                <th>Type Pengajuan</th>
                                <th>File Keterangan</th>
                                <th>Invoice</th>
                                <th>Status</th>
                                <th>Bukti Pembayaran</th>
                                <th>Tanggal Approved</th>
                                <th>Nama PT</th>
                                <th>PPN</th>
                                <th>Nominal Approved</th>
                                <th>Reject Note</th>
                                <th>Tanggal Pengajuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rabpengajuans as $i => $rab)
                                <tr>
                                    <td class="text-center">{{ $rabpengajuans->firstItem() + $i }}</td>
                                    <td>{{ $rab->kode_rab }}</td>
                                    <td>{{ $rab->workorder->kode_wo ?? '-' }}</td>
                                    <td>{{ $rab->nama_barang }}</td>
                                    <td>{{ optional($rab->user)->name ?? '-' }}</td>
                                    <td class="text-center">{{ $rab->qty_approved }}</td>
                                    <td>{{ $rab->unit }}</td>
                                    <td>
                                        Rp {{ number_format($rab->nominal_approved, 0, ',', '.') }}
                                    </td>
                                    <td>{{ $rab->no_rek }}</td>
                                    <td>{{ $rab->nama_toko }}</td>
                                    <td>{{ $rab->no_pr }}</td>
                                    <td>{{ $rab->kebutuhan }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $rab->tipe_pengajuan == 'direct' ? 'bg-primary' : ($rab->tipe_pengajuan == 'luarrab' ? 'bg-danger' : 'bg-warning') }}">
                                            {{ ucfirst($rab->tipe_pengajuan) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if ($rab->file_keterangan)
                                            <a href="{{ asset('storage/' . $rab->file_keterangan) }}" target="_blank"
                                                class="btn btn-sm btn-info">Lihat <i class="bi bi-eye"></i></a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($rab->invoice_file)
                                            <a href="{{ asset('storage/' . $rab->invoice_file) }}" target="_blank"
                                                class="btn btn-sm btn-primary">Lihat <i class="bi bi-eye"></i></a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge bg-{{ $rab->status_pengajuan_color }}">{{ $rab->status_pengajuan_label }}</span>
                                    </td>
                                    <td class="text-center">
                                        @foreach ($rabpengajuan->image_buktibayar ?? [] as $file)
                                            <span
                                                class="badge 
                                                {{ $file['tipe'] == 'partial' ? 'bg-warning' : 'bg-success' }}">
                                                {{ ucfirst($file['tipe']) }}
                                            </span>
                                            <a href="{{ asset('storage/' . $file['file']) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $file['file']) }}" width="50"
                                                    class="img-thumbnail mb-1">
                                            </a>
                                            <br>
                                        @endforeach
                                    </td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($rab->tanggal_approved)->format('d-m-Y') }}
                                    </td>
                                    <td>{{ $rab->nama_pt }}</td>
                                    <td>
                                        <span class="badge {{ $rab->status_ppn == 'ppn' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $rab->status_ppn == 'ppn' ? 'PPN' : 'Non PPN' }}
                                        </span>
                                    </td>
                                    <td>{{ $rab->total_approved !== null ? 'Rp ' . number_format($rab->total_approved, 0, ',', '.') : '-' }}
                                    </td>
                                    <td>{{ $rab->note_reject ?? '-' }}</td>
                                    <td>{{ $rab->tanggal_pengajuan?->format('d-m-Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="23" class="text-center text-muted">Belum ada data pengajuan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @if ($rabpengajuans->hasPages())
            <div class="mt-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <small class="text-muted">
                        Menampilkan {{ $rabpengajuans->firstItem() }} - {{ $rabpengajuans->lastItem() }} dari
                        {{ $rabpengajuans->total() }} data
                    </small>
                    {{ $rabpengajuans->links() }}
                </div>
            </div>
        @endif


        {{-- Optional JS: scroll horizontal with wheel --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const wrapper = document.querySelector('.summary-table-wrapper');
                if (!wrapper) return;

                const table = wrapper.querySelector('table');
                if (!table) return;

                // ===== Horizontal scroll pakai mouse =====
                wrapper.addEventListener('wheel', function(e) {
                    if (e.deltaY !== 0) {
                        e.preventDefault();
                        wrapper.scrollLeft += e.deltaY;
                    }
                });

                // ===== Jangan turun baris =====
                table.querySelectorAll('th, td').forEach(cell => {
                    cell.style.whiteSpace = 'nowrap';
                    cell.style.verticalAlign = 'middle';
                });

                // ===== Hitung lebar per kolom (ikut text terpanjang) =====
                const rows = table.querySelectorAll('tr');
                if (rows.length === 0) return;

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
                            cell.style.minWidth = (maxWidth + 12) + 'px';
                        }
                    });
                }

            });

            document.addEventListener('DOMContentLoaded', function() {
                const wrapper = document.querySelector('.summary-table-wrapper');
                if (wrapper) {
                    wrapper.addEventListener('wheel', function(e) {
                        if (e.deltaY !== 0) {
                            e.preventDefault();
                            wrapper.scrollLeft += e.deltaY;
                        }
                    });
                }
            });
        </script>

    @endsection
