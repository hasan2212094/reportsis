@extends('kerangka.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- ROW ATAS --}}
        <div class="row g-3 mb-4">

            {{-- Perbandingan --}}
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <span class="fw-semibold d-block text-muted">Perbandingan</span>
                        <h5 class="mt-2 {{ $efisiensi < 0 ? 'text-danger' : 'text-success' }}">
                            {{ number_format($efisiensi, 1) }}%
                        </h5>
                        <small class="text-muted">
                            {{ $efisiensi < 0 ? 'Hati-hati' : 'Efisien' }}
                        </small>
                    </div>
                </div>
            </div>

            {{-- Periode --}}
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <span class="fw-semibold d-block text-muted">Periode (1 Minggu)</span>

                        <form method="GET" action="{{ route('home') }}">
                            <select name="start_date" class="form-select form-select-sm mt-2">
                                <option value="">-- Pilih Periode --</option>
                                @foreach ($periodeList as $periode)
                                    <option value="{{ $periode['start'] }}"
                                        {{ $start == $periode['start'] ? 'selected' : '' }}>
                                        {{ $periode['label'] }}
                                    </option>
                                @endforeach
                            </select>

                            <input type="hidden" name="end_date"
                                value="{{ $periodeList[array_search($start, array_column($periodeList, 'start'))]['end'] ?? '' }}">

                            <button class="btn btn-primary btn-sm w-100 mt-2">
                                Filter
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Total Pengajuan --}}
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-cash-coin fs-2 text-success mb-2"></i>
                        <span class="fw-semibold d-block text-muted">Total Pengajuan</span>
                        <h5 class="text-success mt-2">
                            Rp {{ number_format($totalPengajuan ?? 0, 0, ',', '.') }}
                        </h5>
                    </div>
                </div>
            </div>

            {{-- Total Actual --}}
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-graph-down-arrow fs-2 text-danger mb-2"></i>
                        <span class="fw-semibold d-block text-muted">Total Actual</span>
                        <h5 class="text-danger mt-2">
                            Rp {{ number_format($totalActual ?? 0, 0, ',', '.') }}
                        </h5>
                    </div>
                </div>
            </div>

        </div>

        {{-- ROW BAWAH --}}
        <div class="row g-3">

            {{-- Total Pengajuan --}}
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-warning text-white text-center fw-bold py-2">
                        <i class="bi bi-wallet2 me-1"></i> Total Pengajuan
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Direct Cost</span>
                            <span>Rp {{ number_format($totalDirectP, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Indirect Cost</span>
                            <span>Rp {{ number_format($totalIndirectp, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">PPN</span>
                            <span>Rp {{ number_format($totalPpn, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actual --}}
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-danger text-white text-center fw-bold py-2">
                        <i class="bi bi-clipboard2-check me-1"></i> Actual
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Direct Cost</span>
                            <span>Rp {{ number_format($totalDirecta, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Indirect Cost</span>
                            <span>Rp {{ number_format($totalIndirecta, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">PPN</span>
                            <span>Rp {{ number_format($totalPpna, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Luar RAB</span>
                            <span>Rp {{ number_format($totalluarrab, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
