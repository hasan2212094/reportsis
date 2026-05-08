@extends('kerangka.master')
@section('title', 'Payment Pengajuan RAB')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="fw-bold mb-4">
            <span class="text-muted fw-light">DANA / Approval /</span> Payment
        </h4>

        {{-- CEK DATA --}}


        {{-- ===================== --}}
        {{-- DETAIL PENGAJUAN --}}
        {{-- ===================== --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Detail Pengajuan</h5>
            </div>
            {{-- NOTIFIKASI --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-3">No. WO</dt>
                    <dd class="col-sm-9">
                        {{ $rab->workorder->kode_wo ?? '-' }}
                    </dd>

                    <dt class="col-sm-3">Nama User</dt>
                    <dd class="col-sm-9">
                        {{ $rab->user->name ?? '-' }}
                    </dd>

                    <dt class="col-sm-3">Nama Barang</dt>
                    <dd class="col-sm-9">
                        {{ $rab->nama_barang }}
                    </dd>

                    <dt class="col-sm-3">Qty</dt>
                    <dd class="col-sm-9">
                        {{ $rab->qty }}
                    </dd>

                    <dt class="col-sm-3">Nominal</dt>
                    <dd class="col-sm-9">
                        Rp {{ number_format((float) $rab->total, 0, ',', '.') }}
                    </dd>

                    <dt class="col-sm-3">Nama Toko</dt>
                    <dd class="col-sm-9">
                        {{ $rab->nama_toko ?? '-' }}
                    </dd>

                    <dt class="col-sm-3">Keterangan</dt>
                    <dd class="col-sm-9">
                        {{ $rab->kebutuhan ?? '-' }}
                    </dd>
                </dl>
            </div>
        </div>

        {{-- ===================== --}}
        {{-- FORM PEMBAYARAN --}}
        {{-- ===================== --}}
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Form Pembayaran</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('page.rab.approve', $rab->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nama PT</label>
                        <input type="text" name="nama_pt" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nominal</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" id="total_display" class="form-control" placeholder="0"
                                autocomplete="off">
                        </div>

                        {{-- nilai asli untuk backend --}}
                        <input type="hidden" name="total_approved" id="total_approved">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <input type="text" name="comment_approved" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Upload Bukti Pembayaran</label>
                        <input type="file" name="bukti_pembayaran" class="form-control" required>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('page.RAB.approval.index') }}" class="btn btn-secondary">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Kirim Pembayaran
                        </button>
                    </div>
                    <script>
                        const display = document.getElementById('total_display');
                        const hidden = document.getElementById('total_approved');

                        display.addEventListener('input', function() {
                            // ambil angka saja
                            let value = this.value.replace(/[^0-9]/g, '');

                            // set ke hidden input (backend)
                            hidden.value = value;

                            // format rupiah
                            this.value = value ?
                                new Intl.NumberFormat('id-ID').format(value) :
                                '';
                        });
                    </script>
                </form>
            </div>
        </div>
    </div>
@endsection
