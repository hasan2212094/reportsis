@php
    use Carbon\Carbon;
@endphp

@extends('kerangka.master')
@section('title', 'PPN Pengajuan')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Tables /</span> PPN Pengajuan
        </h4>

        @include('components.alert')

        {{-- FILTER + ACTION --}}
        <div class="card p-3 mb-3">
            <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center">

                <form method="GET" action="{{ route('page.ppn.index') }}" class="d-flex flex-wrap gap-2 align-items-center">
                    <label class="mb-0">Periode</label>
                    <input type="date" name="start_date" class="form-control form-control-sm"
                        value="{{ request('start_date') }}">
                    <input type="date" name="end_date" class="form-control form-control-sm"
                        value="{{ request('end_date') }}">
                    <button class="btn btn-sm btn-primary">Filter</button>
                    <a href="{{ route('page.ppn.index') }}" class="btn btn-sm btn-secondary">Reset</a>
                </form>

                <div class="d-flex gap-2">
                    <a href="{{ route('page.ppn.create') }}" class="btn btn-primary rounded-pill">Tambah</a>
                    <a href="{{ route('ppn.export', request()->all()) }}" class="btn btn-success rounded-pill">Export</a>
                </div>
            </div>

            {{-- ================= IMPORT ================= --}}
            <hr>
            <form action="{{ route('ppn.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-2 align-items-center">
                    <div class="col-md-6">
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">Import</button>
                    </div>
                    <div class="col-md-4">
                        <small>
                            📄 <a href="{{ asset('template_ppn_import.xlsx') }}">Download Template</a>
                        </small>
                    </div>
                </div>
            </form>
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

                {{-- ===== DESKTOP & TABLET ===== --}}
                <div class="d-none d-md-block">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>No Faktur</th>
                                    <th>Vendor</th>
                                    <th>Tanggal</th>
                                    <th>DPP</th>
                                    <th>PPN</th>
                                    <th>Total</th>
                                    <th>Note</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                    @php $selisih = Carbon::now()->diffInDays($d->created_at); @endphp
                                    <tr>
                                        <td>{{ $d->kode_ppn }}</td>
                                        <td class="fw-semibold">{{ $d->no_faktur }}</td>
                                        <td>{{ $d->vendor }}</td>
                                        <td>{{ Carbon::parse($d->tanggal)->format('d-m-Y') }}</td>
                                        <td>Rp {{ number_format($d->dpp, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($d->ppn, 0, ',', '.') }}</td>
                                        <td class="fw-bold text-success">
                                            Rp {{ number_format($d->total, 0, ',', '.') }}
                                        </td>
                                        <td>{{ $d->note }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                @if ($selisih <= 7)
                                                    <a href="{{ route('page.ppn.edit', $d->id) }}"
                                                        class="btn btn-warning btn-sm">Edit</a>
                                                @else
                                                    <span class="badge bg-secondary">Expired</span>
                                                @endif
                                                <form action="{{ route('page.ppn.destroy', $d->id) }}" method="POST">
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
                    @foreach ($data as $d)
                        @php $selisih = Carbon::now()->diffInDays($d->created_at); @endphp
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body">

                                <div class="d-flex justify-content-between">
                                    <h6 class="fw-bold">{{ $d->no_faktur }}</h6>
                                    <span class="badge bg-primary">
                                        {{ Carbon::parse($d->tanggal)->format('d-m-Y') }}
                                    </span>
                                </div>

                                <small class="text-muted">{{ $d->vendor }}</small>
                                <hr>

                                <div class="small">
                                    <div class="d-flex justify-content-between">
                                        <span>DPP</span>
                                        <span>Rp {{ number_format($d->dpp, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>PPN</span>
                                        <span>Rp {{ number_format($d->ppn, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between fw-bold">
                                        <span>Total</span>
                                        <span class="text-success">
                                            Rp {{ number_format($d->total, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="d-flex gap-2 mt-3">
                                    @if ($selisih <= 7)
                                        <a href="{{ route('page.ppn.edit', $d->id) }}"
                                            class="btn btn-warning btn-sm w-100">Edit</a>
                                    @else
                                        <span class="badge bg-secondary w-100 py-2 text-center">
                                            Edit Expired
                                        </span>
                                    @endif

                                    <form class="w-100" action="{{ route('page.ppn.destroy', $d->id) }}" method="POST">
                                        @csrf @method('DELETE')
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
                                <th>No Faktur</th>
                                <th>Vendor</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($trashed as $d)
                                <tr>
                                    <td>{{ $d->no_faktur }}</td>
                                    <td>{{ $d->vendor }}</td>
                                    <td>Rp {{ number_format($d->total, 0, ',', '.') }}</td>
                                    <td>
                                        <form action="{{ route('ppn.restore', $d->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-success btn-sm">Restore</button>
                                        </form>
                                        <form action="{{ route('ppn.forceDelete', $d->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm">Hapus Permanen</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
