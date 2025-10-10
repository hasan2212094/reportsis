@extends('kerangka.master')
@section('title, add Di Luar RAB Actual')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">From /</span>Add Di Luar RAB Actual</h4>
        @if (session('error'))
            <div class="alert alert-danger mx-2">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger mx-2">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success mx-2">{{ session('success') }}</div>
        @endif

        <div class="card-body">
            <form action="{{ route('page.luarrab.store') }}" method="POST">
                @csrf
                <div class="row g-2">
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="luarrabps_id" class="form-label">Pilih ITEM ID</label>
                            <select name="luarrabps_id" id="luarrabps_id"
                                class="form-control form-control-sm shadow-sm rounded-3" required
                                onchange="handleItemChange()">
                                <option value="">-- Pilih ITEM ID --</option>
                                @for ($i = 1; $i <= 1000; $i++)
                                    @php $formatted = str_pad($i, 4, '0', STR_PAD_LEFT); @endphp
                                    <option value="ITEM{{ $formatted }}"
                                        {{ old('luarrabps_id') == 'ITEM' . $formatted ? 'selected' : '' }}>
                                        ITEM{{ $formatted }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-2">
                            <label for="needed_by" class="form-label">KEBUTUHAN (No WO)</label>
                            <select id="needed_by_select" name="Needed_by" class="form-select form-select-sm">
                                <option value="">-- Cari atau pilih Work Order --</option>
                                <option value="manual">Input manual di luar WO</option>
                            </select>

                            {{-- input manual muncul kalau user pilih "manual" --}}
                            <input type="text" name="needed_by_input" id="needed_by_input" class="form-control mt-2"
                                placeholder="Tulis manual jika tidak pilih WO" style="display:none;"
                                value="{{ old('needed_by_input') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-2">
                            <label for="Item" class="form-label small text-secondary">ITEM</label>
                            <input type="text" name="Item" class="form-control form-control-sm shadow-sm rounded-3"
                                value="{{ old('Item') }}">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-2">
                            <label for="Qty" class="form-label small text-secondary">Qty Actual</label>
                            <input type="text" name="Qty" class="form-control form-control-sm shadow-sm rounded-3"
                                value="{{ old('Qty') }}">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="mb-2">
                            <label for="Unit" class="form-label small text-secondary">SATUAN</label>
                            <input type="text" name="Unit" class="form-control form-control-sm shadow-sm rounded-3"
                                value="{{ old('Unit') }}">
                        </div>
                    </div>
                </div>
                <div class="row g-2">
                    <div class="col-md-3">
                        <div class="mb-2">
                            <label for="Toko" class="form-label small text-secondary">TOKO</label>
                            <input type="text" name="Toko" class="form-control form-control-sm shadow-sm rounded-3"
                                value="{{ old('Toko') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-2">
                            <label for="Date_actual" class="form-label small text-secondary">Tanggal Actual</label>
                            <input type="date" name="Date_actual"
                                class="form-control form-control-sm shadow-sm rounded-3" value="{{ old('Date_actual') }}"
                                onkeydown="return false">
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
                                value="{{ old('Total') }}">
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
        @endsection
        @push('scripts')
            <script>
                $(document).ready(function() {
                    // Inisialisasi Select2
                    $('#needed_by_select').select2({
                        placeholder: '-- Cari atau pilih Work Order --',
                        allowClear: true,
                        minimumInputLength: 1,
                        ajax: {
                            url: '{{ route('workorders.search') }}',
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    q: params.term
                                };
                            },
                            processResults: function(data) {
                                return {
                                    results: [{
                                            id: 'manual',
                                            text: 'Input manual di luar WO'
                                        },
                                        ...data.map(item => ({
                                            id: item.kode_wo,
                                            text: item.kode_wo
                                        }))
                                    ]
                                };
                            },
                            cache: true
                        },
                        width: '100%'
                    });

                    // Tampilkan input manual kalau pilih "manual"
                    $('#needed_by_select').on('select2:select', function(e) {
                        const val = e.params.data.id;
                        if (val === 'manual') {
                            $('#needed_by_input').show().val('');
                        } else {
                            $('#needed_by_input').hide().val('');
                        }
                    });

                    $('#needed_by_select').on('select2:clear', function() {
                        $('#needed_by_input').hide().val('');
                    });
                });
            </script>
        @endpush
