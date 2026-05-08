{{-- @extends('kerangka.master')
@section('title', 'PPN Actual')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tables /</span>PPN Actual</h4>

        <!-- Basic Bootstrap Table -->
        @php
            $no = 1;
        @endphp
        @include('components.alert')
        <div class="card">
            <div class="card-header d-flex justify-content-end">
                <a href="{{ route('page.ppna.create') }}" class="btn btn-primary rounded-pill"
                    style="margin-right:10px;">Tambah
                    Data</a>
                <a href="{{ route('page.ppna.export') }}" class="btn btn-success rounded-pill">Export Excel</a>
            </div>
            <div class="table-responsive text-nowrap">
                <table id="example" class="table tabel-hover">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Qty Pengajuan</th>
                            <th>satuan</th>
                            <th>Kebutuhan</th>
                            <th>Qty Actual</th>
                            <th>satuan</th>
                            <th>Tanggal Actual</th>
                            <th>Toko</th>
                            <th>Transaksi</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $index => $ppna)
                            <tr>
                                <td>{{ $ppna->ppn->Item }}</td>
                                <td>{{ $ppna->ppn->Qty }}</td>
                                <td>{{ $ppna->ppn->Unit }}</td>
                                <td>{{ $ppna->ppn->Needed_by }}</td>
                                <td>{{ $ppna->Qty }}</td>
                                <td>{{ $ppna->Unit }}</td>
                                <td>{{ \Carbon\Carbon::parse($ppna->Date_actual)->format('d-m-Y') }}</td>
                                <td>{{ $ppna->Toko }}</td>
                                <td>
                                    @if ($ppna->Transaksi == 0)
                                        <span class="badge bg-success">Cash</span>
                                    @elseif ($ppna->Transaksi == 1)
                                        <span class="badge bg-primary">Transfer</span>
                                    @else
                                        <span class="badge bg-secondary">Lainnya</span>
                                    @endif
                                </td>
                                <td>{{ $ppna->Total }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ route('page.ppna.edit', $ppna->id) }}"
                                            class="btn btn-warning btn-sm">Edit</a>

                                        <form action="{{ route('page.ppna.destroy', $ppna->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin mau hapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <hr class="my-5" />

    </div>
@endsection --}}
@extends('kerangka.master')

@section('title', 'PPN Actual')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Tables /</span> PPN Actual
        </h4>

        @include('components.alert')

        {{-- FILTER & ACTION --}}
        <div class="card p-3 mb-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                <form method="GET" action="{{ route('page.ppna.index') }}" class="d-flex gap-2 align-items-center mb-0">
                    <label class="mb-0">Periode</label>
                    <input type="date" name="start_date" class="form-control form-control-sm"
                        value="{{ request('start_date') }}">
                    <input type="date" name="end_date" class="form-control form-control-sm"
                        value="{{ request('end_date') }}">
                    <button class="btn btn-sm btn-primary">Filter</button>
                    <a href="{{ route('page.ppna.index') }}" class="btn btn-sm btn-secondary">Reset</a>
                </form>

                <div class="d-flex gap-2">
                    <a href="{{ route('page.ppna.create') }}" class="btn btn-primary rounded-pill">
                        Tambah Data
                    </a>
                    <a href="{{ route('page.ppna.export', request()->only('start_date', 'end_date')) }}"
                        class="btn btn-success rounded-pill">
                        Export Excel
                    </a>
                </div>
            </div>
        </div>

        {{-- TAB --}}
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#aktif">
                    Data Aktif
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#hapus">
                    Data Terhapus
                </button>
            </li>
        </ul>

        <div class="tab-content">

            {{-- ================= TAB AKTIF ================= --}}
            <div class="tab-pane fade show active" id="aktif">

                {{-- ===== DESKTOP TABLE ===== --}}
                <div class="d-none d-md-block">
                    <div class="table-responsive">
                        <table id="example" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Qty Pengajuan</th>
                                    <th>Satuan</th>
                                    <th>Kebutuhan</th>
                                    <th>Qty Actual</th>
                                    <th>Satuan</th>
                                    <th>Tanggal</th>
                                    <th>Toko</th>
                                    <th>Transaksi</th>
                                    <th>Total</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $ppna)
                                    <tr>
                                        <td>{{ $ppna->ppn->Item ?? '-' }}</td>
                                        <td>{{ $ppna->ppn->Qty ?? '-' }}</td>
                                        <td>{{ $ppna->ppn->Unit ?? '-' }}</td>
                                        <td>
                                            {{ $ppna->ppn->workorder->kode_wo ?? ($ppna->ppn->Needed_by ?? '-') }}
                                        </td>
                                        <td>{{ $ppna->Qty }}</td>
                                        <td>{{ $ppna->Unit }}</td>
                                        <td>{{ \Carbon\Carbon::parse($ppna->Date_actual)->format('d-m-Y') }}</td>
                                        <td>{{ $ppna->Toko }}</td>
                                        <td>
                                            @if ($ppna->Transaksi == 0)
                                                <span class="badge bg-success">Cash</span>
                                            @elseif ($ppna->Transaksi == 1)
                                                <span class="badge bg-primary">Transfer</span>
                                            @else
                                                <span class="badge bg-secondary">Lainnya</span>
                                            @endif
                                        </td>
                                        <td>Rp {{ number_format($ppna->Total, 0, ',', '.') }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                @php
                                                    $selisihHari = \Carbon\Carbon::parse(
                                                        $ppna->Date_actual,
                                                    )->diffInDays(now());
                                                @endphp

                                                @if ($selisihHari <= 14)
                                                    <a href="{{ route('page.ppna.edit', $ppna->id) }}"
                                                        class="btn btn-warning btn-sm">Edit</a>
                                                @else
                                                    <span class="badge bg-secondary">Edit Expired</span>
                                                @endif

                                                <form class="form-soft-delete"
                                                    action="{{ route('page.ppna.destroy', $ppna->id) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ===== MOBILE CARD ===== --}}
                <div class="d-block d-md-none">
                    @foreach ($data as $ppna)
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body">

                                <h6 class="fw-bold mb-1">
                                    {{ $ppna->ppn->Item ?? '-' }}
                                </h6>

                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($ppna->Date_actual)->format('d-m-Y') }}
                                </small>

                                <hr class="my-2">

                                <div class="row small g-2">
                                    <div class="col-6">
                                        <strong>Qty Ajukan</strong><br>
                                        {{ $ppna->ppn->Qty ?? '-' }}
                                    </div>
                                    <div class="col-6">
                                        <strong>Qty Actual</strong><br>
                                        {{ $ppna->Qty }}
                                    </div>

                                    <div class="col-6">
                                        <strong>Toko</strong><br>
                                        {{ $ppna->Toko }}
                                    </div>
                                    <div class="col-6">
                                        <strong>Transaksi</strong><br>
                                        @if ($ppna->Transaksi == 0)
                                            <span class="badge bg-success">Cash</span>
                                        @elseif ($ppna->Transaksi == 1)
                                            <span class="badge bg-primary">Transfer</span>
                                        @else
                                            <span class="badge bg-secondary">Lainnya</span>
                                        @endif
                                    </div>

                                    <div class="col-12">
                                        <strong>Total</strong><br>
                                        <span class="fw-bold text-primary">
                                            Rp {{ number_format($ppna->Total, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>

                                <hr>

                                <div class="d-flex gap-2">
                                    @if (\Carbon\Carbon::parse($ppna->Date_actual)->diffInDays(now()) <= 14)
                                        <a href="{{ route('page.ppna.edit', $ppna->id) }}"
                                            class="btn btn-warning btn-sm w-100">
                                            Edit
                                        </a>
                                    @else
                                        <span class="badge bg-secondary w-100 text-center">Edit Expired</span>
                                    @endif

                                    <form class="form-soft-delete w-100"
                                        action="{{ route('page.ppna.destroy', $ppna->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm w-100">
                                            Hapus
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>

            </div>

            {{-- ================= TAB HAPUS ================= --}}
            <div class="tab-pane fade" id="hapus">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($trashed as $ppna)
                                <tr>
                                    <td>{{ $ppna->ppn->Item ?? '-' }}</td>
                                    <td>Rp {{ number_format($ppna->Total, 0, ',', '.') }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <form class="form-restore" action="{{ route('page.ppna.restore', $ppna->id) }}"
                                                method="POST">
                                                @csrf
                                                <button class="btn btn-success btn-sm">Restore</button>
                                            </form>
                                            <form class="form-force-delete"
                                                action="{{ route('page.ppna.forceDelete', $ppna->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-danger btn-sm">
                                                    Hapus Permanen
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            // DataTable hanya desktop
            if (window.innerWidth >= 768) {
                $('#example').DataTable();
            }

            $('.form-soft-delete, .form-force-delete').on('submit', function(e) {
                e.preventDefault();
                if (!confirm('Yakin lanjut?')) return;
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).find('input[name=_method]').val() ?? 'POST',
                    data: $(this).serialize(),
                    success: () => location.reload(),
                    error: () => alert('Terjadi kesalahan')
                });
            });

            $('.form-restore').on('submit', function(e) {
                e.preventDefault();
                $.post($(this).attr('action'), $(this).serialize(), () => location.reload());
            });

        });
    </script>
@endpush
