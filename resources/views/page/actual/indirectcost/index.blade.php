@extends('kerangka.master')

@section('title', 'Indirect Cost Actual')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Tables /</span> Indirect Cost Actual
        </h4>

        @include('components.alert')

        {{-- FILTER --}}
        <div class="card p-3 mb-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                <form method="GET" action="{{ route('page.indirecta.index') }}" class="d-flex gap-2 align-items-center mb-0">
                    <label class="mb-0">Periode</label>
                    <input type="date" name="start_date" class="form-control form-control-sm"
                        value="{{ request('start_date') }}">
                    <input type="date" name="end_date" class="form-control form-control-sm"
                        value="{{ request('end_date') }}">
                    <button class="btn btn-sm btn-primary">Filter</button>
                    <a href="{{ route('page.indirecta.index') }}" class="btn btn-sm btn-secondary">Reset</a>
                </form>

                <div class="d-flex gap-2">
                    <a href="{{ route('page.indirecta.create') }}" class="btn btn-primary rounded-pill">
                        Tambah Data
                    </a>
                    <a href="{{ route('page.indirecta.export', request()->only('start_date', 'end_date')) }}"
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
                                @foreach ($data as $row)
                                    <tr>
                                        <td>{{ $row->indirectp->Item ?? '-' }}</td>
                                        <td>{{ $row->indirectp->Qty ?? '-' }}</td>
                                        <td>{{ $row->indirectp->Unit ?? '-' }}</td>
                                        <td>{{ $row->indirectp->Needed_by ?? '-' }}</td>
                                        <td>{{ $row->Qty }}</td>
                                        <td>{{ $row->Unit }}</td>
                                        <td>{{ \Carbon\Carbon::parse($row->Date_actual)->format('d-m-Y') }}</td>
                                        <td>{{ $row->Toko }}</td>
                                        <td>
                                            @if ($row->Transaksi == 0)
                                                <span class="badge bg-success">Cash</span>
                                            @elseif ($row->Transaksi == 1)
                                                <span class="badge bg-primary">Transfer</span>
                                            @else
                                                <span class="badge bg-secondary">Lainnya</span>
                                            @endif
                                        </td>
                                        <td>
                                            Rp {{ number_format($row->Total, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('page.indirecta.edit', $row->id) }}"
                                                    class="btn btn-warning btn-sm">
                                                    Edit
                                                </a>
                                                <form class="form-soft-delete"
                                                    action="{{ route('page.indirecta.destroy', $row->id) }}" method="POST">
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
                    @foreach ($data as $row)
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body">

                                <h6 class="fw-bold mb-1">
                                    {{ $row->indirectp->Item ?? '-' }}
                                </h6>

                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($row->Date_actual)->format('d-m-Y') }}
                                </small>

                                <hr class="my-2">

                                <div class="row small g-2">
                                    <div class="col-6">
                                        <strong>Qty Ajukan</strong><br>
                                        {{ $row->indirectp->Qty ?? '-' }}
                                    </div>
                                    <div class="col-6">
                                        <strong>Qty Actual</strong><br>
                                        {{ $row->Qty }}
                                    </div>

                                    <div class="col-6">
                                        <strong>Toko</strong><br>
                                        {{ $row->Toko }}
                                    </div>
                                    <div class="col-6">
                                        <strong>Transaksi</strong><br>
                                        @if ($row->Transaksi == 0)
                                            <span class="badge bg-success">Cash</span>
                                        @elseif ($row->Transaksi == 1)
                                            <span class="badge bg-primary">Transfer</span>
                                        @else
                                            <span class="badge bg-secondary">Lainnya</span>
                                        @endif
                                    </div>

                                    <div class="col-12">
                                        <strong>Total</strong><br>
                                        <span class="fw-bold text-primary">
                                            Rp {{ number_format($row->Total, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>

                                <hr>

                                <div class="d-flex gap-2">
                                    <a href="{{ route('page.indirecta.edit', $row->id) }}"
                                        class="btn btn-warning btn-sm w-100">
                                        Edit
                                    </a>
                                    <form class="form-soft-delete w-100"
                                        action="{{ route('page.indirecta.destroy', $row->id) }}" method="POST">
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
                            @forelse ($trashed as $row)
                                <tr>
                                    <td>{{ $row->indirectp->Item ?? '-' }}</td>
                                    <td>Rp {{ number_format($row->Total, 0, ',', '.') }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <form class="form-restore"
                                                action="{{ route('page.indirecta.restore', $row->id) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-success btn-sm">Restore</button>
                                            </form>
                                            <form class="form-force-delete"
                                                action="{{ route('page.indirecta.forceDelete', $row->id) }}" method="POST">
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
