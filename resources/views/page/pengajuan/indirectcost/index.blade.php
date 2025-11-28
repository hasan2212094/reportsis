@php
    use Carbon\Carbon;
@endphp
@extends('kerangka.master')
@section('title', 'Indirect Cost Pengajuan')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Tables /</span> Indirect Cost Pengajuan
        </h4>

        @include('components.alert')

        {{-- Tombol Aksi dan Filter --}}
        <div class="card p-3 mb-3">
            <div class="d-flex align-items-center justify-content-between">
                <form method="GET" action="{{ route('page.indirectp.index') }}" class="d-flex gap-2 align-items-center mb-0">
                    <label for="start_date" class="mb-0">Periode:</label>
                    <input type="date" name="start_date" id="start_date" class="form-control form-control-sm"
                        value="{{ request('start_date') }}">
                    <input type="date" name="end_date" id="end_date" class="form-control form-control-sm"
                        value="{{ request('end_date') }}">
                    <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                    <a href="{{ route('page.indirectp.index') }}" class="btn btn-sm btn-secondary">Reset</a>
                </form>

                <div class="d-flex gap-2">
                    <a href="{{ route('page.indirectp.create') }}" class="btn btn-primary rounded-pill">Tambah data</a>
                    <a href="{{ route('indirectp.export', [
                        'start_date' => request('start_date'),
                        'end_date' => request('end_date'),
                    ]) }}"
                        class="btn btn-success rounded-pill">Export Excel</a>
                </div>
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <form action="{{ route('indirectp.import') }}" method="POST" enctype="multipart/form-data"
                            class="mt-3">
                            @csrf
                            <div class="mb-3">
                                <label for="file" class="form-label">Pilih File Excel (.xlsx)</label>
                                <input type="file" name="file" id="file" class="form-control" required>
                                @error('file')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Import</button>
                        </form>

                        <hr>
                        <p>📄 Contoh format file: <a href="{{ asset('template_ppn_import.xlsx') }}" download>Download
                                Template</a></p>
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
                                @foreach ($data as $indirectcost)
                                    <tr>
                                        <td>{{ $indirectcost->item_id }}</td>
                                        <td>{{ $indirectcost->Item }}</td>
                                        <td>{{ $indirectcost->Qty }}</td>
                                        <td>{{ $indirectcost->Unit }}</td>
                                        <td>{{ $indirectcost->Needed_by }}</td>
                                        <td>{{ \Carbon\Carbon::parse($indirectcost->Date_pengajuan)->format('d-m-Y') }}
                                        </td>
                                        <td> Rp {{ number_format((float) $directcost->Total, 0, ',', '.') }}</td>
                                        <td>{{ $indirectcost->Notes }}</td>
                                        <td>
                                            @php
                                                $selisihHari = Carbon::now()->diffInDays($indirectcost->created_at);
                                            @endphp
                                            <div class="d-flex gap-2">
                                                {{-- <a href="{{ route('page.indirectp.edit', $indirectcost->id) }}"
                                                class="btn btn-warning btn-sm">Edit</a> --}}
                                                <div class="d-flex gap-2">
                                                    @if ($selisihHari <= 7)
                                                        <a href="{{ route('page.indirectp.edit', $indirectcost->id) }}"
                                                            class="btn btn-warning btn-sm">
                                                            Edit
                                                        </a>
                                                    @else
                                                        <span class="badge bg-secondary p-2">Edit Expired
                                                            ({{ $selisihHari }}
                                                            hari)
                                                        </span>
                                                    @endif
                                                </div>
                                                <form class="form-soft-delete"
                                                    action="{{ route('page.indirectp.destroy', $indirectcost->id) }}"
                                                    method="POST" data-id="{{ $indirectcost->id }}">
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
                                @forelse ($trashed as $indirectcost)
                                    <tr>
                                        <td>{{ $indirectcost->item_id }}</td>
                                        <td>{{ $indirectcost->Item }}</td>
                                        <td>{{ $indirectcost->Qty }}</td>
                                        <td>{{ $indirectcost->Unit }}</td>
                                        <td>{{ $indirectcost->Needed_by }}</td>
                                        <td>{{ \Carbon\Carbon::parse($indirectcost->Date_pengajuan)->format('d-m-Y') }}
                                        </td>
                                        <td>Rp {{ number_format($indirectcost->Total, 0, ',', '.') }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <form class="form-restore"
                                                    action="{{ route('page.indirectp.restore', $indirectcost->id) }}"
                                                    method="POST" data-id="{{ $indirectcost->id }}">
                                                    @csrf
                                                    <button class="btn btn-success btn-sm">Restore</button>
                                                </form>

                                                <form class="form-force-delete"
                                                    action="{{ route('page.indirectp.forceDelete', $indirectcost->id) }}"
                                                    method="POST" data-id="{{ $indirectcost->id }}">
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

    {{-- @push('scripts')
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
@endpush --}}
