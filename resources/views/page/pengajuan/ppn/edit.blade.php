@extends('kerangka.master')
@section('title, Edit PPN Pengajuan')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">From /</span>Edit PPN Pengajuan</h4>
        <div class="card-body">
            @if (session('error'))
                <div class="alert alert-danger mx-2">
                    {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger mx-2">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success mx-2">{{ session('success') }}</div>
            @endif

            <form action="{{ route('page.ppn.update', $data->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-2">
                    <div class="col-md-1">
                        <div class="mb-2">
                            <label for="item_id" class="form-label small text-secondary">ID ITEM</label>
                            <input type="text" name="item_id" class="form-control form-control-sm shadow-sm rounded-3"
                                value="{{ old('item_id', $data->item_id) }}"
                                oninput="this.value = this.value.toUpperCase()">
                        </div>
                    </div>
                </div>
                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label for="Item" class="form-label small text-secondary">ITEM</label>
                            <input type="text" name="Item" class="form-control form-control-sm shadow-sm rounded-3"
                                value="{{ old('Item', $data->Item) }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-2">
                            <label for="Qty" class="form-label small text-secondary">QTY</label>
                            <input type="text" name="Qty" class="form-control form-control-sm shadow-sm rounded-3"
                                value="{{ old('Qty', $data->Qty) }}">
                        </div>
                    </div>
                </div>
                <div class="row g-2">
                    <div class="col-md-1">
                        <div class="mb-2">
                            <label for="Unit" class="form-label small text-secondary">SATUAN</label>
                            <input type="text" name="Unit" class="form-control form-control-sm shadow-sm rounded-3"
                                value="{{ old('Unit', $data->Unit) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label for="Needed_by" class="form-label small text-secondary">KEBUTUHAN</label>
                            <input type="text" name="Needed_by" class="form-control form-control-sm shadow-sm rounded-3"
                                value="{{ old('Needed_by', $data->Needed_by) }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-2">
                        <label for="Date_pengajuan" class="form-label small text-secondary">Tanggal Pengajuan</label>
                        <input type="date" name="Date_pengajuan" class="form-control form-control-sm shadow-sm rounded-3"
                            value="{{ old('Date_pengajuan', $data->Date_pengajuan) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="Total" class="form-label">Total</label>
                        <input type="text" name="Total" class="form-control form-control-sm shadow-sm rounded-3"
                            value="{{ old('Total', $data->Total) }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="Notes" class="form-label">Note</label>
                    <textarea name="Notes" id="Notes" cols="30" rows="10"
                        class="form-control form-control-sm shadow-sm rounded-3">{{ old('Notes', $data->Notes) }}</textarea>
                </div>
        </div>

        <div class="card-footer d-flex justify-content-end gap-3">
            <a href="{{ url('/directp') }}" class="btn btn-dark"><i class="icon-base bx bx-arrow-back icon-sm me-1"></i>
                Back to list</a>
            <button type="submit" class="btn btn-primary"><i class="icon-base bx bx-save icon-sm me-1"></i>
                Save</button>
        </div>
        </form>
    @endsection
