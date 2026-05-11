@extends('layouts.app')

@section('content')

    <div class="max-w-5xl mx-auto mt-10">

        <div class="bg-white shadow-lg rounded-xl border border-gray-200">

            {{-- HEADER --}}
            <div class="border-b px-6 py-4">

                <h2 class="text-xl font-semibold text-gray-800">
                    Tambah Pekerjaan
                </h2>

                <p class="text-sm text-gray-500">
                    Silakan isi data pekerjaan baru
                </p>

            </div>


            {{-- SUCCESS --}}
            @if (session('success'))
                <div class="m-4 p-4 bg-green-100 text-green-700 rounded-lg shadow">

                    {{ session('success') }}

                </div>
            @endif


            {{-- ERROR --}}
            @if (session('error'))
                <div class="m-4 p-4 bg-red-100 text-red-700 rounded-lg shadow">

                    {{ session('error') }}

                </div>
            @endif


            {{-- VALIDATION ERROR --}}
            @if ($errors->any())
                <div class="m-4 p-4 bg-red-100 text-red-700 rounded-lg shadow">

                    <ul class="list-disc ml-5">

                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>
            @endif



            {{-- FORM --}}
            <form action="{{ route('projectmanager.store') }}" method="POST">

                @csrf

                <div class="p-6">

                    {{-- WORKORDER --}}
                    <div class="mb-5">

                        <label class="block text-sm font-medium text-gray-700 mb-1">

                            Workorder

                        </label>

                        <select id="workorderSelect" name="workorder_id" class="w-full mt-1 px-4 py-2 border rounded-lg">

                            <option value="">
                                -- Pilih WO --
                            </option>

                            @foreach ($workorders as $workorder)
                                <option value="{{ $workorder->id }}" data-customer="{{ $workorder->customer_name }}"
                                    data-wo="{{ $workorder->wo_date_fix }}" data-finish="{{ $workorder->finish_fix }}">

                                    {{ $workorder->kode_wo }}

                                </option>
                            @endforeach

                        </select>

                    </div>



                    {{-- DETAIL WO --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                        {{-- CUSTOMER NAME --}}
                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-1">

                                Customer Name

                            </label>

                            <input type="text" id="customer_name" class="w-full px-4 py-2 border rounded-lg bg-gray-100"
                                readonly>

                        </div>



                        {{-- WO DATE --}}
                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-1">

                                WO Date

                            </label>

                            <input type="date" id="wo_date" name="date_awal"
                                class="w-full px-4 py-2 border rounded-lg bg-gray-100" readonly>

                        </div>



                        {{-- PEKERJAAN SELESAI --}}
                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-1">

                                Pekerjaan Selesai

                            </label>

                            <input type="date" id="pekerjaan_selesai" name="target_date"
                                class="w-full px-4 py-2 border rounded-lg bg-gray-100" readonly>

                        </div>

                    </div>

                </div>



                {{-- FOOTER --}}
                <div class="flex justify-end gap-3 px-6 py-4 border-t bg-gray-50 rounded-b-xl">

                    <a href="{{ route('page.Projectmanager.index') }}"
                        class="px-4 py-2 rounded-lg border text-gray-600 hover:bg-gray-100">

                        Batal

                    </a>

                    <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow">

                        Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>



    {{-- SCRIPT AUTO DETAIL WO --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const workorderSelect =
                document.getElementById('workorderSelect');

            workorderSelect.addEventListener('change', function() {

                let selected =
                    this.options[this.selectedIndex];

                // CUSTOMER
                document.getElementById('customer_name').value =
                    selected.dataset.customer || '';

                // WO DATE
                document.getElementById('wo_date').value =
                    selected.dataset.wo || '';

                // PEKERJAAN SELESAI
                document.getElementById('pekerjaan_selesai').value =
                    selected.dataset.finish || '';

            });

        });
    </script>

@endsection
