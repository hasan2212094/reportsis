@extends('kerangka.master')
@section('title', 'Project Monitoring List')

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- HEADER --}}
        <div class="flex flex-wrap justify-between items-center mb-6">

            <div>

                <h1 class="text-4xl font-extrabold text-black">
                    Project Monitoring
                </h1>

                <p class="text-gray-600 mt-1">
                    List Monitoring Project Production
                </p>

            </div>



            {{-- BUTTON --}}
            <a href="{{ route('page.projectmanager.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-black px-6 py-3 rounded-2xl font-bold shadow-lg transition-all duration-200">

                + Add Project

            </a>

        </div>




        {{-- TABLE --}}
        <div class="bg-white rounded-3xl shadow-lg border border-gray-300 overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full table-auto border-collapse">

                    <thead>

                        <tr class="bg-slate-800 text-black text-sm">

                            <th class="border border-gray-300 px-4 py-3">
                                NO
                            </th>

                            <th class="border border-gray-300 px-4 py-3">
                                PROJECT NAME
                            </th>

                            <th class="border border-gray-300 px-4 py-3">
                                CUSTOMER
                            </th>

                            <th class="border border-gray-300 px-4 py-3">
                                WO DATE
                            </th>

                            <th class="border border-gray-300 px-4 py-3">
                                DEADLINE
                            </th>

                            <th class="border border-gray-300 px-4 py-3">
                                DAYS REMAINING
                            </th>

                            <th class="border border-gray-300 px-4 py-3">
                                STATUS
                            </th>

                            <th class="border border-gray-300 px-4 py-3">
                                PROGRESS
                            </th>

                        </tr>

                    </thead>



                    <tbody>

                        @forelse ($projectmanagers as $project)
                            @php

                                $today = \Carbon\Carbon::now();

                                $deadline = \Carbon\Carbon::parse($project->target_date);

                                $daysRemaining = $today->diffInDays($deadline, false);

                            @endphp



                            <tr class="hover:bg-gray-50 transition-all duration-150 text-black">

                                {{-- NO --}}
                                <td class="border border-gray-300 px-4 py-3 text-center font-semibold">

                                    {{ $loop->iteration }}

                                </td>



                                {{-- PROJECT NAME --}}
                                <td class="border border-gray-300 px-4 py-3 font-bold">

                                    <a href="{{ route('project.detail', $project->id) }}"
                                        class="text-blue-700 hover:text-blue-900 hover:underline transition-all duration-200">

                                        {{ $project->workorder->kode_wo ?? '-' }}
                                        -
                                        {{ $project->workorder->customer_name ?? '-' }}

                                    </a>

                                </td>



                                {{-- CUSTOMER --}}
                                <td class="border border-gray-300 px-4 py-3">

                                    {{ $project->workorder->customer_name ?? '-' }}

                                </td>



                                {{-- WO DATE --}}
                                <td class="border border-gray-300 px-4 py-3 text-center">

                                    {{ \Carbon\Carbon::parse($project->workorder->wo_date)->translatedFormat('d M Y') }}

                                </td>



                                {{-- DEADLINE --}}
                                <td class="border border-gray-300 px-4 py-3 text-center">

                                    {{ \Carbon\Carbon::parse($project->workorder->pekerjaan_selesai)->translatedFormat('d M Y') }}

                                </td>



                                {{-- DAYS REMAINING --}}
                                <td class="border border-gray-300 px-4 py-3 text-center">

                                    @if ($daysRemaining < 0)
                                        <span class="text-red-600 font-bold">

                                            Overdue

                                        </span>
                                    @elseif ($daysRemaining == 0)
                                        <span class="text-orange-500 font-bold">

                                            Deadline Today

                                        </span>
                                    @else
                                        <span class="text-black">

                                            {{ $daysRemaining }}
                                            Days Left

                                        </span>
                                    @endif

                                </td>



                                {{-- STATUS --}}
                                <td class="border border-gray-300 px-4 py-3 text-center">

                                    @if ($project->persentase_A == 0)
                                        <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-xs font-bold">

                                            NOT STARTED

                                        </span>
                                    @elseif ($project->persentase_A == 100)
                                        <span class="bg-green-200 text-green-700 px-3 py-1 rounded-full text-xs font-bold">

                                            COMPLETED

                                        </span>
                                    @else
                                        <span
                                            class="bg-yellow-200 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold">

                                            IN PROGRESS

                                        </span>
                                    @endif

                                </td>



                                {{-- PROGRESS --}}
                                <td class="border border-gray-300 px-4 py-3">

                                    <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">

                                        <div class="h-4 rounded-full transition-all duration-500"
                                            style="
                                            width: {{ $project->persentase_A }}%;
                                            background:
                                            @if ($project->persentase_A == 100) #22C55E
                                            @elseif($project->persentase_A == 0)
                                                #9CA3AF
                                            @else
                                                #EAB308 @endif
                                        ">

                                        </div>

                                    </div>

                                    <div class="text-center mt-1 text-xs font-bold text-black">

                                        {{ $project->persentase_A }}%

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="8" class="text-center py-10 text-gray-500">

                                    No Project Monitoring Data

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>




        {{-- PAGINATION --}}
        <div class="mt-5">

            {{ $projectmanagers->links() }}

        </div>

    </div>

@endsection
