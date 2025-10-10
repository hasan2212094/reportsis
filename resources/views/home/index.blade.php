{{-- @extends('kerangka.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

     
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

        <div class="row g-3">
          
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


    <script>
        function updateClock() {
            var now = new Date();
            var time = now.toLocaleTimeString();
            document.getElementById('clock').innerText = time;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
@endsection --}}
@extends('kerangka.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- Row Atas: Persentase - Periode - Total Pengajuan - Total Actual --}}
        <div class="row g-3 mb-4">
            <!-- Persentase -->
            {{-- <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-percent fs-2 text-primary mb-2"></i>
                        <span class="fw-semibold d-block text-muted">Perbandingan</span>
                        @php
                            $pengajuan = $total ?? 0;
                            $actual = $total2 ?? 0;
                            $persen = $pengajuan > 0 ? ($actual / $pengajuan) * 100 : 0;
                        @endphp
                        <h5 class="card-title mt-2 {{ $persen > 100 ? 'text-danger' : 'text-success' }}">
                            {{ number_format($persen, 1) }}%
                        </h5>
                        <small class="text-muted">
                            {{ $persen > 100 ? 'Over Budget' : 'Efisien' }}
                        </small>
                    </div>
                </div>
            </div> --}}
            <div class="row">
                <!-- Perbandingan -->
                <div class="col-lg-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <span class="fw-semibold d-block text-muted">Perbandingan</span>
                            <h5 class="card-title {{ $efisiensi < 0 ? 'text-danger' : 'text-success' }} mt-2">
                                {{ number_format($efisiensi, 1) }}%
                            </h5>
                            <small class="text-muted">
                                {{ $efisiensi < 0 ? 'Hati-hati' : 'Efisien' }}
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Periode Filter -->
                <div class="col-lg-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <span class="fw-semibold d-block text-muted">Periode (1 MINGGU)</span>
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
                                <button type="submit" class="btn btn-primary btn-sm mt-2">Filter</button>
                            </form>
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
                                Rp {{ number_format($totalPengajuan ?? 0, 0, ',', '.') }}
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
                                Rp {{ number_format($totalActual ?? 0, 0, ',', '.') }}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>


            {{-- Bawah: Detail Total Pengajuan & Actual --}}
            <div class="row g-3">
                <!-- Total Pengajuan -->
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

                <!-- Actual -->
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
    @endsection
    <script>
        document.getElementById('periode').addEventListener('change', function() {
            const [start, end] = this.value.split('|');
            document.getElementById('start_date').value = start;
            document.getElementById('end_date').value = end;
        });
    </script>
