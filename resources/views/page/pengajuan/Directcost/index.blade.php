@extends('kerangka.master')
@section('title', 'Direct Cost Pengajuan')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Tables /</span> Direct Cost Pengajuan
        </h4>

        @include('components.alert')

        <div class="card p-3 mb-3">
            <div class="d-flex align-items-center justify-content-between">
                {{-- Form filter --}}
                <form method="GET" action="{{ route('page.pengajuan.Directcost.index') }}"
                    class="d-flex gap-2 align-items-center mb-0">
                    <label for="start_date" class="mb-0">Periode:</label>
                    <input type="date" name="start_date" id="start_date" class="form-control form-control-sm"
                        value="{{ request('start_date') }}">
                    <input type="date" name="end_date" id="end_date" class="form-control form-control-sm"
                        value="{{ request('end_date') }}">
                    <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                    <a href="{{ route('page.pengajuan.Directcost.index') }}" class="btn btn-sm btn-secondary">Reset</a>
                </form>

                {{-- Tombol aksi --}}
                <div class="d-flex gap-2">
                    <a href="{{ url('/directp/create') }}" class="btn btn-primary rounded-pill">Tambah data</a>
                    <a href="{{ route('directp.export', [
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
                        <th>Id_item</th>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Satuan</th>
                        <th>Kebutuhan</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Total</th>
                        <th>Note</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $directcost)
                        <tr>
                            <td>{{ $directcost->item_id }}</td>
                            <td>{{ $directcost->Item }}</td>
                            <td>{{ $directcost->Qty }}</td>
                            <td>{{ $directcost->Unit }}</td>
                            <td>{{ $directcost->Needed_by }}</td>
                            {{-- Format d-m-Y untuk ditampilkan --}}
                            <td>{{ \Carbon\Carbon::parse($directcost->Date_pengajuan)->format('d-m-Y') }}</td>
                            <td>Rp {{ number_format($directcost->Total, 0, ',', '.') }}</td>
                            <td>{{ $directcost->Notes }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-flex align-items-center gap-2">
                                        @php
                                            $selisihHari = \Carbon\Carbon::parse($directcost->Date_actual)->diffInDays(
                                                now(),
                                            );
                                        @endphp

                                        @if ($selisihHari <= 7)
                                            {{-- Edit hanya muncul jika <= 7 hari --}}
                                            <a href="{{ route('page.pengajuan.Directcost.edit', $directcost->id) }}"
                                                class="btn btn-warning btn-sm">Edit</a>
                                        @else
                                            {{-- Kalau lebih dari 7 hari, tampilkan badge info --}}
                                            <span class="badge bg-secondary">Edit Expired</span>
                                        @endif

                                        <form action="{{ route('page.Directcost.destroy', $directcost->id) }}"
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
