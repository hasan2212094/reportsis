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
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Tables /</span> Direct Cost Actual
        </h4>

        @include('components.alert')

        {{-- Filter dan Tombol Aksi --}}
        <div class="card p-3 mb-3">
            <div class="d-flex align-items-center justify-content-between">
                {{-- Filter Periode --}}
                <form method="GET" action="{{ route('page.directa.index') }}" class="d-flex gap-2 align-items-center mb-0">
                    <label for="start_date" class="mb-0">Periode:</label>
                    <input type="date" name="start_date" id="start_date" class="form-control form-control-sm"
                        value="{{ request('start_date') }}">
                    <input type="date" name="end_date" id="end_date" class="form-control form-control-sm"
                        value="{{ request('end_date') }}">
                    <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                    <a href="{{ route('page.directa.index') }}" class="btn btn-sm btn-secondary">Reset</a>
                </form>

                {{-- Tombol Tambah & Export --}}
                <div class="d-flex gap-2">
                    <a href="{{ route('page.directa.create') }}" class="btn btn-primary rounded-pill">Tambah data</a>
                    <a href="{{ route('directa.export', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                        class="btn btn-success rounded-pill">Export Excel</a>
                </div>
            </div>
        </div>

        {{-- Tab Aktif / Terhapus --}}
        <ul class="nav nav-tabs mb-3" id="dataTab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="aktif-tab" data-bs-toggle="tab" data-bs-target="#aktif"
                    type="button">Data Aktif</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="hapus-tab" data-bs-toggle="tab" data-bs-target="#hapus" type="button">Data
                    Terhapus</button>
            </li>
        </ul>

        <div class="tab-content">
            {{-- TAB 1: DATA AKTIF --}}
            <div class="tab-pane fade show active" id="aktif" role="tabpanel">
                <div class="table-responsive text-nowrap">
                    <table id="example" class="table table-hover">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qty Pengajuan</th>
                                <th>Satuan</th>
                                <th>Kebutuhan</th>
                                <th>Qty Actual</th>
                                <th>Satuan</th>
                                <th>Tanggal Actual</th>
                                <th>Toko</th>
                                <th>Transaksi</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $directcost)
                                <tr>
                                    <td>{{ $directcost->direct_ps->Item ?? '-' }}</td>
                                    <td>{{ $directcost->direct_ps->Qty ?? '-' }}</td>
                                    <td>{{ $directcost->direct_ps->Unit ?? '-' }}</td>
                                    <td>
                                        @if ($directcost->direct_ps)
                                            @if ($directcost->direct_ps->workorder)
                                                <span>{{ $directcost->direct_ps->workorder->kode_wo }}</span>
                                            @elseif ($directcost->direct_ps->Needed_by)
                                                {{ $directcost->direct_ps->Needed_by }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
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
                                    <td>Rp {{ number_format($directcost->Total, 0, ',', '.') }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @php
                                                $selisihHari = \Carbon\Carbon::parse(
                                                    $directcost->Date_actual,
                                                )->diffInDays(now());
                                            @endphp
                                            @if ($selisihHari <= 14)
                                                <a href="{{ route('page.directa.edit', $directcost->id) }}"
                                                    class="btn btn-warning btn-sm">Edit</a>
                                            @else
                                                <span class="badge bg-secondary">Edit Expired</span>
                                            @endif

                                            <form class="form-soft-delete"
                                                action="{{ route('page.directa.destroy', $directcost->id) }}"
                                                method="POST" data-id="{{ $directcost->id }}">
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

            {{-- TAB 2: DATA TERHAPUS --}}
            <div class="tab-pane fade" id="hapus" role="tabpanel">
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qty Pengajuan</th>
                                <th>Satuan</th>
                                <th>Kebutuhan</th>
                                <th>Qty Actual</th>
                                <th>Satuan</th>
                                <th>Tanggal Actual</th>
                                <th>Toko</th>
                                <th>Transaksi</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-trashed">
                            @forelse ($trashed as $directcost)
                                <tr>
                                    <td>{{ $directcost->direct_ps->Item ?? '-' }}</td>
                                    <td>{{ $directcost->direct_ps->Qty ?? '-' }}</td>
                                    <td>{{ $directcost->direct_ps->Unit ?? '-' }}</td>
                                    <td>
                                        @if ($directcost->direct_ps)
                                            @if ($directcost->direct_ps->workorder)
                                                <span>{{ $directcost->direct_ps->workorder->kode_wo }}</span>
                                            @elseif ($directcost->direct_ps->Needed_by)
                                                {{ $directcost->direct_ps->Needed_by }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
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
                                    <td>Rp {{ number_format($directcost->Total, 0, ',', '.') }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <form class="form-restore"
                                                action="{{ route('page.directa.restore', $directcost->id) }}"
                                                method="POST" data-id="{{ $directcost->id }}">
                                                @csrf
                                                <button class="btn btn-success btn-sm">Restore</button>
                                            </form>
                                            <form class="form-force-delete"
                                                action="{{ route('page.directa.forceDelete', $directcost->id) }}"
                                                method="POST" data-id="{{ $directcost->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus
                                                    Permanen</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data terhapus</td>
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
            $('#example').DataTable();

            // === Soft Delete ===
            $(document).on('submit', '.form-soft-delete', function(e) {
                e.preventDefault();
                if (!confirm('Yakin mau hapus data ini (soft delete)?')) return;

                let form = $(this);
                $.ajax({
                    url: form.attr('action'),
                    type: 'DELETE',
                    data: form.serialize(),
                    success: function(res) {
                        if (res.status === 'success') {
                            alert(res.message);
                            location.reload(); // ✅ auto refresh
                        } else {
                            alert('Gagal hapus data.');
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan.');
                    }
                });
            });

            // === Restore ===
            $(document).on('submit', '.form-restore', function(e) {
                e.preventDefault();
                let form = $(this);

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    success: function(res) {
                        if (res.status === 'success') {
                            alert(res.message);
                            location.reload(); // ✅ auto refresh
                        } else {
                            alert('Gagal restore data.');
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan saat restore.');
                    }
                });
            });

            // === Force Delete ===
            $(document).on('submit', '.form-force-delete', function(e) {
                e.preventDefault();
                if (!confirm('Yakin hapus permanen data ini?')) return;

                let form = $(this);
                $.ajax({
                    url: form.attr('action'),
                    type: 'DELETE',
                    data: form.serialize(),
                    success: function(res) {
                        if (res.status === 'success') {
                            alert(res.message);
                            location.reload(); // ✅ auto refresh
                        } else {
                            alert('Gagal hapus permanen.');
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan saat hapus permanen.');
                    }
                });
            });
        });
    </script>
@endpush
