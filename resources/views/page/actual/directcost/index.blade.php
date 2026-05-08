{{-- @extends('kerangka.master')
@section('title', 'Direct Cost Actual')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tables /</span> Direct cost Actual</h4>

        <!-- Basic Bootstrap Table -->
        @php
            $no = 1;
        @endphp
        <div class="card">
            <div class="card-header d-flex justify-content-end">
                <a href="{{ url('/directa/create') }}" class="btn btn-primary rounded-pill" style="margin-right:10px;">Tambah
                    Data</a>
                <a href="{{ route('directa.export') }}" class="btn btn-success rounded-pill">Export Excel</a>
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
                        @foreach ($data as $index => $directcost)
                            <tr>
                                <td>{{ $directcost->direct_ps->Item }}</td>
                                <td>{{ $directcost->direct_ps->Qty }}</td>
                                <td>{{ $directcost->direct_ps->Unit }}</td>
                                <td>{{ $directcost->direct_ps->Needed_by }}</td>
                                <td>{{ $directcost->Qty }}</td>
                                <td>{{ $directcost->Unit }}</td>
                                <td>{{ \Carbon\Carbon::parse($directcost->Date_actual)->format('d-m-Y') }}</td>
                                <td>{{ $directcost->Toko }}</td>
                                <td>
                                    @if ($directcost->Transaksi == 0)
                                        <span class="badge bg-success">Cash</span>
                                    @elseif ($directcost->Transaksi == 1)
                                        <span class="badge bg-primary">Transfer</span>
                                    @else
                                        <span class="badge bg-secondary">Lainnya</span>
                                    @endif
                                </td>
                                <td>{{ $directcost->Total }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ route('page.directa.edit', $directcost->id) }}"
                                            class="btn btn-warning btn-sm">Edit</a>

                                        <form action="{{ route('page.directa.destroy', $directcost->id) }}" method="POST"
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
@section('title', 'Direct Cost Actual')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="fw-bold mb-3">
            <span class="text-muted fw-light">Tables /</span> Direct Cost Actual
        </h4>

        @include('components.alert')

        {{-- FILTER & ACTION --}}
        <div class="card p-3 mb-3 shadow-sm">
            <div class="row g-3 align-items-end">

                <div class="col-12 col-lg-6">
                    <form method="GET" action="{{ route('page.directa.index') }}" class="row g-2">
                        <div class="col-6 col-md-4">
                            <input type="date" name="start_date" class="form-control form-control-sm"
                                value="{{ request('start_date') }}">
                        </div>
                        <div class="col-6 col-md-4">
                            <input type="date" name="end_date" class="form-control form-control-sm"
                                value="{{ request('end_date') }}">
                        </div>
                        <div class="col-6 col-md-2">
                            <button class="btn btn-primary btn-sm w-100">Filter</button>
                        </div>
                        <div class="col-6 col-md-2">
                            <a href="{{ route('page.directa.index') }}" class="btn btn-secondary btn-sm w-100">Reset</a>
                        </div>
                    </form>
                </div>

                <div class="col-12 col-lg-6 text-lg-end">
                    <div class="d-flex gap-2 justify-content-lg-end">
                        <a href="{{ route('page.directa.create') }}" class="btn btn-primary btn-sm rounded-pill">+
                            Tambah</a>

                        <a href="{{ route('directa.export', request()->only(['start_date', 'end_date'])) }}"
                            class="btn btn-success btn-sm rounded-pill">Export</a>
                    </div>
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

            {{-- ================= DATA AKTIF ================= --}}
            <div class="tab-pane fade show active" id="aktif">

                {{-- ===== DESKTOP TABLE ===== --}}
                <div class="d-none d-lg-block">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
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
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $dc)
                                    @php
                                        $expired = \Carbon\Carbon::parse($dc->Date_actual)->diffInDays(now()) > 14;
                                    @endphp
                                    <tr>
                                        <td class="fw-semibold">{{ $dc->direct_ps->Item ?? '-' }}</td>
                                        <td>{{ $dc->direct_ps->Qty ?? '-' }}</td>
                                        <td>{{ $dc->direct_ps->Unit ?? '-' }}</td>
                                        <td>
                                            {{ $dc->direct_ps->workorder->kode_wo ?? ($dc->direct_ps->Needed_by ?? '-') }}
                                        </td>
                                        <td>{{ $dc->Qty }}</td>
                                        <td>{{ $dc->Unit }}</td>
                                        <td>{{ \Carbon\Carbon::parse($dc->Date_actual)->format('d-m-Y') }}</td>
                                        <td>{{ $dc->Toko }}</td>
                                        <td>
                                            @if ($dc->Transaksi == 0)
                                                <span class="badge bg-success">Cash</span>
                                            @elseif ($dc->Transaksi == 1)
                                                <span class="badge bg-primary">Transfer</span>
                                            @else
                                                <span class="badge bg-secondary">Lainnya</span>
                                            @endif
                                        </td>
                                        <td class="fw-semibold text-success">
                                            Rp {{ number_format($dc->Total, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex gap-1 justify-content-center">
                                                @if (!$expired)
                                                    <a href="{{ route('page.directa.edit', $dc->id) }}"
                                                        class="btn btn-warning btn-sm">Edit</a>
                                                @else
                                                    <span class="badge bg-secondary">Expired</span>
                                                @endif

                                                <form class="form-soft-delete"
                                                    action="{{ route('page.directa.destroy', $dc->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
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
                <div class="d-block d-lg-none">
                    @foreach ($data as $dc)
                        @php
                            $expired = \Carbon\Carbon::parse($dc->Date_actual)->diffInDays(now()) > 14;
                        @endphp
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body">

                                <div class="d-flex justify-content-between mb-2">
                                    <div>
                                        <h6 class="fw-bold mb-0">
                                            {{ $dc->direct_ps->Item ?? '-' }}
                                        </h6>
                                        <small class="text-muted">
                                            {{ $dc->direct_ps->workorder->kode_wo ?? ($dc->direct_ps->Needed_by ?? '-') }}
                                        </small>
                                    </div>
                                    <span class="badge bg-primary">
                                        {{ \Carbon\Carbon::parse($dc->Date_actual)->format('d-m-Y') }}
                                    </span>
                                </div>

                                <div class="row small">
                                    <div class="col-6">Qty Pengajuan</div>
                                    <div class="col-6 text-end">
                                        {{ $dc->direct_ps->Qty ?? '-' }}
                                        {{ $dc->direct_ps->Unit ?? '' }}
                                    </div>

                                    <div class="col-6">Qty Actual</div>
                                    <div class="col-6 text-end">
                                        {{ $dc->Qty }} {{ $dc->Unit }}
                                    </div>

                                    <div class="col-6">Toko</div>
                                    <div class="col-6 text-end">{{ $dc->Toko }}</div>

                                    <div class="col-6">Transaksi</div>
                                    <div class="col-6 text-end">
                                        @if ($dc->Transaksi == 0)
                                            Cash
                                        @elseif ($dc->Transaksi == 1)
                                            Transfer
                                        @else
                                            Lainnya
                                        @endif
                                    </div>

                                    <div class="col-6 fw-semibold">Total</div>
                                    <div class="col-6 text-end fw-semibold text-success">
                                        Rp {{ number_format($dc->Total, 0, ',', '.') }}
                                    </div>
                                </div>

                                <div class="d-flex gap-2 mt-3">
                                    @if (!$expired)
                                        <a href="{{ route('page.directa.edit', $dc->id) }}"
                                            class="btn btn-warning btn-sm w-100">Edit</a>
                                    @else
                                        <span class="badge bg-secondary w-100 py-2 text-center">
                                            Expired
                                        </span>
                                    @endif

                                    <form class="form-soft-delete w-100"
                                        action="{{ route('page.directa.destroy', $dc->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm w-100">Hapus</button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>

            </div>

            {{-- ================= DATA TERHAPUS ================= --}}
            <div class="tab-pane fade" id="hapus">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Item</th>
                                <th>Qty Actual</th>
                                <th>Satuan</th>
                                <th>Total</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($trashed as $dc)
                                <tr>
                                    <td>{{ $dc->direct_ps->Item ?? '-' }}</td>
                                    <td>{{ $dc->Qty }}</td>
                                    <td>{{ $dc->Unit }}</td>
                                    <td>Rp {{ number_format($dc->Total, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <form class="form-restore"
                                                action="{{ route('page.directa.restore', $dc->id) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-success btn-sm">Restore</button>
                                            </form>

                                            <form class="form-force-delete"
                                                action="{{ route('page.directa.forceDelete', $dc->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm">
                                                    Hapus Permanen
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
