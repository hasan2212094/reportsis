@extends('kerangka.master')

@section('title', 'Tambah Project Monitoring')

@section('content')

    <div class="min-h-screen py-8">

        <div class="max-w-6xl mx-auto">

            {{-- HERO --}}
            <div
                class="relative overflow-hidden rounded-[36px] bg-gradient-to-r from-indigo-600 via-blue-600 to-cyan-500 shadow-[0_20px_60px_rgba(37,99,235,0.25)] mb-8">

                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">

                    <div>
                        <a href="{{ route('page.Projectmanager.index') }}"
                            class="inline-flex items-center gap-3 px-5 py-3 rounded-2xl bg-white/15 border border-white/20 backdrop-blur-xl text-black font-bold shadow-lg hover:bg-white/25 hover:-translate-y-1 transition-all duration-300 mb-6">

                            <i class="bx bx-arrow-back text-2xl"></i>

                            Back to Project List

                        </a>

                    </div>

                </div>



            </div>



            {{-- ALERT --}}
            @if (session('success'))
                <div class="mb-6 rounded-[28px] border border-green-200 bg-green-100 px-8 py-6 text-green-700 shadow-lg">

                    <div class="flex items-center gap-4">

                        <i class="bx bx-check-circle text-4xl"></i>

                        <div>

                            <h3 class="font-bold text-xl">

                                Success

                            </h3>

                            <p class="mt-1">

                                {{ session('success') }}

                            </p>

                        </div>

                    </div>

                </div>
            @endif



            @if (session('error'))
                <div class="mb-6 rounded-[28px] border border-red-200 bg-red-100 px-8 py-6 text-red-700 shadow-lg">

                    <div class="flex items-center gap-4">

                        <i class="bx bx-error-circle text-4xl"></i>

                        <div>

                            <h3 class="font-bold text-xl">

                                Error

                            </h3>

                            <p class="mt-1">

                                {{ session('error') }}

                            </p>

                        </div>

                    </div>

                </div>
            @endif



            @if ($errors->any())

                <div class="mb-6 rounded-[28px] border border-red-200 bg-red-100 px-8 py-6 text-red-700 shadow-lg">

                    <div class="flex items-start gap-4">

                        <i class="bx bx-error text-4xl mt-1"></i>

                        <div>

                            <h3 class="font-bold text-xl mb-3">

                                Validation Error

                            </h3>

                            <ul class="space-y-2">

                                @foreach ($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach

                            </ul>

                        </div>

                    </div>

                </div>

            @endif



            {{-- MAIN CARD --}}
            <div
                class="backdrop-blur-2xl bg-white/80 border border-white/30 rounded-[36px] shadow-[0_20px_60px_rgba(15,23,42,0.08)] overflow-hidden">

                <form action="{{ route('projectmanager.store') }}" method="POST">

                    @csrf

                    {{-- HEADER --}}
                    <div class="px-10 py-8 border-b border-slate-200/70 bg-gradient-to-r from-slate-50 to-white">

                        <div class="flex items-center gap-4">

                            <div
                                class="w-16 h-16 rounded-2xl bg-gradient-to-r from-indigo-500 to-blue-500 text-white flex items-center justify-center shadow-xl">

                                <i class="bx bx-edit-alt text-3xl"></i>

                            </div>

                            <div>

                                <h2 class="text-3xl font-black text-slate-800">

                                    Project Information

                                </h2>

                                <p class="text-slate-500 mt-1">

                                    Choose workorder and system will auto-fill all required data.

                                </p>

                            </div>

                        </div>

                    </div>



                    {{-- BODY --}}
                    <div class="p-10 lg:p-14">

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

                            {{-- WORKORDER --}}
                            <div class="lg:col-span-2">

                                <label class="block text-sm font-bold uppercase tracking-[0.2em] text-slate-600 mb-4">

                                    Workorder

                                </label>

                                <div class="relative group">

                                    <i
                                        class="bx bx-search absolute left-6 top-1/2 -translate-y-1/2 text-slate-400 text-3xl group-focus-within:text-blue-500 transition-all duration-300">
                                    </i>

                                    <select id="workorderSelect" name="workorder_id" required
                                        class="w-full h-20 pl-16 pr-6 rounded-[28px] border border-slate-200 bg-white text-slate-800 text-xl font-semibold shadow-xl focus:outline-none focus:ring-4 focus:ring-cyan-200 focus:border-cyan-400 transition-all duration-300">

                                        <option value="">
                                            -- Select Workorder --
                                        </option>

                                        @foreach ($workorders as $workorder)
                                            <option value="{{ $workorder->id }}"
                                                data-customer="{{ $workorder->customer_name }}"
                                                data-wo="{{ $workorder->wo_date_fix }}"
                                                data-finish="{{ $workorder->finish_fix }}">

                                                {{ $workorder->kode_wo }}
                                                -
                                                {{ $workorder->customer_name }}

                                            </option>
                                        @endforeach

                                    </select>

                                </div>

                            </div>



                            {{-- CUSTOMER --}}
                            <div>

                                <label class="block text-sm font-bold uppercase tracking-[0.2em] text-slate-600 mb-4">

                                    Customer Name

                                </label>

                                <div class="relative">

                                    <i class="bx bx-user absolute left-6 top-1/2 -translate-y-1/2 text-slate-400 text-3xl">
                                    </i>

                                    <input type="text" id="customer_name" readonly
                                        class="w-full h-20 pl-16 pr-6 rounded-[28px] bg-slate-100 border border-slate-200 text-slate-800 text-xl font-semibold shadow-inner">

                                </div>

                            </div>



                            {{-- WO DATE --}}
                            <div>

                                <label class="block text-sm font-bold uppercase tracking-[0.2em] text-slate-600 mb-4">

                                    WO Date

                                </label>

                                <div class="relative">

                                    <i
                                        class="bx bx-calendar absolute left-6 top-1/2 -translate-y-1/2 text-slate-400 text-3xl">
                                    </i>

                                    <input type="date" id="wo_date" readonly
                                        class="w-full h-20 pl-16 pr-6 rounded-[28px] bg-slate-100 border border-slate-200 text-slate-800 text-xl font-semibold shadow-inner">

                                </div>

                            </div>



                            {{-- FINISH DATE --}}
                            <div>

                                <label class="block text-sm font-bold uppercase tracking-[0.2em] text-slate-600 mb-4">

                                    Finish Date

                                </label>

                                <div class="relative">

                                    <i
                                        class="bx bx-check-circle absolute left-6 top-1/2 -translate-y-1/2 text-slate-400 text-3xl">
                                    </i>

                                    <input type="date" id="pekerjaan_selesai" readonly
                                        class="w-full h-20 pl-16 pr-6 rounded-[28px] bg-slate-100 border border-slate-200 text-slate-800 text-xl font-semibold shadow-inner">

                                </div>

                            </div>



                            {{-- DURATION --}}
                            <div>

                                <label class="block text-sm font-bold uppercase tracking-[0.2em] text-slate-600 mb-4">

                                    Project Duration

                                </label>

                                <div id="duration_box"
                                    class="h-20 rounded-[28px] bg-gradient-to-r from-cyan-500 via-blue-500 to-indigo-600 text-white text-2xl font-black flex items-center px-8 shadow-[0_15px_35px_rgba(59,130,246,0.35)]">

                                    0 Days

                                </div>

                            </div>

                        </div>

                    </div>



                    {{-- FOOTER --}}
                    <div
                        class="px-10 py-8 bg-slate-50/80 border-t border-slate-200 flex flex-col md:flex-row items-center justify-between gap-6">

                        {{-- LEFT INFO --}}
                        <div class="flex items-center gap-4 text-slate-500">

                            <i class="bx bx-info-circle text-2xl"></i>

                            <span class="font-medium">

                                Project will be generated automatically based on selected workorder.

                            </span>

                        </div>



                        {{-- BUTTON --}}
                        <div class="flex items-center gap-5">

                            {{-- CANCEL --}}
                            <a href="{{ route('page.Projectmanager.index') }}"
                                class="px-8 py-4 rounded-[24px] bg-white border border-slate-300 text-slate-700 font-bold shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">

                                Cancel

                            </a>



                            {{-- SAVE --}}
                            <button type="submit"
                                class="group px-10 py-4 rounded-[24px] bg-gradient-to-r from-indigo-600 via-blue-600 to-cyan-500 text-white font-black shadow-[0_15px_35px_rgba(59,130,246,0.35)] hover:scale-105 hover:-translate-y-1 hover:shadow-cyan-300/50 transition-all duration-300">

                                <i class="bx bx-save mr-2 group-hover:rotate-12 transition-all duration-300">
                                </i>

                                Create Project

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>



    {{-- AUTO FILL --}}
    <script>
        const select =
            document.getElementById('workorderSelect');



        select.addEventListener('change', function() {

            const option =
                this.options[this.selectedIndex];



            // CUSTOMER
            document.getElementById('customer_name').value =
                option.dataset.customer || '';



            // WO DATE
            document.getElementById('wo_date').value =
                option.dataset.wo || '';



            // FINISH DATE
            document.getElementById('pekerjaan_selesai').value =
                option.dataset.finish || '';



            // DURATION
            const woDate =
                new Date(option.dataset.wo);

            const finishDate =
                new Date(option.dataset.finish);



            if (option.dataset.wo && option.dataset.finish) {

                const diffTime =
                    finishDate - woDate;

                const diffDays =
                    Math.ceil(diffTime / (1000 * 60 * 60 * 24));



                document.getElementById('duration_box').innerHTML =
                    diffDays + ' Days';

            } else {

                document.getElementById('duration_box').innerHTML =
                    '0 Days';

            }

        });
    </script>

@endsection
