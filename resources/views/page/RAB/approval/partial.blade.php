@extends('kerangka.master')
@section('title', 'Partial Payment Pengajuan RAB')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="fw-bold mb-4">
            <span class="text-muted fw-light">DANA / Approval /</span> Partial Payment
        </h4>

        {{-- ===================== --}}
        {{-- DETAIL PENGAJUAN --}}
        {{-- ===================== --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Detail Pengajuan</h5>
            </div>

            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-3">No. WO</dt>
                    <dd class="col-sm-9">{{ $rab->workorder->kode_wo ?? '-' }}</dd>

                    <dt class="col-sm-3">Nama User</dt>
                    <dd class="col-sm-9">{{ $rab->user->name ?? '-' }}</dd>

                    <dt class="col-sm-3">Nama Barang</dt>
                    <dd class="col-sm-9">{{ $rab->nama_barang }}</dd>

                    <dt class="col-sm-3">Qty Pengajuan</dt>
                    <dd class="col-sm-9">{{ $rab->qty }}</dd>

                    <dt class="col-sm-3">Nominal Pengajuan</dt>
                    <dd class="col-sm-9">
                        Rp {{ number_format((float) $rab->total, 0, ',', '.') }}
                    </dd>
                </dl>
            </div>
        </div>

        {{-- ===================== --}}
        {{-- FORM PARTIAL --}}
        {{-- ===================== --}}
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Form Partial Pembayaran</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('page.rab.partial', $rab->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nama PT</label>
                        <input type="text" name="nama_pt_partial" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Qty Partial</label>
                        <input type="number" name="qty_partial" class="form-control" min="1"
                            max="{{ $rab->qty }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nominal Partial</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" id="total_display_partial" class="form-control" placeholder="0">
                        </div>
                        <input type="hidden" name="total_partial" id="total_partial">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <input type="text" name="keterangan_partial" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Upload Bukti Pembayaran</label>
                        <input type="file" name="bukti_pembayaran_partial[]" class="form-control" multiple required>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('page.RAB.approval.index') }}" class="btn btn-secondary">
                            Batal
                        </a>

                        <button type="submit" class="btn btn-warning">
                            Kirim Partial
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    {{-- FORMAT RUPIAH --}}
    <script>
        const display = document.getElementById('total_display_partial');
        const hidden = document.getElementById('total_partial');

        display.addEventListener('input', function() {
            let value = this.value.replace(/[^0-9]/g, '');
            hidden.value = value;
            this.value = value ?
                new Intl.NumberFormat('id-ID').format(value) : '';
        });
    </script>

@endsection
