<form>
    <div class="row g-3">

        {{-- WO No --}}
        <div class="col-md-6">
            <label class="form-label">WO No</label>
            <input type="text" class="form-control" value="{{ $wo->kode_wo }}" disabled>
        </div>

        {{-- Quantity --}}
        <div class="col-md-6">
            <label class="form-label">Quantity</label>
            <input type="number" class="form-control" value="{{ $wo->quantity ?? '' }}" disabled>
        </div>

        {{-- Customer Name --}}
        <div class="col-md-6">
            <label class="form-label">Customer Name</label>
            <input type="text" class="form-control" value="{{ $wo->customer_name }}" disabled>
        </div>

        {{-- Contact Person --}}
        <div class="col-md-6">
            <label class="form-label">Contact Person</label>
            <input type="text" class="form-control" value="{{ $wo->contact_person ?? '' }}" disabled>
        </div>

        {{-- Address --}}
        <div class="col-md-6">
            <label class="form-label">Address</label>
            <textarea class="form-control" rows="2" disabled>{{ $wo->address ?? '' }}</textarea>
        </div>

        {{-- Phone / Fax --}}
        <div class="col-md-6">
            <label class="form-label">Phone / Fax</label>
            <input type="text" class="form-control" value="{{ $wo->phone_fax ?? '' }}" disabled>
        </div>

        {{-- Customer PO No --}}
        <div class="col-md-6">
            <label class="form-label">Customer PO No</label>
            <input type="text" class="form-control" value="{{ $wo->customer_po_no ?? '' }}" disabled>
        </div>

        {{-- Customer PO Date --}}
        <div class="col-md-6">
            <label class="form-label">Customer PO Date</label>
            <input type="date" class="form-control"
                value="{{ $wo->customer_po_date ? \Carbon\Carbon::parse($wo->customer_po_date)->format('Y-m-d') : '' }}"
                disabled>
        </div>

        {{-- Delivery Date --}}
        <div class="col-md-6">
            <label class="form-label">Wo Date</label>
            <input type="date" class="form-control"
                value="{{ $wo->wo_date ? \Carbon\Carbon::parse($wo->wo_date)->format('Y-m-d') : '' }}" disabled>
        </div>

        <div class="col-md-6">
            <label class="form-label">Nama Produk</label>
            <input type="text" class="form-control" value="{{ $wo->nama_produk ?? '' }}" disabled>
        </div>

        <div class="col-md-6">
            <label class="form-label">Type Unit</label>
            <input type="text" class="form-control" value="{{ $wo->type_unit ?? '' }}" disabled>
        </div>

        <div class="col-md-6">
            <label class="form-label">Pekerjaan Selesai</label>
            <input type="text" class="form-control" value="{{ $wo->pekerjaan_selesai ?? '' }}" disabled>
        </div>

        <div class="col-md-6">
            <label class="form-label">Pekerjaan Termasuk</label>
            <input type="text" class="form-control" value="{{ $wo->pekerjaan_termasuk ?? '' }}" disabled>
        </div>

        <div class="col-md-6">
            <label class="form-label">Pekerjaan Tidak Termasuk</label>
            <input type="text" class="form-control" value="{{ $wo->pekerjaan_tidak_termasuk ?? '' }}" disabled>
        </div>

        <div class="col-md-6">
            <label class="form-label">Garansi</label>
            <input type="text" class="form-control" value="{{ $wo->garansi ?? '' }}" disabled>
        </div>

        <div class="col-md-6">
            <label class="form-label">Harga</label>
            <p class="form-control-plaintext">
                Rp {{ number_format($wo->total ?? 0, 0, ',', '.') }}
            </p>
        </div>

        <div class="col-md-6">
            <label class="form-label">Support By</label>
            <input type="text" class="form-control" value="{{ $wo->end_user ?? '' }}" disabled>
        </div>
    </div>
</form>
