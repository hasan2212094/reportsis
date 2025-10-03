@extends('kerangka.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- Filter Periode --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('home') }}" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="start_date" class="form-label fw-semibold">Tanggal Awal</label>
                        <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}"
                            class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="form-label fw-semibold">Tanggal Akhir</label>
                        <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}"
                            class="form-control">
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel me-1"></i> Filter
                        </button>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-arrow-repeat me-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Row Atas: Jam - Periode - Total Pengajuan - Total Actual --}}
        <div class="row g-3 mb-4">
            <!-- Jam -->
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-clock-history fs-2 text-primary mb-2"></i>
                        <span class="fw-semibold d-block text-muted">Jam</span>
                        <h5 id="clock" class="card-title text-primary mt-2"></h5>
                    </div>
                </div>
            </div>

            <!-- Periode -->
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-calendar-event fs-2 text-info mb-2"></i>
                        <span class="fw-semibold d-block text-muted">Periode</span>
                        <h6 class="card-title text-info mt-2">
                            @if (request('start_date') && request('end_date'))
                                {{ \Carbon\Carbon::parse(request('start_date'))->format('d M Y') }} -
                                {{ \Carbon\Carbon::parse(request('end_date'))->format('d M Y') }}
                            @else
                                {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
                            @endif
                        </h6>
                    </div>
                </div>
            </div>

            <!-- Total Pengajuan -->
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-cash-coin fs-2 text-success mb-2"></i>
                        <span class="fw-semibold d-block text-muted">Total Pengajuan</span>
                        <h5 class="card-title text-success mt-2">
                            Rp {{ number_format($total ?? 0, 0, ',', '.') }}
                        </h5>
                    </div>
                </div>
            </div>

            <!-- Total Actual -->
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-graph-down-arrow fs-2 text-danger mb-2"></i>
                        <span class="fw-semibold d-block text-muted">Total Actual</span>
                        <h5 class="card-title text-danger mt-2">
                            Rp {{ number_format($total2 ?? 0, 0, ',', '.') }}
                        </h5>
                    </div>
                </div>
            </div>
        </div>

        {{-- Row Bawah: Total Pengajuan vs Actual --}}
        <div class="row g-3">
            <!-- Card Total Pengajuan -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-gradient bg-warning text-white text-center fw-bold">
                        <i class="bi bi-wallet2 me-2"></i> Total Pengajuan
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Direct Cost</span>
                            <span class="fw-semibold">Rp {{ number_format($totalDirectP, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Indirect Cost</span>
                            <span class="fw-semibold">Rp {{ number_format($totalIndirectp, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">PPN</span>
                            <span class="fw-semibold">Rp {{ number_format($totalPpn, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Actual -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-gradient bg-danger text-white text-center fw-bold">
                        <i class="bi bi-clipboard2-check me-2"></i> Actual
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Direct Cost Actual</span>
                            <span class="fw-semibold">Rp {{ number_format($totalDirecta, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Indirect Cost Actual</span>
                            <span class="fw-semibold">Rp {{ number_format($totalIndirecta, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">PPN Actual</span>
                            <span class="fw-semibold">Rp {{ number_format($totalPpna, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Luar RAB Actual</span>
                            <span class="fw-semibold">Rp {{ number_format($totalluarrab, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Jam Otomatis --}}
    <script>
        function updateClock() {
            var now = new Date();
            var time = now.toLocaleTimeString();
            document.getElementById('clock').innerText = time;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
@endsection
