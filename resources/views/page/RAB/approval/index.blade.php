@extends('kerangka.master')
@section('title', 'Approval Pengajuan RAB')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="fw-bold mb-4">
            <span class="text-muted fw-light">DANA /</span> Approval Pengajuan
        </h4>

        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <a href="{{ route('page.RAB.approval.index') }}"
                        class="btn btn-outline-secondary btn-sm {{ request('status_ppn') == null ? 'active' : '' }}">
                        Semua
                    </a>

                    <a href="{{ route('page.RAB.approval.index', ['status_ppn' => 'ppn']) }}"
                        class="btn btn-outline-primary btn-sm {{ request('status_ppn') == 'ppn' ? 'active' : '' }}">
                        PPN
                    </a>

                    <a href="{{ route('page.RAB.approval.index', ['status_ppn' => 'non_ppn']) }}"
                        class="btn btn-outline-warning btn-sm {{ request('status_ppn') == 'non_ppn' ? 'active' : '' }}">
                        Non PPN
                    </a>
                </div>

                <form id="formImport" action="{{ route('page.RAB.approval.import') }}" method="POST">
                    @csrf

                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <button type="submit" id="btnImport" class="btn btn-primary btn-sm">
                                <i class="bx bx-import me-1"></i> Import ke Pengajuan
                            </button>
                            {{-- <button type="submit" class="btn btn-primary btn-sm"
                                onclick="return confirm('Import data yang dipilih ke Pengajuan?')">
                                <i class="bx bx-import me-1"></i> Import ke Pengajuan
                            </button> --}}
                        </div>
                        <div class="text-muted small">
                            Centang data yang akan di-import
                        </div>
                    </div>

                    <div class="table-responsive position-relative rab-table-wrapper">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th width="30">
                                        <input type="checkbox" id="checkAll">
                                    </th>
                                    <th>No</th>
                                    <th>No Pengajuan DANA</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Nama Pengajuan</th>
                                    <th>No. WO</th>
                                    <th>Nama Barang</th>
                                    <th>Qty</th>
                                    <th>Unit</th>
                                    <th>Nominal</th>
                                    <th>Nama Toko</th>
                                    <th>Status PPN</th>
                                    <th>Type RAB</th>
                                    <th>File Keterangan</th>
                                    <th>Invoice</th>
                                    <th>Ket.Partial</th>
                                    <th>Status</th>
                                    <th class="text-center sticky-actions" style="min-width: 150px;">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($rabpengajuans as $rabpengajuan)
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" name="selected_ids[]" value="{{ $rabpengajuan->id }}"
                                                {{ $rabpengajuan->is_imported ? 'disabled' : '' }}>
                                        </td>
                                        <td class="text-center">{{ $rabpengajuans->firstItem() + $loop->index }}</td>
                                        <td>{{ $rabpengajuan->kode_rab }}</td>
                                        <td>{{ $rabpengajuan->created_at->format('d M Y') }}</td>
                                        <td>{{ optional($rabpengajuan->user)->name ?? '-' }}</td>
                                        <td>{{ $rabpengajuan->workorder->kode_wo ?? ($rabpengajuan->no_wo ?? '-') }}
                                        </td>
                                        <td>{{ $rabpengajuan->nama_barang }}</td>
                                        <td class="text-center">{{ $rabpengajuan->sisa_qty }}</td>
                                        <td>{{ $rabpengajuan->unit }}</td>
                                        <td>Rp {{ number_format($rabpengajuan->total, 0, ',', '.') }}</td>
                                        <td>{{ $rabpengajuan->nama_toko }}</td>
                                        <td>
                                            <span
                                                class="badge {{ $rabpengajuan->status_ppn == 'ppn' ? 'bg-success' : 'bg-danger' }}">
                                                {{ $rabpengajuan->status_ppn == 'ppn' ? 'PPN' : 'Non PPN' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge
                                {{ $rabpengajuan->tipe_pengajuan == 'direct'
                                    ? 'bg-primary'
                                    : ($rabpengajuan->tipe_pengajuan == 'luarrab'
                                        ? 'bg-danger'
                                        : 'bg-warning') }}">
                                                {{ ucfirst($rabpengajuan->tipe_pengajuan) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if ($rabpengajuan->file_keterangan)
                                                <a href="{{ asset('storage/' . $rabpengajuan->file_keterangan) }}"
                                                    target="_blank" class="btn btn-sm btn-info">
                                                    Lihat <i class="bi bi-eye"></i>
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($rabpengajuan->invoice_file)
                                                <a href="{{ asset('storage/' . $rabpengajuan->invoice_file) }}"
                                                    target="_blank" class="btn btn-sm btn-primary">
                                                    Lihat <i class="bi bi-eye"></i>
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($rabpengajuan->keterangan_partial)
                                                {{ $rabpengajuan->keterangan_partial }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $rabpengajuan->status_pengajuan_color }}">
                                                {{ $rabpengajuan->status_pengajuan_label }}
                                            </span>
                                        </td>
                                        <td class="text-center sticky-actions">
                                            <a href="{{ route('page.RAB.approval.payment', $rabpengajuan->id) }}"
                                                class="btn btn-success btn-sm px-2 py-1 mb-1">
                                                <i class="bx bx-credit-card me-1"></i>Approve
                                            </a>
                                            <a href="{{ route('page.RAB.approval.partial', $rabpengajuan->id) }}"
                                                class="btn btn-info btn-sm px-2 py-1 mb-1">
                                                <i class="bx bx-edit-alt me-1"></i>Partial
                                            </a>
                                            <a href="{{ route('page.RAB.approval.reject', $rabpengajuan->id) }}"
                                                class="btn btn-danger btn-sm px-2 py-1 mb-1">
                                                <i class="bx bx-x me-1"></i>Reject
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            @if ($rabpengajuan->is_imported)
                                                <span class="badge bg-success">
                                                    Sudah Masuk Pengajuan
                                                </span>
                                            @else
                                                <span class="badge bg-warning">
                                                    Belum Di Import
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="17" class="text-center text-muted">Tidak ada pengajuan menunggu
                                            approval</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
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

    {{-- CHECK ALL SCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const form = document.getElementById('formImport');
            const btn = document.getElementById('btnImport');
            const checkAll = document.getElementById('checkAll');
            const wrapper = document.querySelector('.rab-table-wrapper');
            const table = wrapper.querySelector('table');

            const checkboxes = document.querySelectorAll('input[name="selected_ids[]"]');

            // =========================
            // BUTTON STATE
            // =========================
            function updateButtonState() {
                const checked = document.querySelectorAll('input[name="selected_ids[]"]:checked');
                btn.disabled = checked.length === 0;
            }

            // =========================
            // SELECT ALL
            // =========================
            checkAll.addEventListener('change', function() {
                checkboxes.forEach(cb => {
                    if (!cb.disabled) {
                        cb.checked = this.checked;
                    }
                });
                updateButtonState();
            });

            // =========================
            // INDIVIDUAL CHECKBOX
            // =========================
            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateButtonState);
            });

            // =========================
            // VALIDASI SUBMIT
            // =========================
            form.addEventListener('submit', function(e) {

                const checked = document.querySelectorAll('input[name="selected_ids[]"]:checked');

                if (checked.length === 0) {
                    e.preventDefault();
                    alert('Silakan checklist minimal 1 data sebelum import.');
                    return;
                }

                if (!confirm('Import data yang dipilih ke Pengajuan?')) {
                    e.preventDefault();
                }
            });

            // =========================
            // AUTO WIDTH KOLOM
            // =========================
            table.querySelectorAll('th, td').forEach(cell => {
                cell.style.whiteSpace = 'nowrap';
                cell.style.verticalAlign = 'middle';
            });

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

            // =========================
            // HORIZONTAL SCROLL
            // =========================
            wrapper.addEventListener('wheel', function(e) {
                if (e.deltaY !== 0) {
                    e.preventDefault();
                    wrapper.scrollLeft += e.deltaY;
                }
            });

            // Init
            updateButtonState();
        });
    </script>

@endsection
