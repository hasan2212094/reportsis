@extends('kerangka.master')
@section('title, add Di Luar RAB Actual')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">From /</span>Add Di Luar RAB Actual</h4>
        <div class="card-body">
            <form action="{{ route('page.luarrab.update', $data->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-2">
                    <div class="col-md-1">
                        <div class="mb-2">
                            <label for="luarrabps_id" class="form-label small text-secondary">ID ITEM</label>
                            <input type="text" class="form-control form-control-sm shadow-sm rounded-3 bg-light"
                                value="{{ $data->luarrabps_id }}" disabled>
                            <input type="hidden" name="luarrabps_id" value="{{ $data->luarrabps_id }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-2">
                            <label for="Item" class="form-label small text-secondary">ITEM</label>
                            <input type="text" name="Item" class="form-control form-control-sm shadow-sm rounded-3"
                                value="{{ old('Item', $data->Item) }}">
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="needed_by_select" class="form-label fw-bold">KEBUTUHAN (No WO)</label>
                        <select id="needed_by_select" name="Needed_by" class="form-select form-select-sm">
                            <option value="">-- Pilih atau cari Work Order --</option>
                            <option value="manual" {{ $data->workorder_id ? '' : 'selected' }}>Input manual di luar WO
                            </option>
                            @foreach ($workorders as $wo)
                                <option value="{{ $wo->kode_wo }}" {{ $data->workorder_id == $wo->id ? 'selected' : '' }}>
                                    {{ $wo->kode_wo }} — {{ $wo->description }}
                                </option>
                            @endforeach
                        </select>


                        <input type="text" name="needed_by_input" id="needed_by_input" class="form-control mt-2"
                            placeholder="Tulis manual jika tidak pilih WO"
                            style="{{ $data->workorder_id ? 'display:none;' : '' }}"
                            value="{{ old('needed_by_input', $data->Needed_by) }}">
                    </div>
                    <div class="col-md-2">
                        <div class="mb-2">
                            <label for="Qty" class="form-label small text-secondary">Qty Actual</label>
                            <input type="text" name="Qty" class="form-control form-control-sm shadow-sm rounded-3"
                                value="{{ old('Qty', $data->Qty) }}">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="mb-2">
                            <label for="Unit" class="form-label small text-secondary">SATUAN</label>
                            <input type="text" name="Unit" class="form-control form-control-sm shadow-sm rounded-3"
                                value="{{ old('Unit', $data->Unit) }}">
                        </div>
                    </div>
                </div>
                <div class="row g-2">
                    <div class="col-md-3">
                        <div class="mb-2">
                            <label for="Toko" class="form-label small text-secondary">TOKO</label>
                            <input type="text" name="Toko" class="form-control form-control-sm shadow-sm rounded-3"
                                value="{{ old('Toko', $data->Toko) }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-2">
                            <label for="Date_actual" class="form-label small text-secondary">Tanggal Actual</label>
                            <input type="date" name="Date_actual"
                                class="form-control form-control-sm shadow-sm rounded-3"
                                value="{{ old('Date_actual', $data->Date_actual) }}" onkeydown="return false">
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
                                value="{{ old('Total', $data->Total) }}">
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-end gap-3">
                    <a href="{{ url('/luarrab') }}" class="btn btn-dark"><i
                            class="icon-base bx bx-arrow-back icon-sm me-1"></i>
                        Back to list</a>
                    <button type="submit" class="btn btn-primary"><i class="icon-base bx bx-save icon-sm me-1"></i>
                        Save</button>
                </div>
            </form>
            <script>
                document.getElementById('needed_by_select').addEventListener('change', function() {
                    const inputManual = document.getElementById('needed_by_input');
                    inputManual.style.display = this.value === 'manual' ? 'block' : 'none';
                });
            </script>
        @endsection
