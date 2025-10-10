@extends('kerangka.master')
@section('title, add PPN Actual')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">From /</span>Add PPN Actual</h4>
        <div class="card-body">
            <form action="{{ route('page.ppna.store') }}" method="POST">
                @csrf
                <div class="row g-2">
                    <div class="col-md-2">
                        <div class="mb-2">
                            <label for="ppns_id" class="form-label small text-secondary">ITEM ID</label>
                            <select name="ppns_id" id="ppns_id" class="form-control form-control-sm shadow-sm rounded-3">
                                <option value="">-- Select --</option>
                                @foreach ($ppn as $item)
                                    <option value="" hidden>-- Select --</option>
                                    <option value="{{ $item->id }}">{{ $item->item_id }}-{{ $item->Item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row g-2">
                    <div class="col-md-2">
                        <div class="mb-2">
                            <label for="Qty" class="form-label small text-secondary">Qty Actual</label>
                            <input type="text" name="Qty" class="form-control form-control-sm shadow-sm rounded-3"
                                value="{{ old('Qty') }}">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="mb-2">
                            <label for="Unit" class="form-label small text-secondary">SATUAN</label>
                            <input type="text" name="Unit" class="form-control form-control-sm shadow-sm rounded-3"
                                value="{{ old('Unit') }}">
                        </div>
                    </div>
                </div>
                <div class="row g-2">
                    <div class="col-md-3">
                        <div class="mb-2">
                            <label for="Toko" class="form-label small text-secondary">TOKO</label>
                            <input type="text" name="Toko" class="form-control form-control-sm shadow-sm rounded-3"
                                value="{{ old('Toko') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-2">
                            <label for="Date_actual" class="form-label small text-secondary">Tanggal Actual</label>
                            <input type="date" name="Date_actual"
                                class="form-control form-control-sm shadow-sm rounded-3" value="{{ old('Date_actual') }}"
                                onkeydown="return false">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="Transaksi" class="form-label">TRANSAKSI</label>
                        <select name="Transaksi" id="Transaksi" class="form-control form-control-sm shadow-sm rounded-3">
                            <option value="" hidden>-- Select Transaksi --</option>
                            <option value="1">Transfer</option>
                            <option value="0">Cash</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="Total" class="form-label">Total</label>
                            <input type="text" name="Total" class="form-control form-control-sm shadow-sm rounded-3"
                                value="{{ old('Total') }}">
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-end gap-3">
                    <a href="" class="btn btn-dark"><i class="icon-base bx bx-arrow-back icon-sm me-1"></i>
                        Back to list</a>
                    <button type="submit" class="btn btn-primary"><i class="icon-base bx bx-save icon-sm me-1"></i>
                        Save</button>
                </div>
            </form>
        @endsection
