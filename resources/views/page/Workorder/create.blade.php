@extends('kerangka.master')

@section('title', 'Tambah Workorder')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Workorder /</span> Tambah Workorder
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

                <form action="{{ route('page.workorder.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">

                        {{-- WO No --}}
                        <div class="col-md-6">
                            <label class="form-label">WO No</label>
                            <input type="text" name="kode_wo" class="form-control @error('kode_wo') is-invalid @enderror"
                                value="{{ old('kode_wo') }}" required>
                        </div>

                        {{-- Quantity --}}
                        <div class="col-md-6">
                            <label class="form-label">Quantity</label>
                            <input type="number" name="quantity"
                                class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity') }}">
                        </div>

                        {{-- Customer Name --}}
                        <div class="col-md-6">
                            <label class="form-label">Customer Name</label>
                            <input type="text" name="customer_name"
                                class="form-control @error('customer_name') is-invalid @enderror"
                                value="{{ old('customer_name') }}" required>
                        </div>

                        {{-- Contact Person --}}
                        <div class="col-md-6">
                            <label class="form-label">Contact Person</label>
                            <input type="text" name="contact_person" class="form-control"
                                value="{{ old('contact_person') }}">
                        </div>

                        {{-- Address --}}
                        <div class="col-md-6">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
                        </div>

                        {{-- Phone / Fax --}}
                        <div class="col-md-6">
                            <label class="form-label">Phone / Fax</label>
                            <input type="text" name="phone_fax" class="form-control" value="{{ old('phone_fax') }}">
                        </div>

                        {{-- Customer PO No --}}
                        <div class="col-md-6">
                            <label class="form-label">Customer PO No</label>
                            <input type="text" name="customer_po_no" class="form-control"
                                value="{{ old('customer_po_no') }}">
                        </div>

                        {{-- Customer PO Date --}}
                        <div class="col-md-6">
                            <label class="form-label">Customer PO Date</label>
                            <input type="date" name="customer_po_date" class="form-control"
                                value="{{ old('customer_po_date') }}">
                        </div>

                        {{-- Workorder Date --}}
                        <div class="col-md-6">
                            <label class="form-label">Workorder Date</label>
                            <input type="date" name="wo_date" class="form-control"
                                value="{{ old('customer_po_date') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nama Produk</label>
                            <input type="text" name="nama_produk" class="form-control" value="{{ old('nama_produk') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Type Unit</label>
                            <input type="text" name="type_unit" class="form-control" value="{{ old('type_unit') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Pekerjaan Selesai</label>
                            <input type="date" name="pekerjaan_selesai" class="form-control"
                                value="{{ old('pekerjaan_selesai') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Pekerjaan Termasuk</label>
                            <input type="text" name="pekerjaan_termasuk" class="form-control"
                                value="{{ old('pekerjaan_termasuk') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Pekerjaan Tidak Termasuk</label>
                            <input type="text" name="pekerjaan_tidak_termasuk" class="form-control"
                                value="{{ old('pekerjaan_tidak_termasuk') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Garansi</label>
                            <input type="text" name="garansi" class="form-control" value="{{ old('garansi') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Harga</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" id="total_display" class="form-control" placeholder="0"
                                    autocomplete="off">
                            </div>

                            <!-- WAJIB ADA -->
                            <input type="hidden" name="total" id="total">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Support By</label>
                            <input type="text" name="end_user" class="form-control" value="{{ old('end_user') }}">
                        </div>

                    </div>

                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <a href="{{ route('page.workorder.index') }}" class="btn btn-secondary">
                            Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Save Workorder
                        </button>
                    </div>

                </form>

            </div>
        </div>


    </div>
    @push('scripts')
        <script>
            const totalDisplay = document.getElementById('total_display');
            const hargaInput = document.getElementById('total');

            totalDisplay.addEventListener('input', function(e) {
                // Hapus semua karakter selain angka
                let value = e.target.value.replace(/\D/g, '');

                // Set ke hidden input
                hargaInput.value = value;

                // Format dengan titik ribuan
                e.target.value = new Intl.NumberFormat('id-ID').format(value);
            });
        </script>
    @endpush
@endsection
