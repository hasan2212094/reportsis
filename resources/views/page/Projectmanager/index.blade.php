@extends('kerangka.master')
@section('title', 'Project Plan Monitoring')

@section('content')

    <div class="container-fluid py-4">

        <div class="bg-white rounded-3xl shadow-lg border border-gray-300 p-4">

            {{-- HEADER --}}
            <div class="flex flex-wrap justify-between items-center gap-4 mb-6">

                <div>

                    <h1 class="text-3xl font-extrabold text-black uppercase">
                        PT. SRIBARU INDAH SEJAHTERA
                    </h1>

                    <p class="text-sm text-black">
                        Manufacturer Of Engineered Transport Equipment
                    </p>

                </div>

                <div>

                    <h1 class="text-4xl font-bold text-black">
                        Project Plan Monitoring
                    </h1>

                </div>

            </div>



            {{-- PROJECT TABLE --}}
            <div class="w-full overflow-hidden rounded-2xl border border-gray-300">

                <table class="w-full table-auto border-collapse text-[12px]">

                    <thead>

                        <tr class="bg-slate-800">

                            <th class="border border-gray-300 px-3 py-2">
                                PROJECT NAME
                            </th>

                            <th class="border border-gray-300 px-3 py-2">
                                DATE
                            </th>

                            <th class="border border-gray-300 px-3 py-2">
                                DEADLINE
                            </th>

                            <th class="border border-gray-300 px-3 py-2">
                                DAYS REMAINING
                            </th>

                            <th class="border border-gray-300 px-3 py-2">
                                PROJECT STATUS
                            </th>

                            <th class="border border-gray-300 px-3 py-2 bg-cyan-500">
                                NOT STARTED
                            </th>

                            <th class="border border-gray-300 px-3 py-2 bg-yellow-500">
                                IN PROGRESS
                            </th>

                            <th class="border border-gray-300 px-3 py-2 bg-green-600">
                                COMPLETE
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
                            <tr class="bg-white text-black font-semibold">

                                <td class="border border-gray-300 px-3 py-3 text-left">

                                    {{ $project->workorder->kode_wo ?? '-' }}
                                    -
                                    {{ $project->workorder->customer_name ?? '-' }}

                                </td>



                                <td class="border border-gray-300 px-3 py-3">

                                    {{ \Carbon\Carbon::parse($project->date_awal)->translatedFormat('d M Y') }}

                                </td>



                                <td class="border border-gray-300 px-3 py-3">

                                    {{ \Carbon\Carbon::parse($project->target_date)->translatedFormat('d M Y') }}

                                </td>



                                <td class="border border-gray-300 px-3 py-3">

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



                                <td class="border border-gray-300 px-3 py-3">

                                    @if ($project->persentase_A == 0)
                                        <span class="text-gray-700 font-bold">

                                            Not Started

                                        </span>
                                    @elseif ($project->persentase_A == 100)
                                        <span class="text-green-700 font-bold">

                                            Completed

                                        </span>
                                    @else
                                        <span class="text-yellow-700 font-bold">

                                            In Progress

                                        </span>
                                    @endif

                                </td>



                                <td class="border border-gray-300 px-3 py-3 bg-cyan-100 text-black font-bold">

                                    @if ($project->persentase_A == 0)
                                        {{ $project->persentase_A }}%
                                    @else
                                        0%
                                    @endif

                                </td>



                                <td class="border border-gray-300 px-3 py-3 bg-yellow-100 text-black font-bold">

                                    @if ($project->persentase_A > 0 && $project->persentase_A < 100)
                                        {{ $project->persentase_A }}%
                                    @else
                                        0%
                                    @endif

                                </td>



                                <td class="border border-gray-300 px-3 py-3 bg-green-100 text-black font-bold">

                                    @if ($project->persentase_A == 100)
                                        {{ $project->persentase_A }}%
                                    @else
                                        0%
                                    @endif

                                </td>

                            </tr>




                            {{-- GRAPH --}}
                            <tr>

                                <td colspan="8" class="bg-gray-50 border border-gray-300 p-6">

                                    <h1 class="text-2xl font-bold text-black text-center mb-6">

                                        UNIT PROGRESS

                                    </h1>



                                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-5">

                                        @foreach ($project->units as $unit)
                                            @php

                                                $height = max($unit->persentase * 2, 40);

                                                if ($unit->persentase == 0) {
                                                    $color = '#9CA3AF';
                                                } elseif ($unit->persentase == 100) {
                                                    $color = '#22C55E';
                                                } else {
                                                    $color = '#EAB308';
                                                }

                                            @endphp



                                            <div
                                                class="bg-white rounded-2xl shadow border border-gray-300 p-4 flex flex-col items-center">

                                                <div class="h-[220px] flex items-end">

                                                    <div class="w-20 rounded-t-2xl flex items-center justify-center transition-all duration-700"
                                                        style="
                                                        height: {{ $height }}px;
                                                        background: {{ $color }};
                                                    ">

                                                        <span class="text-black text-lg font-bold">

                                                            {{ $unit->persentase }}%

                                                        </span>

                                                    </div>

                                                </div>



                                                <div class="mt-4 text-black font-bold">

                                                    UNIT {{ $unit->unit_no }}

                                                </div>

                                            </div>
                                        @endforeach

                                    </div>

                                </td>

                            </tr>




                            {{-- TASK TABLE --}}
                            <tr>

                                <td colspan="8" class="bg-white border border-gray-300 p-4">

                                    {{-- HEADER --}}
                                    <div class="flex justify-between items-center mb-4">

                                        <div>

                                            <h1 class="text-2xl font-bold text-black">

                                                Task Monitoring

                                            </h1>

                                            <p class="text-black text-sm">

                                                Input Activity Progress

                                            </p>

                                        </div>



                                        {{-- BUTTON --}}
                                        <button onclick="addTaskRow({{ $project->id }})" type="button"
                                            class="bg-blue-600 hover:bg-blue-700 px-5 py-2 rounded-xl font-bold shadow">

                                            + Add Task

                                        </button>

                                    </div>




                                    {{-- FORM --}}
                                    <form action="{{ route('projectmanager.task.store') }}" method="POST">

                                        @csrf

                                        <input type="hidden" name="project_manager_id" value="{{ $project->id }}">



                                        <div class="w-full overflow-auto rounded-2xl border border-gray-300">

                                            <table class="w-full table-fixed border-collapse text-[11px]">

                                                <thead>

                                                    <tr class="bg-slate-800 text-black">

                                                        <th class="border border-gray-300 p-2 w-[180px]">
                                                            TASK NAME
                                                        </th>

                                                        <th class="border border-gray-300 p-2 w-[120px]">
                                                            ASSIGNED TO
                                                        </th>

                                                        <th class="border border-gray-300 p-2 w-[220px]">
                                                            DETAIL ACTIVITY
                                                        </th>

                                                        <th class="border border-gray-300 p-2 w-[120px]">
                                                            BL START
                                                        </th>

                                                        <th class="border border-gray-300 p-2 w-[120px]">
                                                            BL FINISH
                                                        </th>

                                                        <th class="border border-gray-300 p-2 w-[120px]">
                                                            ACT START
                                                        </th>

                                                        <th class="border border-gray-300 p-2 w-[90px]">
                                                            DURATION
                                                        </th>



                                                        {{-- AUTO UNIT --}}
                                                        @for ($i = 1; $i <= $project->workorder->quantity; $i++)
                                                            <th class="border border-gray-300 p-2 bg-cyan-500 w-[90px]">

                                                                UNIT {{ $i }}

                                                            </th>
                                                        @endfor



                                                        <th class="border border-gray-300 p-2 w-[100px]">
                                                            PRIORITY
                                                        </th>

                                                        <th class="border border-gray-300 p-2 w-[90px]">
                                                            %
                                                        </th>

                                                    </tr>

                                                </thead>




                                                {{-- BODY --}}
                                                <tbody id="taskTable{{ $project->id }}">



                                                    {{-- EXISTING TASK --}}
                                                    @foreach ($project->tasks as $task)
                                                        <tr class="bg-white text-black">

                                                            <td class="border border-gray-300 p-2">

                                                                {{ $task->task_name }}

                                                            </td>

                                                            <td class="border border-gray-300 p-2">

                                                                {{ $task->pic }}

                                                            </td>

                                                            <td class="border border-gray-300 p-2 text-left">

                                                                {{ $task->activity_detail }}

                                                            </td>

                                                            <td class="border border-gray-300 p-2">

                                                                {{ $task->bl_start }}

                                                            </td>

                                                            <td class="border border-gray-300 p-2">

                                                                {{ $task->bl_finish }}

                                                            </td>

                                                            <td class="border border-gray-300 p-2">

                                                                {{ $task->act_start }}

                                                            </td>

                                                            <td class="border border-gray-300 p-2 font-bold">

                                                                {{ $task->duration }}

                                                            </td>



                                                            {{-- UNIT --}}
                                                            @for ($i = 1; $i <= $project->workorder->quantity; $i++)
                                                                <td
                                                                    class="border border-gray-300 p-2 bg-cyan-100 text-black font-bold">

                                                                    UNIT {{ $i }}

                                                                </td>
                                                            @endfor



                                                            <td class="border border-gray-300 p-2">

                                                                {{ $task->priority }}

                                                            </td>

                                                            <td class="border border-gray-300 p-2 font-bold text-blue-700">

                                                                {{ $task->percentage }}%

                                                            </td>

                                                        </tr>
                                                    @endforeach




                                                    {{-- DEFAULT INPUT --}}
                                                    <tr class="bg-white text-black">

                                                        <td class="border border-gray-300 p-1">

                                                            <input type="text" name="task_name[]"
                                                                class="w-full rounded border border-gray-300 px-2 py-1 text-black text-[11px]">

                                                        </td>



                                                        <td class="border border-gray-300 p-1">

                                                            <input type="text" name="pic[]"
                                                                class="w-full rounded border border-gray-300 px-2 py-1 text-black text-[11px]">

                                                        </td>



                                                        <td class="border border-gray-300 p-1">

                                                            <textarea name="activity_detail[]" rows="1"
                                                                class="w-full rounded border border-gray-300 px-2 py-1 text-black text-[11px]"></textarea>

                                                        </td>



                                                        <td class="border border-gray-300 p-1">

                                                            <input type="date" name="bl_start[]"
                                                                class="w-full rounded border border-gray-300 px-2 py-1 text-black text-[11px]">

                                                        </td>



                                                        <td class="border border-gray-300 p-1">

                                                            <input type="date" name="bl_finish[]"
                                                                class="w-full rounded border border-gray-300 px-2 py-1 text-black text-[11px]">

                                                        </td>



                                                        <td class="border border-gray-300 p-1">

                                                            <input type="date" name="act_start[]"
                                                                class="w-full rounded border border-gray-300 px-2 py-1 text-black text-[11px]">

                                                        </td>



                                                        <td class="border border-gray-300 p-1">

                                                            <input type="number" name="duration[]" value="0"
                                                                class="w-full rounded border border-gray-300 px-2 py-1 text-black text-[11px]">

                                                        </td>



                                                        {{-- AUTO UNIT --}}
                                                        @for ($i = 1; $i <= $project->workorder->quantity; $i++)
                                                            <td
                                                                class="border border-gray-300 p-2 bg-cyan-100 text-black font-bold">

                                                                UNIT {{ $i }}

                                                            </td>
                                                        @endfor



                                                        <td class="border border-gray-300 p-1">

                                                            <select name="priority[]"
                                                                class="w-full rounded border border-gray-300 px-2 py-1 text-black text-[11px]">

                                                                <option value="High">
                                                                    High
                                                                </option>

                                                                <option value="Medium">
                                                                    Medium
                                                                </option>

                                                                <option value="Low">
                                                                    Low
                                                                </option>

                                                            </select>

                                                        </td>



                                                        <td class="border border-gray-300 p-1">

                                                            <input type="number" name="percentage[]" value="0"
                                                                class="w-full rounded border border-gray-300 px-2 py-1 text-black text-[11px]">

                                                        </td>

                                                    </tr>

                                                </tbody>

                                            </table>

                                        </div>



                                        {{-- SAVE --}}
                                        <div class="mt-5 text-end">

                                            <button type="submit"
                                                class="bg-green-600 hover:bg-green-700 px-6 py-3 rounded-xl font-bold shadow-lg">

                                                Save Task

                                            </button>

                                        </div>

                                    </form>

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>



    {{-- SCRIPT --}}
    <script>
        function addTaskRow(projectId) {
            let table =
                document.getElementById(
                    'taskTable' + projectId
                );

            let qty =
                {{ $project->workorder->quantity ?? 0 }};

            let unitColumns = '';

            for (let i = 1; i <= qty; i++) {

                unitColumns += `

            <td class="border border-gray-300 p-2 bg-cyan-100 text-black font-bold">

                UNIT ${i}

            </td>

        `;
            }


            let row = `

        <tr class="bg-white text-black">

            <td class="border border-gray-300 p-1">

                <input
                    type="text"
                    name="task_name[]"
                    class="w-full rounded border border-gray-300 px-2 py-1 text-black text-[11px]">

            </td>

            <td class="border border-gray-300 p-1">

                <input
                    type="text"
                    name="pic[]"
                    class="w-full rounded border border-gray-300 px-2 py-1 text-black text-[11px]">

            </td>

            <td class="border border-gray-300 p-1">

                <textarea
                    name="activity_detail[]"
                    rows="1"
                    class="w-full rounded border border-gray-300 px-2 py-1 text-black text-[11px]"></textarea>

            </td>

            <td class="border border-gray-300 p-1">

                <input
                    type="date"
                    name="bl_start[]"
                    class="w-full rounded border border-gray-300 px-2 py-1 text-black text-[11px]">

            </td>

            <td class="border border-gray-300 p-1">

                <input
                    type="date"
                    name="bl_finish[]"
                    class="w-full rounded border border-gray-300 px-2 py-1 text-black text-[11px]">

            </td>

            <td class="border border-gray-300 p-1">

                <input
                    type="date"
                    name="act_start[]"
                    class="w-full rounded border border-gray-300 px-2 py-1 text-black text-[11px]">

            </td>

            <td class="border border-gray-300 p-1">

                <input
                    type="number"
                    name="duration[]"
                    value="0"
                    class="w-full rounded border border-gray-300 px-2 py-1 text-black text-[11px]">

            </td>

            ${unitColumns}

            <td class="border border-gray-300 p-1">

                <select
                    name="priority[]"
                    class="w-full rounded border border-gray-300 px-2 py-1 text-black text-[11px]">

                    <option value="High">
                        High
                    </option>

                    <option value="Medium">
                        Medium
                    </option>

                    <option value="Low">
                        Low
                    </option>

                </select>

            </td>

            <td class="border border-gray-300 p-1">

                <input
                    type="number"
                    name="percentage[]"
                    value="0"
                    class="w-full rounded border border-gray-300 px-2 py-1 text-black text-[11px]">

            </td>

        </tr>

    `;

            table.insertAdjacentHTML(
                'beforeend',
                row
            );
        }
    </script>

@endsection
