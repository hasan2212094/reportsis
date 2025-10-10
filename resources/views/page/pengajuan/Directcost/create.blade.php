{{-- @extends('kerangka.master')
@section('title, add Direct Cost Pengajuan')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">From /</span>Add Direct Cost Pengajuan</h4>
        <div class="card-body">
            @if (session('error'))
                <div class="alert alert-danger mx-2">
                    {{ session('error') }}
                </div>
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

            <form action="{{ route('page.pengajuan.Directcost.store') }}" method="POST">
                @csrf
                <div class="row g-2">
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="item_id" class="form-label">Pilih ITEM ID</label>
                            <select name="item_id" id="item_id" class="form-control form-control-sm shadow-sm rounded-3"
                                required>
                                <option value="">-- Pilih ITEM ID --</option>
                                @for ($i = 1; $i <= 1000; $i++)
                                    @php
                                        $formatted = str_pad($i, 4, '0', STR_PAD_LEFT); // 0001, 0002, dst.
                                    @endphp
                                    <option value="ITEM{{ $formatted }}">ITEM{{ $formatted }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label for="Item" class="form-label small text-secondary">ITEM</label>
                            <input type="text" name="Item" class="form-control form-control-sm shadow-sm rounded-3"
                                value="{{ old('Item') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-2">
                            <label for="Qty" class="form-label small text-secondary">QTY</label>
                            <input type="text" name="Qty" class="form-control form-control-sm shadow-sm rounded-3"
                                value="{{ old('Qty') }}">
                        </div>
                    </div>
                </div>
                <div class="row g-2">
                    <div class="col-md-1">
                        <div class="mb-2">
                            <label for="Unit" class="form-label small text-secondary">SATUAN</label>
                            <input type="text" name="Unit" class="form-control form-control-sm shadow-sm rounded-3"
                                value="{{ old('Unit') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-2">
                            <label for="needed_by" class="form-label">KEBUTUHAN</label>
                            <select id="needed_by_select" class="form-control form-control-sm shadow-sm rounded-3"
                                name="needed_by_select" onchange="toggleNeededByInput(this)">
                                <option value="">-- Pilih Work Order --</option>
                                @foreach ($workorders as $wo)
                                    <option value="{{ $wo->kode_wo }}">{{ $wo->kode_wo }}</option>
                                @endforeach
                                <option value="manual">Lainnya...</option>
                            </select>

                            <input type="text" id="needed_by_input" name="Needed_by" class="form-control mt-2"
                                placeholder="Tulis manual jika tidak pilih WO" style="display:none;">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-2">
                            <label for="Date_pengajuan" class="form-label small text-secondary">Tanggal Pengajuan</label>
                            <input type="date" name="Date_pengajuan"
                                class="form-control form-control-sm shadow-sm rounded-3"
                                value="{{ old('Date_pengajuan') }}" onkeydown="return false">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="Total" class="form-label">Total</label>
                            <input type="text" name="Total" class="form-control form-control-sm shadow-sm rounded-3"
                                value="{{ old('Total') }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="Notes" class="form-label">Note</label>
                        <textarea name="Notes" id="Notes" cols="30" rows="10"
                            class="form-control form-control-sm shadow-sm rounded-3">{{ old('Notes') }}</textarea>
                    </div>
                </div>

        </div>


        <div class="card-footer d-flex justify-content-end gap-3">
            <a href="{{ url('/directp') }}" class="btn btn-dark"><i class="icon-base bx bx-arrow-back icon-sm me-1"></i>
                Back to list</a>
            <button type="submit" class="btn btn-primary"><i class="icon-base bx bx-save icon-sm me-1"></i>
                Save</button>
        </div>
        </form>
    @endsection

    <script>
        function handleItemChange() {
            const select = document.getElementById('item_id');
            const value = select.value;
            if (value.startsWith('ITEM')) {
                console.log('ITEM ID baru dipilih:', value);
            }
        }

        function toggleNeededByInput(select) {
            const input = document.getElementById('needed_by_input');
            if (select.value === 'manual') {
                input.style.display = 'block';
                input.value = '';
            } else if (select.value) {
                input.style.display = 'none';
                input.value = select.value;
            } else {
                input.style.display = 'none';
                input.value = '';
            }
        }
        document.addEventListener("DOMContentLoaded", function() {
            const input = document.getElementById("Date_pengajuan");
            if (!input.value) {
                const today = new Date().toISOString().split('T')[0];
                input.value = today;
            }
        });
    </script> --}}
{{-- @extends('kerangka.master')

@section('title', 'Add Direct Cost Pengajuan')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Form /</span> Add Direct Cost Pengajuan</h4>

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

        <form action="{{ route('page.pengajuan.Directcost.store') }}" method="POST">
            @csrf
            <div class="row g-2">
       
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="item_id" class="form-label">Pilih ITEM ID</label>
                        <select name="item_id" id="item_id" class="form-control form-control-sm shadow-sm rounded-3"
                            required onchange="handleItemChange()">
                            <option value="">-- Pilih ITEM ID --</option>
                            @for ($i = 1; $i <= 1000; $i++)
                                @php $formatted = str_pad($i, 4, '0', STR_PAD_LEFT); @endphp
                                <option value="ITEM{{ $formatted }}"
                                    {{ old('item_id') == 'ITEM' . $formatted ? 'selected' : '' }}>ITEM{{ $formatted }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>

            <div class="row g-2">
            
                <div class="col-md-3">
                    <div class="mb-2">
                        <label for="Item" class="form-label small text-secondary">ITEM</label>
                        <input type="text" name="Item" class="form-control form-control-sm shadow-sm rounded-3"
                            value="{{ old('Item') }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="mb-2">
                        <label for="Qty" class="form-label small text-secondary">QTY</label>
                        <input type="number" name="Qty" class="form-control form-control-sm shadow-sm rounded-3"
                            value="{{ old('Qty') }}">
                    </div>
                </div>
            </div>

            <div class="row g-2">
                <div class="col-md-1">
                    <div class="mb-2">
                        <label for="Unit" class="form-label small text-secondary">SATUAN</label>
                        <input type="text" name="Unit" class="form-control form-control-sm shadow-sm rounded-3"
                            value="{{ old('Unit') }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="mb-2">
                        <label for="needed_by" class="form-label">KEBUTUHAN (No WO)</label>
                        <select id="needed_by_select" name="Needed_by" class="form-select form-select-sm">
                            <option value="">-- Cari atau pilih Work Order --</option>
                            <option value="manual">Input manual diluar WO</option>
                        </select>
                        <input type="text" id="needed_by_input" class="form-control mt-2"
                            placeholder="Tulis manual jika tidak pilih WO" style="display:none;"
                            value="{{ old('Needed_by') }}">
                    </div>
                </div>

         
                <div class="col-md-3">
                    <div class="mb-2">
                        <label for="Date_pengajuan" class="form-label small text-secondary">Tanggal Pengajuan</label>
                        <input type="date" name="Date_pengajuan" class="form-control form-control-sm shadow-sm rounded-3"
                            value="{{ old('Date_pengajuan') }}" onkeydown="return false">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="Total" class="form-label">Total</label>
                        <input type="number" name="Total" class="form-control form-control-sm shadow-sm rounded-3"
                            value="{{ old('Total') }}">
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="Notes" class="form-label">Note</label>
                        <textarea name="Notes" id="Notes" cols="30" rows="4"
                            class="form-control form-control-sm shadow-sm rounded-3">{{ old('Notes') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-end gap-3">
                <a href="{{ url('/directp') }}" class="btn btn-dark">
                    <i class="icon-base bx bx-arrow-back icon-sm me-1"></i> Back to list
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="icon-base bx bx-save icon-sm me-1"></i> Save
                </button>
            </div>
        </form>
    </div>
@endsection
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        function handleItemChange() {
            const select = document.getElementById('item_id');
            const value = select.value;
            if (value.startsWith('ITEM')) {
                console.log('ITEM ID baru dipilih:', value);
            }
        }

        $(document).ready(function() {
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
                                    text: 'Input manual diluar WO'
                                },
                                ...data.map(function(item) {
                                    return {
                                        id: item.kode_wo,
                                        text: item.kode_wo
                                    };
                                })
                            ]
                        };
                    },
                    cache: true
                },
                width: '100%'
            });

            // Event Select2 saat memilih opsi
            $('#needed_by_select').on('select2:select', function(e) {
                const input = $('#needed_by_input');
                const val = e.params.data.id;

                if (val === 'manual') {
                    input.show().val('');
                } else {
                    input.hide().val(val);
                }
            });

            // Saat opsi dihapus
            $('#needed_by_select').on('select2:clear', function() {
                $('#needed_by_input').hide().val('');
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const select = document.getElementById('needed_by_select');
            const manualInput = document.getElementById('needed_by_input');

            // Jika user pilih "manual", tampilkan input text
            select.addEventListener('change', function() {
                if (this.value === 'manual') {
                    manualInput.style.display = 'block';
                } else {
                    manualInput.style.display = 'none';
                    manualInput.value = ''; // hapus nilai jika user ganti pilihan
                }
            });

            // Saat form di-submit
            const form = select.closest('form');
            form.addEventListener('submit', function() {
                if (select.value === 'manual' && manualInput.value.trim() !== '') {
                    // buat option baru agar terkirim ke backend
                    const customOption = new Option(manualInput.value, manualInput.value, true, true);
                    select.add(customOption);
                }
            });
        });
    </script>
@endpush --}}
@extends('kerangka.master')

@section('title', 'Add Direct Cost Pengajuan')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Form /</span> Add Direct Cost Pengajuan</h4>

        {{-- Alert Section --}}
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

        <form action="{{ route('page.pengajuan.Directcost.store') }}" method="POST">
            @csrf
            <div class="row g-2">
                {{-- ITEM ID --}}
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="item_id" class="form-label">ITEM ID (Otomatis)</label>
                        <input type="text" name="item_id" id="item_id"
                            class="form-control form-control-sm shadow-sm rounded-3 bg-light" value="{{ $newItemId }}"
                            readonly>
                    </div>
                </div>
                <div class="row g-2">
                    {{-- ITEM --}}
                    <div class="col-md-3">
                        <div class="mb-2">
                            <label for="Item" class="form-label small text-secondary">ITEM</label>
                            <input type="text" name="Item" class="form-control form-control-sm shadow-sm rounded-3"
                                value="{{ old('Item') }}">
                        </div>
                    </div>

                    {{-- QTY --}}
                    <div class="col-md-3">
                        <div class="mb-2">
                            <label for="Qty" class="form-label small text-secondary">QTY</label>
                            <input type="number" name="Qty" class="form-control form-control-sm shadow-sm rounded-3"
                                value="{{ old('Qty') }}">
                        </div>
                    </div>
                </div>

                <div class="row g-2">
                    {{-- SATUAN --}}
                    <div class="col-md-1">
                        <div class="mb-2">
                            <label for="Unit" class="form-label small text-secondary">SATUAN</label>
                            <input type="text" name="Unit" class="form-control form-control-sm shadow-sm rounded-3"
                                value="{{ old('Unit') }}">
                        </div>
                    </div>

                    {{-- KEBUTUHAN / WO --}}
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

                    {{-- TANGGAL PENGAJUAN --}}
                    <div class="col-md-3">
                        <div class="mb-2">
                            <label for="Date_pengajuan" class="form-label small text-secondary">Tanggal Pengajuan</label>
                            <input type="date" name="Date_pengajuan"
                                class="form-control form-control-sm shadow-sm rounded-3" value="{{ old('Date_pengajuan') }}"
                                onkeydown="return false">
                        </div>
                    </div>

                    {{-- TOTAL --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="Total" class="form-label">Total</label>
                            <input type="number" name="Total" class="form-control form-control-sm shadow-sm rounded-3"
                                value="{{ old('Total') }}">
                        </div>
                    </div>

                    {{-- CATATAN --}}
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="Notes" class="form-label">Note</label>
                            <textarea name="Notes" id="Notes" cols="30" rows="4"
                                class="form-control form-control-sm shadow-sm rounded-3">{{ old('Notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-end gap-3">
                    <a href="{{ url('/directp') }}" class="btn btn-dark">
                        <i class="icon-base bx bx-arrow-back icon-sm me-1"></i> Back to list
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="icon-base bx bx-save icon-sm me-1"></i> Save
                    </button>
                </div>
        </form>
    </div>
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

        function checkItemAvailability() {
            const select = document.getElementById('item_id');
            const itemId = select.value;
            if (!itemId) return;

            console.log("Cek item:", itemId);

            fetch(`/check-item/${itemId}`)
                .then(response => response.json())
                .then(data => {
                    console.log("Hasil:", data);

                    if (data.exists) {
                        const currentIndex = select.selectedIndex;
                        const nextIndex = currentIndex + 1;
                        if (nextIndex < select.options.length) {
                            select.selectedIndex = nextIndex;
                            console.log(`Pindah ke ${select.value}`);
                        }
                    } else {
                        console.log(`${itemId} belum digunakan`);
                    }
                })
                .catch(err => console.error("Fetch error:", err));
        }
    </script>
@endpush
