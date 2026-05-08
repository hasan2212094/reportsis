@extends('kerangka.master')

@section('title', 'Edit Workorder')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Workorder /</span> Edit Workorder
        </h4>

        {{-- Alert --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">

                <form action="{{ route('page.workorder.update', $workorder->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        {{-- WO No --}}
                        <div class="col-md-6">
                            <label class="form-label">WO No</label>
                            <input type="text" name="kode_wo" class="form-control @error('kode_wo') is-invalid @enderror"
                                value="{{ old('kode_wo', $workorder->kode_wo) }}" required>
                        </div>

                        {{-- Quantity --}}
                        <div class="col-md-6">
                            <label class="form-label">Quantity</label>
                            <input type="number" name="quantity"
                                class="form-control @error('quantity') is-invalid @enderror"
                                value="{{ old('quantity', $workorder->quantity) }}">
                        </div>

                        {{-- Customer Name --}}
                        <div class="col-md-6">
                            <label class="form-label">Customer Name</label>
                            <input type="text" name="customer_name"
                                class="form-control @error('customer_name') is-invalid @enderror"
                                value="{{ old('customer_name', $workorder->customer_name) }}" required>
                        </div>

                        {{-- Contact Person --}}
                        <div class="col-md-6">
                            <label class="form-label">Contact Person</label>
                            <input type="text" name="contact_person" class="form-control"
                                value="{{ old('contact_person', $workorder->contact_person) }}">
                        </div>

                        {{-- Address --}}
                        <div class="col-md-6">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="2">{{ old('address', $workorder->address) }}</textarea>
                        </div>

                        {{-- Phone / Fax --}}
                        <div class="col-md-6">
                            <label class="form-label">Phone / Fax</label>
                            <input type="text" name="phone_fax" class="form-control"
                                value="{{ old('phone_fax', $workorder->phone_fax) }}">
                        </div>

                        {{-- Customer PO No --}}
                        <div class="col-md-6">
                            <label class="form-label">Customer PO No</label>
                            <input type="text" name="customer_po_no" class="form-control"
                                value="{{ old('customer_po_no', $workorder->customer_po_no) }}">
                        </div>

                        {{-- Customer PO Date --}}
                        <div class="col-md-6">
                            <label class="form-label">Customer PO Date</label>
                            <input type="date" name="customer_po_date" class="form-control"
                                value="{{ old('customer_po_date', $workorder->customer_po_date) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nama Produk</label>
                            <input type="text" name="nama_produk" class="form-control"
                                value="{{ old('nama_produk', $workorder->nama_produk) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Type Unit</label>
                            <input type="text" name="type_unit" class="form-control"
                                value="{{ old('type_unit', $workorder->type_unit) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Pekerjaan Selesai</label>
                            <input type="text" name="pekerjaan_selesai" class="form-control"
                                value="{{ old('pekerjaan_selesai', $workorder->pekerjaan_selesai) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Pekerjaan Termasuk</label>
                            <input type="text" name="pekerjaan_termasuk" class="form-control"
                                value="{{ old('pekerjaan_termasuk', $workorder->pekerjaan_termasuk) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Pekerjaan Termasuk</label>
                            <input type="text" name="pekerjaan_termasuk" class="form-control"
                                value="{{ old('pekerjaan_termasuk', $workorder->pekerjaan_termasuk) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Garansi</label>
                            <input type="text" name="garansi" class="form-control"
                                value="{{ old('garansi', $workorder->garansi) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Support By</label>
                            <input type="text" name="end_user" class="form-control"
                                value="{{ old('end_user', $workorder->end_user) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Customer PO Date</label>
                            <input type="date" name="customer_po_date" class="form-control"
                                value="{{ old('customer_po_date', $workorder->customer_po_date) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">WO Date</label>
                            <input type="date" name="wo_date" class="form-control"
                                value="{{ old('wo_date', $workorder->wo_date) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Harga</label>
                            <input type="text" name="total" class="form-control"
                                value="{{ old('total', number_format($workorder->total ?? 0, 0, ',', '.')) }}"
                                placeholder="Masukkan harga">
                        </div>

                    </div>

                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <a href="{{ route('page.workorder.index') }}" class="btn btn-secondary">
                            Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Update Workorder
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection
