{{-- @extends('kerangka.master')
@section('title, Edit DirectCost')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">From /</span>Edit Direct Cost Pengajuan</h4>
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

            <form action="{{ route('page.Directcost.update', $data->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-2">
                    <div class="col-md-1">
                        <div class="mb-2">
                            <label for="item_id" class="form-label small text-secondary">ID ITEM</label>
                            <input type="text" name="item_id" class="form-control form-control-sm shadow-sm rounded-3"
                                value="{{ old('item_id', $data->item_id) }}"
                                oninput="this.value = this.value.toUpperCase()">
                        </div>
                    </div>
                </div>
                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label for="Item" class="form-label small text-secondary">ITEM</label>
                            <input type="text" name="Item" class="form-control form-control-sm shadow-sm rounded-3"
                                value="{{ old('Item', $data->Item) }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-2">
                            <label for="Qty" class="form-label small text-secondary">QTY</label>
                            <input type="text" name="Qty" class="form-control form-control-sm shadow-sm rounded-3"
                                value="{{ old('Qty', $data->Qty) }}">
                        </div>
                    </div>
                </div>
                <div class="row g-2">
                    <div class="col-md-1">
                        <div class="mb-2">
                            <label for="Unit" class="form-label small text-secondary">SATUAN</label>
                            <input type="text" name="Unit" class="form-control form-control-sm shadow-sm rounded-3"
                                value="{{ old('Unit', $data->Unit) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label for="Needed_by" class="form-label small text-secondary">KEBUTUHAN</label>
                            <input type="text" name="Needed_by" class="form-control form-control-sm shadow-sm rounded-3"
                                value="{{ old('Needed_by', $data->Needed_by) }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-2">
                        <label for="Date_pengajuan" class="form-label small text-secondary">Tanggal Pengajuan</label>
                        <input type="date" name="Date_pengajuan" class="form-control form-control-sm shadow-sm rounded-3"
                            value="{{ old('Date_pengajuan', $data->Date_pengajuan) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="Total" class="form-label">Total</label>
                        <input type="text" name="Total" class="form-control form-control-sm shadow-sm rounded-3"
                            value="{{ old('Total', $data->Total) }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="Notes" class="form-label">Note</label>
                    <textarea name="Notes" id="Notes" cols="30" rows="10"
                        class="form-control form-control-sm shadow-sm rounded-3">{{ old('Notes', $data->Notes) }}</textarea>
                </div>
        </div>

        <div class="card-footer d-flex justify-content-end gap-3">
            <a href="{{ url('/directp') }}" class="btn btn-dark"><i class="icon-base bx bx-arrow-back icon-sm me-1"></i>
                Back to list</a>
            <button type="submit" class="btn btn-primary"><i class="icon-base bx bx-save icon-sm me-1"></i>
                Save</button>
        </div>
        </form>
    @endsection --}}
{{-- @extends('kerangka.master')

@section('title', 'Edit DirectCost')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Form / </span> Edit Direct Cost Pengajuan
        </h4>

        <div class="card shadow-sm rounded-4 border-0">
            <div class="card-body">

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

                <form action="{{ route('page.Directcost.update', $data->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-2">
                        <div class="col-md-1">
                            <div class="mb-2">
                                <label for="item_id" class="form-label small text-secondary">ID ITEM</label>
                               
                                <input type="text" class="form-control form-control-sm shadow-sm rounded-3 bg-light"
                                    value="{{ $data->item_id }}" disabled>

                                
                                <input type="hidden" name="item_id" value="{{ $data->item_id }}">
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="Item" class="form-label small text-secondary">ITEM</label>
                                <input type="text" name="Item"
                                    class="form-control form-control-sm shadow-sm rounded-3"
                                    value="{{ old('Item', $data->Item) }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-2">
                                <label for="Qty" class="form-label small text-secondary">QTY</label>
                                <input type="text" name="Qty"
                                    class="form-control form-control-sm shadow-sm rounded-3"
                                    value="{{ old('Qty', $data->Qty) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-1">
                            <div class="mb-2">
                                <label for="Unit" class="form-label small text-secondary">SATUAN</label>
                                <input type="text" name="Unit"
                                    class="form-control form-control-sm shadow-sm rounded-3"
                                    value="{{ old('Unit', $data->Unit) }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="needed_by_select" class="form-label fw-bold">Kebutuhan Berdasarkan</label>
                                <select id="needed_by_select" name="needed_by"
                                    class="form-select form-select-sm shadow-sm rounded-3">
                                    <option value="{{ old('needed_by', $data->needed_by) }}">-- Pilih Work Order atau Manual
                                        --</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6" id="manual_input_wrapper" style="display:none;">
                            <div class="mb-2">
                                <label for="needed_by_input" class="form-label fw-bold">Input Manual</label>
                                <input type="text" id="needed_by_input" name="needed_by_manual"
                                    class="form-control form-control-sm shadow-sm rounded-3"
                                    placeholder="Masukkan kebutuhan manual">
                            </div>
                        </div>

                        <div class="row g-2">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="Date_pengajuan" class="form-label small text-secondary">Tanggal
                                        Pengajuan</label>
                                    <input type="date" name="Date_pengajuan"
                                        class="form-control form-control-sm shadow-sm rounded-3"
                                        value="{{ old('Date_pengajuan', $data->Date_pengajuan) }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="Total" class="form-label small text-secondary">TOTAL</label>
                                    <input type="text" name="Total"
                                        class="form-control form-control-sm shadow-sm rounded-3"
                                        value="{{ old('Total', $data->Total) }}">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="Notes" class="form-label small text-secondary">NOTE</label>
                            <textarea name="Notes" id="Notes" cols="30" rows="5"
                                class="form-control form-control-sm shadow-sm rounded-3">{{ old('Notes', $data->Notes) }}</textarea>
                        </div>

                        <div class="card-footer d-flex justify-content-end gap-3 border-0 bg-transparent">
                            <a href="{{ url('/directp') }}" class="btn btn-dark">
                                <i class="bx bx-arrow-back me-1"></i> Back to list
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i> Save
                            </button>
                        </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            $(document).ready(function() {
                // Inisialisasi Select2
                $('#needed_by_select').select2({
                    placeholder: '-- Cari atau pilih Work Order --',
                    allowClear: true,
                    minimumInputLength: 1,
                    ajax: {
                        url: '{{ route('workorders.search') }}', // API Workorder
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
                                            id: item.id, // ✅ gunakan ID numerik
                                            text: item.kode_wo // tampilkan kode WO
                                        };
                                    })
                                ]
                            };
                        },
                        cache: true
                    },
                    width: '100%'
                });

                // Saat user memilih opsi
                $('#needed_by_select').on('select2:select', function(e) {
                    const val = e.params.data.id;
                    const manualInput = $('#manual_input_wrapper');

                    if (val === 'manual') {
                        manualInput.show();
                        $('#needed_by_input').val('');
                    } else {
                        manualInput.hide();
                        $('#needed_by_input').val('');
                    }
                });

                // Saat user menghapus pilihan
                $('#needed_by_select').on('select2:clear', function() {
                    $('#manual_input_wrapper').hide();
                    $('#needed_by_input').val('');
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
    @endpush
@endsection --}}
@extends('kerangka.master')

@section('title', 'Edit Direct Cost Pengajuan')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Form /</span> Edit Direct Cost Pengajuan</h4>

        @include('components.alert')

        <form action="{{ route('page.Directcost.update', $data->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- ITEM ID --}}
            <div class="row g-2">
                <div class="col-md-1">
                    <div class="mb-2">
                        <label for="item_id" class="form-label small text-secondary">ID ITEM</label>

                        <input type="text" class="form-control form-control-sm shadow-sm rounded-3 bg-light"
                            value="{{ $data->item_id }}" disabled>


                        <input type="hidden" name="item_id" value="{{ $data->item_id }}">
                    </div>
                </div>
            </div>


            <div class="row g-2">
                {{-- ITEM --}}
                <div class="col-md-3">
                    <div class="mb-2">
                        <label for="Item" class="form-label small text-secondary">ITEM</label>
                        <input type="text" name="Item" class="form-control form-control-sm shadow-sm rounded-3"
                            value="{{ old('Item', $data->Item) }}">
                    </div>
                </div>

                {{-- QTY --}}
                <div class="col-md-3">
                    <div class="mb-2">
                        <label for="Qty" class="form-label small text-secondary">QTY</label>
                        <input type="number" name="Qty" class="form-control form-control-sm shadow-sm rounded-3"
                            value="{{ old('Qty', $data->Qty) }}">
                    </div>
                </div>
            </div>

            <div class="row g-2">
                {{-- SATUAN --}}
                <div class="col-md-1">
                    <div class="mb-2">
                        <label for="Unit" class="form-label small text-secondary">SATUAN</label>
                        <input type="text" name="Unit" class="form-control form-control-sm shadow-sm rounded-3"
                            value="{{ old('Unit', $data->Unit) }}">
                    </div>
                </div>

                {{-- KEBUTUHAN --}}
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="needed_by_select" class="form-label fw-bold">KEBUTUHAN (No WO)</label>

                        <select id="needed_by_select" name="needed_by" class="form-select form-select-sm">
                            <option value="">-- Pilih atau cari Work Order --</option>
                            <option value="manual"
                                {{ old('needed_by', $data->workorder_id ? '' : 'manual') == 'manual' ? 'selected' : '' }}>
                                Input manual di luar WO
                            </option>
                            @foreach ($workorders as $wo)
                                <option value="{{ $wo->id }}"
                                    {{ old('needed_by') == $wo->id || $data->workorder_id == $wo->id ? 'selected' : '' }}>
                                    {{ $wo->kode_wo }} — {{ $wo->description }}
                                </option>
                            @endforeach
                        </select>

                        {{-- Input manual muncul hanya kalau pilih "manual" --}}
                        <input type="text" name="needed_by_manual" id="needed_by_manual"
                            class="form-control form-control-sm mt-2" placeholder="Tulis kebutuhan manual"
                            value="{{ old('needed_by_manual', $data->Needed_by) }}"
                            style="{{ old('needed_by', $data->workorder_id ? '' : 'manual') == 'manual' ? '' : 'display:none;' }}">
                    </div>
                </div>

                {{-- TANGGAL PENGAJUAN --}}
                <div class="col-md-3">
                    <div class="mb-2">
                        <label for="Date_pengajuan" class="form-label small text-secondary">Tanggal Pengajuan</label>
                        <input type="date" name="Date_pengajuan" class="form-control form-control-sm shadow-sm rounded-3"
                            value="{{ old('Date_pengajuan', $data->Date_pengajuan) }}" onkeydown="return false">
                    </div>
                </div>

                {{-- TOTAL --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="Total" class="form-label">Total</label>
                        <input type="number" name="Total" class="form-control form-control-sm shadow-sm rounded-3"
                            value="{{ old('Total', $data->Total) }}">
                    </div>
                </div>

                {{-- CATATAN --}}
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="Notes" class="form-label">Note</label>
                        <textarea name="Notes" id="Notes" cols="30" rows="4"
                            class="form-control form-control-sm shadow-sm rounded-3">{{ old('Notes', $data->Notes) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- ACTION BUTTONS --}}
            <div class="card-footer d-flex justify-content-end gap-3">
                <a href="{{ url('/directp') }}" class="btn btn-dark">
                    <i class="bx bx-arrow-back me-1"></i> Back
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save me-1"></i> Update
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        //     $(document).ready(function() {
        //         // Inisialisasi Select2 untuk Work Order
        //         $('#needed_by_select').select2({
        //             placeholder: '-- Cari atau pilih Work Order --',
        //             allowClear: true,
        //             minimumInputLength: 1,
        //             ajax: {
        //                 url: '{{ route('workorders.search') }}',
        //                 dataType: 'json',
        //                 delay: 250,
        //                 data: params => ({
        //                     q: params.term
        //                 }),
        //                 processResults: data => ({
        //                     results: [{
        //                         id: 'manual',
        //                         text: 'Input manual diluar WO'
        //                     }, ...data.map(item => ({
        //                         id: item.kode_wo,
        //                         text: item.kode_wo
        //                     }))]
        //                 }),
        //                 cache: true
        //             },
        //             width: '100%'
        //         });

        //         const manualInput = $('#needed_by_input');
        //         const select = $('#needed_by_select');

        //         // Event saat memilih WO
        //         select.on('select2:select', function(e) {
        //             const val = e.params.data.id;
        //             if (val === 'manual') {
        //                 manualInput.show().val('');
        //             } else {
        //                 manualInput.hide().val(val);
        //             }
        //         });

        //         // Saat clear
        //         select.on('select2:clear', function() {
        //             manualInput.hide().val('');
        //         });

        //         // Saat submit form
        //         const form = select.closest('form');
        //         form.on('submit', function() {
        //             if (select.val() === 'manual' && manualInput.val().trim() !== '') {
        //                 const customOption = new Option(manualInput.val(), manualInput.val(), true, true);
        //                 select.append(customOption);
        //             }
        //         });
        //     });
        // 
    </script> --}}
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2 jika kamu pakai plugin itu
            $('#needed_by_select').select2({
                placeholder: '-- Pilih atau cari Work Order --',
                allowClear: true
            });

            // Saat pilihan berubah, tampilkan/hidden input manual
            function toggleManualInput() {
                $('#needed_by_select').on('change', function() {
                    if ($(this).val() === 'manual') {
                        $('#needed_by_manual').show();
                    } else {
                        $('#needed_by_manual').hide().val('');
                    }
                });
            }

            $('#needed_by_select').on('change', toggleManualInput);

            // Jalankan sekali di awal saat halaman dimuat
            toggleManualInput();
        });
    </script>
@endpush
