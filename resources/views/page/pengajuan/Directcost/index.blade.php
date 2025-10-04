@extends('kerangka.master')
@section('title', 'Direct Cost Pengajuan')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Tables /</span> Direct Cost Pengajuan
        </h4>

        @include('components.alert')

        {{-- Tombol Aksi dan Filter --}}
        <div class="card p-3 mb-3">
            <div class="d-flex align-items-center justify-content-between">
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

        {{-- Tab: Data Aktif / Data Terhapus --}}
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
            {{-- TAB 1: Data Aktif --}}
            <div class="tab-pane fade show active" id="aktif" role="tabpanel">
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
                                <th>Aksi</th>
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
                                    <td>{{ \Carbon\Carbon::parse($directcost->Date_pengajuan)->format('d-m-Y') }}</td>
                                    <td>Rp {{ number_format($directcost->Total, 0, ',', '.') }}</td>
                                    <td>{{ $directcost->Notes }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('page.pengajuan.Directcost.edit', $directcost->id) }}"
                                                class="btn btn-warning btn-sm">Edit</a>
                                            <form class="form-soft-delete"
                                                action="{{ route('page.Directcost.destroy', $directcost->id) }}"
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

            {{-- TAB 2: Data Terhapus --}}
            <div class="tab-pane fade" id="hapus" role="tabpanel">
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID Item</th>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Satuan</th>
                                <th>Kebutuhan</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($trashed as $directcost)
                                <tr>
                                    <td>{{ $directcost->item_id }}</td>
                                    <td>{{ $directcost->Item }}</td>
                                    <td>{{ $directcost->Qty }}</td>
                                    <td>{{ $directcost->Unit }}</td>
                                    <td>{{ $directcost->Needed_by }}</td>
                                    <td>{{ \Carbon\Carbon::parse($directcost->Date_pengajuan)->format('d-m-Y') }}</td>
                                    <td>Rp {{ number_format($directcost->Total, 0, ',', '.') }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <form class="form-restore"
                                                action="{{ route('page.Directcost.restore', $directcost->id) }}"
                                                method="POST" data-id="{{ $directcost->id }}">
                                                @csrf
                                                <button class="btn btn-success btn-sm">Restore</button>
                                            </form>

                                            <form class="form-force-delete"
                                                action="{{ route('page.Directcost.forceDelete', $directcost->id) }}"
                                                method="POST" data-id="{{ $directcost->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus Permanen</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data terhapus</td>
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
            const tableAktif = $('#example').DataTable();

            // === Soft Delete ===
            $(document).on('submit', '.form-soft-delete', function(e) {
                e.preventDefault();
                if (!confirm('Yakin mau hapus data ini (soft delete)?')) return;

                let form = $(this);
                let url = form.attr('action');
                let row = form.closest('tr');

                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: form.serialize(),
                    success: function(res) {
                        if (res.status === 'success') {
                            alert(res.message);
                            // Pindahkan baris ke tab "terhapus"
                            row.fadeOut(300, function() {
                                let rowData = row.clone();
                                row.remove();

                                // tambahkan ke tabel terhapus
                                $('#tbody-trashed').prepend(rowData);
                                // ganti tombol jadi restore dan hapus permanen
                                rowData.find('td:last').html(`
                                <div class="d-flex gap-2">
                                    <form class="form-restore" action="/directp/restore/${form.data('id')}" method="POST">
                                        @csrf
                                        <button class="btn btn-success btn-sm">Restore</button>
                                    </form>
                                    <form class="form-force-delete" action="/directp/force-delete/${form.data('id')}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Hapus Permanen</button>
                                    </form>
                                </div>
                            `);
                            });
                        }
                    },
                    error: function(err) {
                        alert('Terjadi kesalahan.');
                    }
                });
            });

            // === Restore ===
            $(document).on('submit', '.form-restore', function(e) {
                e.preventDefault();

                let form = $(this);
                let url = form.attr('action');
                let row = form.closest('tr');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: form.serialize(),
                    success: function(res) {
                        if (res.status === 'success') {
                            alert(res.message);
                            // pindahkan ke tabel aktif
                            row.fadeOut(300, function() {
                                let rowData = row.clone();
                                row.remove();

                                rowData.find('td:last').html(`
                                <div class="d-flex gap-2">
                                    <a href="#" class="btn btn-warning btn-sm">Edit</a>
                                    <form class="form-soft-delete" action="/directp/${form.data('id')}" method="POST" data-id="${form.data('id')}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </div>
                            `);
                                $('#example tbody').prepend(rowData);
                            });
                        }
                    },
                    error: function() {
                        alert('Gagal restore data.');
                    }
                });
            });

            // === Force Delete ===
            $(document).on('submit', '.form-force-delete', function(e) {
                e.preventDefault();
                if (!confirm('Yakin hapus permanen data ini?')) return;

                let form = $(this);
                let url = form.attr('action');
                let row = form.closest('tr');

                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: form.serialize(),
                    success: function(res) {
                        if (res.status === 'success') {
                            alert(res.message);
                            row.fadeOut(300, function() {
                                row.remove();
                            });
                        }
                    },
                    error: function() {
                        alert('Gagal menghapus permanen.');
                    }
                });
            });
        });
    </script>
@endpush
