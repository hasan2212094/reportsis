{{-- @extends('kerangka.master')
@section('title', 'Indirect Cost Actual')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tables /</span>Indirect Cost Actual</h4>

        <!-- Basic Bootstrap Table -->
        @php
            $no = 1;
        @endphp
        @include('components.alert')
        <div class="card">
            <div class="card-header d-flex justify-content-end">
                <a href="{{ route('page.indirecta.create') }}" class="btn btn-primary rounded-pill"
                    style="margin-right:10px;">Tambah
                    Data</a>
                <a href="{{ route('page.indirecta.export') }}" class="btn btn-success rounded-pill">Export Excel</a>
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
                        @foreach ($data as $index => $indirectcost)
                            <tr>
                                <td>{{ $indirectcost->indirectp->Item }}</td>
                                <td>{{ $indirectcost->indirectp->Qty }}</td>
                                <td>{{ $indirectcost->indirectp->Unit }}</td>
                                <td>{{ $indirectcost->indirectp->Needed_by }}</td>
                                <td>{{ $indirectcost->Qty }}</td>
                                <td>{{ $indirectcost->Unit }}</td>
                                <td>{{ \Carbon\Carbon::parse($indirectcost->Date_actual)->format('d-m-Y') }}</td>
                                <td>{{ $indirectcost->Toko }}</td>
                                <td>
                                    @if ($indirectcost->Transaksi == 0)
                                        <span class="badge bg-success">Cash</span>
                                    @elseif ($indirectcost->Transaksi == 1)
                                        <span class="badge bg-primary">Transfer</span>
                                    @else
                                        <span class="badge bg-secondary">Lainnya</span>
                                    @endif
                                </td>
                                <td>{{ $indirectcost->Total }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ route('page.indirecta.edit', $indirectcost->id) }}"
                                            class="btn btn-warning btn-sm">Edit</a>

                                        <form action="{{ route('page.indirecta.destroy', $indirectcost->id) }}"
                                            method="POST" onsubmit="return confirm('Yakin mau hapus data ini?');">
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
@section('title', 'Indirect Cost Actual')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Tables /</span> Indirect Cost Actual
        </h4>

        @include('components.alert')

        <div class="card p-3 mb-3">
            <div class="d-flex align-items-center justify-content-between">
                {{-- Form filter --}}
                <form method="GET" action="{{ route('page.indirecta.index') }}" class="d-flex gap-2 align-items-center mb-0">
                    <label for="start_date" class="mb-0">Periode:</label>
                    <input type="date" name="start_date" id="start_date" class="form-control form-control-sm"
                        value="{{ request('start_date') }}">
                    <input type="date" name="end_date" id="end_date" class="form-control form-control-sm"
                        value="{{ request('end_date') }}">
                    <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                    <a href="{{ route('page.indirecta.index') }}" class="btn btn-sm btn-secondary">Reset</a>
                </form>

                {{-- Tombol aksi --}}
                <div class="d-flex gap-2">
                    <a href="{{ route('page.indirecta.create') }}" class="btn btn-primary rounded-pill">Tambah data</a>
                    <a href="{{ route('page.indirecta.export', [
                        'start_date' => request('start_date'),
                        'end_date' => request('end_date'),
                    ]) }}"
                        class="btn btn-success rounded-pill">Export Excel</a>
                </div>
            </div>
        </div>

        <div class="table-responsive text-nowrap">
            <table id="example" class="table table-hover">
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
                    @foreach ($data as $index => $indirectcost)
                        <tr>
                            <td>{{ $indirectcost->indirectp->Item }}</td>
                            <td>{{ $indirectcost->indirectp->Qty }}</td>
                            <td>{{ $indirectcost->indirectp->Unit }}</td>
                            <td>{{ $indirectcost->indirectp->Needed_by }}</td>
                            <td>{{ $indirectcost->Qty }}</td>
                            <td>{{ $indirectcost->Unit }}</td>
                            <td>{{ \Carbon\Carbon::parse($indirectcost->Date_actual)->format('d-m-Y') }}</td>
                            <td>{{ $indirectcost->Toko }}</td>
                            <td>
                                @if ($indirectcost->Transaksi == 0)
                                    <span class="badge bg-success">Cash</span>
                                @elseif ($indirectcost->Transaksi == 1)
                                    <span class="badge bg-primary">Transfer</span>
                                @else
                                    <span class="badge bg-secondary">Lainnya</span>
                                @endif
                            </td>
                            <td>Rp {{ number_format($indirectcost->Total, 0, ',', '.') }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-flex align-items-center gap-2">
                                        @php
                                            $selisihHari = \Carbon\Carbon::parse(
                                                $indirectcost->Date_actual,
                                            )->diffInDays(now());
                                        @endphp

                                        @if ($selisihHari <= 7)
                                            {{-- Edit hanya muncul jika <= 7 hari --}}
                                            <a href="{{ route('page.indirecta.edit', $indirectcost->id) }}"
                                                class="btn btn-warning btn-sm">Edit</a>
                                        @else
                                            {{-- Kalau lebih dari 7 hari, tampilkan badge info --}}
                                            <span class="badge bg-secondary">Edit Expired</span>
                                        @endif

                                        <form action="{{ route('page.indirecta.destroy', $indirectcost->id) }}"
                                            method="POST" onsubmit="return confirm('Yakin mau hapus data ini?');">
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#example').DataTable();

            // Custom filter date range
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var min = $('#start_date').val();
                    var max = $('#end_date').val();
                    var date = data[5]; // kolom ke-6 = Tanggal Pengajuan (format d-m-Y)

                    if (date) {
                        // ubah d-m-Y menjadi Date object
                        var parts = date.split('-'); // [dd, mm, yyyy]
                        var tableDate = new Date(parts[2], parts[1] - 1, parts[0]);

                        var minDate = min ? new Date(min) : null;
                        var maxDate = max ? new Date(max) : null;

                        if (
                            (!minDate && !maxDate) ||
                            (!minDate && tableDate <= maxDate) ||
                            (!maxDate && tableDate >= minDate) ||
                            (tableDate >= minDate && tableDate <= maxDate)
                        ) {
                            return true;
                        }
                        return false;
                    }
                    return true;
                }
            );

            // tombol filter
            $('#filter_date').on('click', function() {
                table.draw();
            });

            // reset filter
            $('#reset_date').on('click', function() {
                $('#start_date').val('');
                $('#end_date').val('');
                table.draw();
            });

            // trigger filter saat tekan Enter di input
            $('#start_date, #end_date').on('keyup change', function(e) {
                if (e.key === 'Enter') {
                    table.draw();
                }
            });
        });
    </script>
@endpush
