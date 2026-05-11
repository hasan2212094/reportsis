@extends('kerangka.master')
@section('title', 'Project Plan Monitoring')

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="bg-white p-3 rounded shadow-sm border">

            {{-- HEADER --}}
            <div class="flex justify-between items-start mb-3">

                <div class="flex items-center gap-3">

                    <img src="{{ asset('assets/img/logo.png') }}" class="h-16">

                    <div>

                        <h1 class="text-red-700 text-3xl font-bold uppercase leading-6">
                            PT. SRIBARU INDAH SEJAHTERA
                        </h1>

                        <p class="text-[11px] uppercase leading-3 text-gray-600 mt-1">
                            Manufacturer Of Engineered Transport Equipment,
                            <br>
                            Steel Construction & Fabrication
                        </p>

                    </div>

                </div>

                <div>

                    <h1 class="text-5xl font-bold text-gray-700">
                        Project Plan Monitoring
                    </h1>
                    <a href="{{ route('page.projectmanager.create') }}"
                        style="
        background:#2563eb;
        color:white;
        padding:12px 20px;
        border-radius:16px;
        display:inline-flex;
        align-items:center;
        gap:10px;
        font-weight:bold;
        text-decoration:none;
    ">

                        <span style="font-size:20px;">+</span>

                        <span>Add Project</span>

                    </a>
                </div>

            </div>



            {{-- TABLE --}}
            <div class="overflow-x-auto">

                <table class="w-full border border-black text-center text-sm">

                    <thead>

                        <tr class="font-bold">

                            <th class="border border-black p-1">
                                PROJECT NAME
                            </th>

                            <th class="border border-black p-1">
                                DATE
                            </th>

                            <th class="border border-black p-1">
                                DEADLINE
                            </th>

                            <th class="border border-black p-1">
                                DAYS REMAINING
                            </th>

                            <th class="border border-black p-1">
                                PROJECT STATUS
                            </th>

                            <th class="border border-black bg-cyan-300 p-1">
                                Grand Total %<br>
                                Not Started
                            </th>

                            <th class="border border-black bg-lime-400 p-1">
                                Grand Total %<br>
                                In Progress
                            </th>

                            <th class="border border-black bg-green-600 text-white p-1">
                                Grand Total %<br>
                                Complete
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach ($projectmanagers as $project)
                            @php

                                $today = \Carbon\Carbon::now();

                                $deadline = \Carbon\Carbon::parse($project->target_date);

                                $daysRemaining = $today->diffInDays($deadline, false);

                            @endphp


                            {{-- PROJECT ROW --}}
                            <tr class="font-bold">

                                {{-- PROJECT NAME --}}
                                <td class="border border-black p-2">
                                    {{ $project->workorder->kode_wo ?? '-' }}
                                </td>

                                {{-- DATE --}}
                                <td class="border border-black p-2">
                                    {{ \Carbon\Carbon::parse($project->date_awal)->translatedFormat('d F Y') }}
                                </td>

                                {{-- DEADLINE --}}
                                <td class="border border-black p-2">
                                    {{ \Carbon\Carbon::parse($project->target_date)->translatedFormat('d F Y') }}
                                </td>

                                {{-- DAYS --}}
                                <td class="border border-black p-2">

                                    @if ($daysRemaining < 0)
                                        Overdue {{ abs($daysRemaining) }} Days
                                    @elseif ($daysRemaining == 0)
                                        Deadline Today
                                    @else
                                        {{ $daysRemaining }} Days Left
                                    @endif

                                </td>

                                {{-- STATUS --}}
                                <td class="border border-black p-2 uppercase">

                                    @if ($project->persentase_A == 0)
                                        <span class="text-gray-600">
                                            Not Started
                                        </span>
                                    @elseif ($project->persentase_A == 100)
                                        <span class="text-green-600">
                                            Completed
                                        </span>
                                    @else
                                        <span class="text-blue-600">
                                            In Progress
                                        </span>
                                    @endif

                                </td>


                                {{-- NOT STARTED --}}
                                <td class="border border-black bg-cyan-300 text-3xl font-bold">

                                    @if ($project->persentase_A == 0)
                                        {{ $project->persentase_A }}%
                                    @else
                                        0%
                                    @endif

                                </td>


                                {{-- IN PROGRESS --}}
                                <td class="border border-black bg-lime-400 text-3xl font-bold">

                                    @if ($project->persentase_A > 0 && $project->persentase_A < 100)
                                        {{ $project->persentase_A }}%
                                    @else
                                        0%
                                    @endif

                                </td>


                                {{-- COMPLETE --}}
                                <td class="border border-black bg-green-600 text-white text-3xl font-bold">

                                    @if ($project->persentase_A == 100)
                                        {{ $project->persentase_A }}%
                                    @else
                                        0%
                                    @endif

                                </td>

                            </tr>



                            {{-- UNIT GRAPH ROW --}}
                            <tr>

                                <td colspan="8" class="border border-black p-6 bg-gray-50">

                                    <h1 class="text-4xl font-bold mb-10">

                                        Percentage Progress Job

                                    </h1>


                                    {{-- GRAPH AREA --}}
                                    <div class="flex justify-center items-end gap-10 flex-wrap min-h-[450px]">

                                        @foreach ($project->units as $unit)
                                            @php

                                                $height = max($unit->persentase * 3, 40);

                                                // COLOR
                                                if ($unit->persentase == 0) {
                                                    $color = '#9CA3AF';
                                                } elseif ($unit->persentase == 100) {
                                                    $color = '#22C55E';
                                                } else {
                                                    $color = '#FACC15';
                                                }

                                            @endphp


                                            <div class="flex flex-col items-center">

                                                {{-- BAR AREA --}}
                                                <div class="relative flex items-end h-[320px]">

                                                    {{-- BAR --}}
                                                    <div class="w-32 rounded-t-3xl shadow-2xl transition-all duration-700 flex items-center justify-center"
                                                        style="
                                height: {{ $height }}px;
                                background: {{ $color }};
                            ">

                                                        <span class="text-3xl font-bold text-white">

                                                            {{ $unit->persentase }}%

                                                        </span>

                                                    </div>

                                                </div>


                                                {{-- STATUS --}}
                                                <div class="mt-5 text-lg font-semibold">

                                                    @if ($unit->persentase == 0)
                                                        <span class="text-gray-500">
                                                            Not Started
                                                        </span>
                                                    @elseif ($unit->persentase == 100)
                                                        <span class="text-green-600">
                                                            Completed
                                                        </span>
                                                    @else
                                                        <span class="text-yellow-500">
                                                            In Progress
                                                        </span>
                                                    @endif

                                                </div>


                                                {{-- UNIT --}}
                                                <div class="mt-2 text-2xl font-bold">

                                                    UNIT {{ $unit->unit_no }}

                                                </div>

                                            </div>
                                        @endforeach

                                    </div>

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endsection
