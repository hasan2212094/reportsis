@extends('kerangka.master')
@section('title', 'Workorder')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Tables /</span> Workorder
        </h4>

        <div class="card shadow-sm">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif


            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Data Workorder</h5>

                <a href="{{ route('page.workorder.exportall') }}" class="btn btn-success px-4">
                    <i class="bx bx-file"></i> Export Excel
                </a>

                {{-- <a href="{{ route('page.workorder.exportpdf') }}" class="btn btn-danger">
                    Export PDF
                </a> --}}

                {{-- Filter & Tombol Tambah --}}
                <div class="d-flex gap-2 align-items-center">


                    {{-- Filter WO --}}
                    <form method="GET" action="{{ route('page.workorder.index') }}">
                        <div class="row g-3 mb-3">

                            {{-- Kolom 1 --}}
                            <div class="col-md-4">
                                <label class="form-label">
                                    No WO / Support By
                                </label>
                                <input type="text" name="wo_support" class="form-control"
                                    placeholder="Cari No WO atau Support By..." value="{{ request('wo_support') }}">
                            </div>

                            {{-- Kolom 2 --}}
                            <div class="col-md-4">
                                <label class="form-label">
                                    Customer / Nama Produk
                                </label>
                                <input type="text" name="customer_produk" class="form-control"
                                    placeholder="Cari Customer atau Nama Produk..."
                                    value="{{ request('customer_produk') }}">
                            </div>

                            {{-- Tombol --}}
                            <div class="col-md-4 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    Filter
                                </button>

                                <a href="{{ route('page.workorder.index') }}" class="btn btn-secondary w-100">
                                    Reset
                                </a>
                            </div>

                        </div>
                    </form>



                    {{-- Tombol Tambah --}}
                    <div class="d-flex justify-content-between align-items-end mt-3">

                        {{-- Tombol Tambah --}}
                        <a href="{{ route('page.workorder.create') }}" class="btn btn-primary px-4">
                            <i class="bx bx-plus"></i> Tambah
                        </a>

                    </div>
                </div>
            </div>


            {{-- TABLE --}}
            <div class="table-responsive postion-relative wo-scroll-wrapper">
                <table class="table table-hover align-middle mb-0 text-nowrap">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%">No</th>
                            <th class="text-center">WO No</th>
                            <th class="text-center">Support By</th>
                            <th class="text-center">Customer</th>
                            <th class="text-center">PO No</th>
                            <th class="text-center">PO Date</th>
                            <th class="text-center">Qty</th>
                            <th class="text-center">Wo Date</th>
                            <th class="text-center">Nama Produk</th>
                            <th class="text-center">Type Unit</th>
                            <th class="text-center">Pekerjaan Selesai</th>
                            <th class="text-center">Pekerjaan Termasuk</th>
                            <th class="text-center">pekerjaan Tidak Termasuk</th>
                            <th class="text-center">Garansi</th>
                            <th class="text-center">Harga</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($workorders as $index => $wo)
                            <tr>
                                <td class="text-muted">
                                    {{ $workorders->firstItem() + $index }}
                                </td>

                                {{-- Klik WO No untuk modal preview --}}
                                <td class="fw-semibold text-primary">
                                    <a href="#" class="text-decoration-none show-wo-detail" data-bs-toggle="modal"
                                        data-bs-target="#woDetailModal" data-id="{{ $wo->id }}">
                                        {{ $wo->kode_wo }}
                                    </a>
                                </td>
                                <td class="text-center">{{ $wo->end_user ?? '-' }}</td>
                                <td class="text-center">{{ $wo->customer_name }}</td>
                                <td>{{ $wo->customer_po_no ?? '-' }}</td>
                                <td>
                                    {{ $wo->customer_po_date ? \Carbon\Carbon::parse($wo->customer_po_date)->format('d-m-Y') : '-' }}
                                </td>
                                <td class="text-center">{{ $wo->quantity ?? '-' }}</td>
                                <td class="text-center" class="nowrap-col">
                                    {{ $wo->wo_date ? \Carbon\Carbon::parse($wo->wo_date)->format('d-m-Y') : '-' }}
                                </td>
                                <td class="text-center">{{ $wo->nama_produk ?? '-' }}</td>
                                <td class="text-center">{{ $wo->type_unit ?? '-' }}</td>
                                <td class="text-center">{{ $wo->pekerjaan_selesai ?? '-' }}</td>
                                <td class="text-center">{{ $wo->pekerjaan_termasuk ?? '-' }}</td>
                                <td class="text-center">{{ $wo->pekerjaan_tidak_termasuk ?? '-' }}</td>
                                <td class="text-center">{{ $wo->garansi ?? '-' }}</td>
                                <td>
                                    Rp {{ number_format($wo->total ?? 0, 0, ',', '.') }}
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        {{-- EXPORT PDF --}}
                                        <a href="{{ route('page.workorder.exportpdf', $wo->id) }}"
                                            class="btn btn-danger btn-sm">
                                            PDF
                                        </a>

                                        <a href="{{ route('page.workorder.edit', $wo->id) }}"
                                            class="btn btn-warning btn-sm">Edit</a>
                                        <form class="form-soft-delete"
                                            action="{{ route('page.workorder.destroy', $wo->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    Tidak ada data Workorder
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

        <div class="mt-3 d-flex justify-content-between align-items-center">
            <small class="text-muted">
                Menampilkan {{ $workorders->firstItem() ?? 0 }}
                - {{ $workorders->lastItem() ?? 0 }}
                dari {{ $workorders->total() }}
                data
            </small>


            @if ($workorders->hasPages())
                <div class="mt-2">
                    {{ $workorders->links() }}
                </div>
            @endif

            {{-- TOTAL HARGA DI TENGAH --}}
            <div class="mb-3 text-center">
                <div class="fw-bold fs-5">
                    Total Harga :
                    <span class="text-success">
                        Rp {{ number_format($totalHarga, 0, ',', '.') }}
                    </span>
                </div>
            </div>

        </div>



        {{-- MODAL DETAIL WORKORDER --}}
        <div class="modal fade" id="woDetailModal" tabindex="-1" aria-labelledby="woDetailModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="woDetailModalLabel">Detail Workorder</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div id="wo-detail-content">
                            <div class="text-center text-muted">Loading...</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>


    @endsection

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                /* ======================
                   1️⃣ FIX WO NO
                ====================== */
                document.querySelectorAll('.show-wo-detail').forEach(function(el) {

                    el.style.whiteSpace = 'nowrap';
                    el.style.wordBreak = 'keep-all';

                    let text = el.innerText.trim();
                    text = text.replace(/\s+/g, '');
                    el.innerText = text;

                    el.closest('td').style.minWidth = '150px';
                });


                /* ======================
                   2️⃣ FIX TANGGAL (PO & WO DATE)
                ====================== */
                document.querySelectorAll('td').forEach(function(td) {

                    let text = td.innerText.trim();

                    // Detect format tanggal 00-00-0000
                    if (/^\d{2}-\d{2}-\d{4}$/.test(text)) {

                        td.style.whiteSpace = 'nowrap';
                        td.style.wordBreak = 'keep-all';
                        td.style.minWidth = '120px';

                        td.innerText = text.replace(/\s+/g, '');
                    }
                });


                /* ======================
                   3️⃣ FIX KOLOM PEKERJAAN (TEXT PANJANG)
                ====================== */
                document.querySelectorAll('td').forEach(function(td) {

                    let text = td.innerText.trim();

                    // Kalau teks panjang dan bukan angka / bukan tanggal
                    if (text.length > 20 && !/^\d+$/.test(text)) {

                        td.style.minWidth = '200px';
                        td.style.whiteSpace = 'normal';
                    }
                });

            });

            document.addEventListener('DOMContentLoaded', function() {
                const wrapper = document.querySelector('.wo-scroll-wrapper');

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
                const modal = document.getElementById('woDetailModal');
                const content = document.getElementById('wo-detail-content');

                modal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const id = button.getAttribute('data-id');

                    content.innerHTML = '<div class="text-center text-muted">Loading...</div>';

                    fetch(`/workorder/${id}/detail`)
                        .then(response => response.json())
                        .then(data => {
                            content.innerHTML = data.html;
                        })
                        .catch(err => {
                            content.innerHTML = '<div class="text-danger">Gagal memuat data!</div>';
                        });
                });
            });
        </script>
    @endpush
