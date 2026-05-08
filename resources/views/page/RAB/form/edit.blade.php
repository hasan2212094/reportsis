@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Pengajuan RAB</h5>
            </div>

            <div class="card-body">

                <form action="{{ route('page.rab.update', $rab->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. WO</label>
                            <input type="text" name="no_wo" value="{{ $rab->no_wo }}" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Barang</label>
                            <input type="text" name="nama_barang" value="{{ $rab->nama_barang }}" class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Qty</label>
                            <input type="number" name="qty" value="{{ $rab->qty }}" class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">No. Rekening</label>
                            <input type="text" name="no_rek" value="{{ $rab->no_rek }}" class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Nama Toko</label>
                            <input type="text" name="nama_toko" value="{{ $rab->nama_toko }}" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. PR</label>
                            <input type="text" name="no_pr" value="{{ $rab->no_pr }}" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">ID Items</label>
                            <select name="item_id" class="form-select">
                                <option value="">-- Pilih Item --</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Keterangan Kebutuhan</label>
                            <textarea name="keterangan" class="form-control" rows="3">{{ $rab->keterangan }}</textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">File Keterangan</label>
                            <input type="file" name="file_keterangan" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Invoice</label>
                            <input type="file" name="invoice_file" class="form-control">
                        </div>

                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('page.RAB.form.index') }}" class="btn btn-secondary">
                            Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Update
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection
