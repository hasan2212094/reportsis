{{-- @extends('kerangka.master')
@section('title', 'Luar RAB Actual')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tables /</span>Luar RAB Actual</h4>

        <!-- Basic Bootstrap Table -->
        @php
            $no = 1;
        @endphp
        @include('components.alert')
        <div class="card">
            <div class="card-header d-flex justify-content-end">
                <a href="{{ route('page.luarrab.create') }}" class="btn btn-primary rounded-pill"
                    style="margin-right:10px;">Tambah
                    Data</a>
                <a href="{{ route('page.luarrab.export') }}" class="btn btn-success rounded-pill">Export Excel</a>
            </div>
            <div class="table-responsive text-nowrap">
                <table id="example" class="table tabel-hover">
                    <thead>
                        <tr>
                            <th>Item</th>
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
                        @foreach ($data as $index => $luarrab)
                            <tr>
                                <td>{{ $luarrab->luarrabps_id }}</td>
                                <td>{{ $luarrab->Needed_by }}</td>
                                <td>{{ $luarrab->Qty }}</td>
                                <td>{{ $luarrab->Unit }}</td>
                                <td>{{ \Carbon\Carbon::parse($luarrab->Date_actual)->format('d-m-Y') }}</td>
                                <td>{{ $luarrab->Toko }}</td>
                                <td>
                                    @if ($luarrab->Transaksi == 0)
                                        <span class="badge bg-success">Cash</span>
                                    @elseif ($luarrab->Transaksi == 1)
                                        <span class="badge bg-primary">Transfer</span>
                                    @else
                                        <span class="badge bg-secondary">Lainnya</span>
                                    @endif
                                </td>
                                <td>{{ $luarrab->Total }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ route('page.luarrab.edit', $luarrab->id) }}"
                                            class="btn btn-warning btn-sm">Edit</a>

                                        <form action="{{ route('page.luarrab.destroy', $luarrab->id) }}" method="POST"
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
@section('title', 'Luar RAB Actual')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Tables /</span> Luar RAB Actual
        </h4>

        @include('components.alert')

        {{-- FILTER & ACTION --}}
        <div class="card p-3 mb-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                <form method="GET" action="{{ route('page.luarrab.index') }}" class="d-flex gap-2 align-items-center mb-0">
                    <label class="mb-0">Periode</label>
                    <input type="date" name="start_date" class="form-control form-control-sm"
                        value="{{ request('start_date') }}">
                    <input type="date" name="end_date" class="form-control form-control-sm"
                        value="{{ request('end_date') }}">
                    <button class="btn btn-sm btn-primary">Filter</button>
                    <a href="{{ route('page.luarrab.index') }}" class="btn btn-sm btn-secondary">Reset</a>
                </form>

                <div class="d-flex gap-2">
                    <a href="{{ route('page.luarrab.create') }}" class="btn btn-primary rounded-pill">
                        Tambah Data
                    </a>
                    <a href="{{ route('page.luarrab.export', request()->only('start_date', 'end_date')) }}"
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
                                    <th>ID Item</th>
                                    <th>Item</th>
                                    <th>Kebutuhan</th>
                                    <th>Qty</th>
                                    <th>Satuan</th>
                                    <th>Tanggal</th>
                                    <th>Toko</th>
                                    <th>Transaksi</th>
                                    <th>Total</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $luarrab)
                                    <tr>
                                        <td>{{ $luarrab->luarrabps_id }}</td>
                                        <td>{{ $luarrab->Item }}</td>
                                        <td>{{ $luarrab->workorder->kode_wo ?? ($luarrab->Needed_by ?? '-') }}</td>
                                        <td>{{ $luarrab->Qty }}</td>
                                        <td>{{ $luarrab->Unit }}</td>
                                        <td>{{ \Carbon\Carbon::parse($luarrab->Date_actual)->format('d-m-Y') }}</td>
                                        <td>{{ $luarrab->Toko }}</td>
                                        <td>
                                            @if ($luarrab->Transaksi == 0)
                                                <span class="badge bg-success">Cash</span>
                                            @elseif ($luarrab->Transaksi == 1)
                                                <span class="badge bg-primary">Transfer</span>
                                            @else
                                                <span class="badge bg-secondary">Lainnya</span>
                                            @endif
                                        </td>
                                        <td>Rp {{ number_format($luarrab->Total, 0, ',', '.') }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                @php
                                                    $selisihHari = \Carbon\Carbon::parse(
                                                        $luarrab->Date_actual,
                                                    )->diffInDays(now());
                                                @endphp

                                                @if ($selisihHari <= 14)
                                                    <a href="{{ route('page.luarrab.edit', $luarrab->id) }}"
                                                        class="btn btn-warning btn-sm">Edit</a>
                                                @else
                                                    <span class="badge bg-secondary">Edit Expired</span>
                                                @endif

                                                <form class="form-soft-delete"
                                                    action="{{ route('page.luarrab.destroy', $luarrab->id) }}"
                                                    method="POST">
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
                    @foreach ($data as $luarrab)
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body">

                                <div class="d-flex justify-content-between">
                                    <h6 class="fw-bold">{{ $luarrab->Item }}</h6>
                                    <span class="badge bg-info">
                                        ID {{ $luarrab->luarrabps_id }}
                                    </span>
                                </div>

                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($luarrab->Date_actual)->format('d-m-Y') }}
                                </small>

                                <hr class="my-2">

                                <div class="row small g-2">
                                    <div class="col-6">
                                        <strong>Kebutuhan</strong><br>
                                        {{ $luarrab->workorder->kode_wo ?? ($luarrab->Needed_by ?? '-') }}
                                    </div>
                                    <div class="col-6">
                                        <strong>Qty</strong><br>
                                        {{ $luarrab->Qty }} {{ $luarrab->Unit }}
                                    </div>

                                    <div class="col-6">
                                        <strong>Toko</strong><br>
                                        {{ $luarrab->Toko }}
                                    </div>
                                    <div class="col-6">
                                        <strong>Transaksi</strong><br>
                                        @if ($luarrab->Transaksi == 0)
                                            <span class="badge bg-success">Cash</span>
                                        @elseif ($luarrab->Transaksi == 1)
                                            <span class="badge bg-primary">Transfer</span>
                                        @else
                                            <span class="badge bg-secondary">Lainnya</span>
                                        @endif
                                    </div>

                                    <div class="col-12">
                                        <strong>Total</strong><br>
                                        <span class="fw-bold text-primary">
                                            Rp {{ number_format($luarrab->Total, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>

                                <hr>

                                <div class="d-flex gap-2">
                                    @if (\Carbon\Carbon::parse($luarrab->Date_actual)->diffInDays(now()) <= 14)
                                        <a href="{{ route('page.luarrab.edit', $luarrab->id) }}"
                                            class="btn btn-warning btn-sm w-100">
                                            Edit
                                        </a>
                                    @else
                                        <span class="badge bg-secondary w-100 text-center">
                                            Edit Expired
                                        </span>
                                    @endif

                                    <form class="form-soft-delete w-100"
                                        action="{{ route('page.luarrab.destroy', $luarrab->id) }}" method="POST">
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

            {{-- ================= TAB TERHAPUS ================= --}}
            <div class="tab-pane fade" id="hapus">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Item</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($trashed as $luarrab)
                                <tr>
                                    <td>{{ $luarrab->luarrabps_id }}</td>
                                    <td>{{ $luarrab->Item }}</td>
                                    <td>Rp {{ number_format($luarrab->Total, 0, ',', '.') }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <form class="form-restore"
                                                action="{{ route('page.luarrab.restore', $luarrab->id) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-success btn-sm">Restore</button>
                                            </form>
                                            <form class="form-force-delete"
                                                action="{{ route('page.luarrab.forceDelete', $luarrab->id) }}"
                                                method="POST">
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
                                    <td colspan="4" class="text-center">Tidak ada data terhapus</td>
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
