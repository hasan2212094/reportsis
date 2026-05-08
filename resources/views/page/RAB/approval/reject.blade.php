@extends('kerangka.master')
@section('title', 'Reject Pengajuan RAB')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="fw-bold mb-4">
            <span class="text-muted fw-light">DANA / Approval /</span> Reject
        </h4>

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

        {{-- CEK DATA --}}
        @if ($rabpengajuans->isEmpty())
            <div class="alert alert-warning">
                Tidak ada pengajuan yang bisa diproses.
            </div>
        @else
            @php
                // ambil 1 data saja
                $rab = $rabpengajuans->first();
                // dd($rab);
            @endphp

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
            {{-- FORM REJECT --}}
            {{-- ===================== --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Form Reject</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('page.rab.reject', $rab->id) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Catatan Reject</label>
                            <textarea name="note_reject" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('page.RAB.approval.index') }}" class="btn btn-secondary">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-danger">
                                Kirim Reject
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

    </div>
@endsection
