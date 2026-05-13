@extends('kerangka.master')

@section('title', 'Project Plan Monitoring')

@section('content')

    <div class="container-fluid py-4">

        <div style="
    display:flex;
    justify-content:flex-end;
    margin-bottom:5px;
">

            <a href="{{ route('page.Projectmanager.index') }}"
                style="
            display:inline-flex;
            align-items:center;
            gap:14px;

            padding:14px 24px;

            border-radius:20px;

            background:linear-gradient(
                135deg,
                #2563eb,
                #06b6d4
            );

            color:white;
            font-weight:700;
            font-size:14px;

            text-decoration:none;

            box-shadow:
                0 10px 25px rgba(37,99,235,0.35);

            transition:all 0.3s ease;
        "
                onmouseover="
            this.style.transform='scale(1.05)';
            this.style.boxShadow='0 15px 35px rgba(6,182,212,0.45)';
        "
                onmouseout="
            this.style.transform='scale(1)';
            this.style.boxShadow='0 10px 25px rgba(37,99,235,0.35)';
        ">

                {{-- ICON --}}
                <div
                    style="
            width:42px;
            height:42px;

            border-radius:14px;

            background:rgba(255,255,255,0.2);

            display:flex;
            align-items:center;
            justify-content:center;
        ">

                    <i class="bx bx-home-alt"
                        style="
                    font-size:24px;
                    color:white;
                ">

                    </i>

                </div>

                {{-- TEXT --}}
                <div style="line-height:1.2;">

                    <div style="
                font-size:11px;
                opacity:0.8;
            ">

                        Back To

                    </div>

                    <div style="
                font-size:14px;
                font-weight:700;
            ">

                        Project List

                    </div>

                </div>

            </a>

        </div>

        {{-- TITLE --}}
        <div class="mb-6">

            <h1 class="text-5xl font-black text-slate-700 tracking-tight">

                Project Plan Monitoring

            </h1>

        </div>

        {{-- PROJECT TABLE --}}
        <div class="inline-block min-w-full rounded-2xl border border-gray-300 bg-white">

            <table class="table-auto border-collapse text-[12px] w-max min-w-full">

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

                    @php

                        $currentDate = \Carbon\Carbon::now();

                        $finishDate = \Carbon\Carbon::parse($project->workorder->pekerjaan_selesai);

                        $daysRemaining = $currentDate->diffInDays($finishDate, false) + 1;

                    @endphp



                    {{-- PROJECT ROW --}}
                    <tr class="bg-white text-black font-semibold">

                        <td class="border border-gray-300 px-3 py-3 text-left">

                            <a href="{{ route('project.detail', $project->id) }}">

                                {{ $project->workorder->kode_wo }}
                                -
                                {{ $project->workorder->customer_name }}

                            </a>

                        </td>



                        <td class="border border-gray-300 px-3 py-3">

                            {{ \Carbon\Carbon::parse($project->workorder->wo_date)->translatedFormat('d M Y') }}

                        </td>



                        <td class="border border-gray-300 px-3 py-3">

                            {{ \Carbon\Carbon::parse($project->workorder->pekerjaan_selesai)->translatedFormat('d M Y') }}

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

                            @if ($projectStatus == 'NOT STARTED')
                                <span class="text-gray-700 font-bold">

                                    Not Started

                                </span>
                            @elseif ($projectStatus == 'COMPLETE')
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

                            {{ $notStartedPercent }}%

                        </td>



                        <td class="border border-gray-300 px-3 py-3 bg-yellow-100 text-black font-bold">

                            {{ $inProgressPercent }}%

                        </td>



                        <td class="border border-gray-300 px-3 py-3 bg-green-100 text-black font-bold">

                            {{ $completePercent }}%

                        </td>

                    </tr>

                    {{-- UNIT GRAPH --}}
                    <tr>

                        <td colspan="{{ 9 + $project->workorder->quantity }}" class="bg-white border border-gray-300 p-8">

                            <h1 class="text-4xl font-bold text-center text-black mb-10">

                                Percentage Progress Job

                            </h1>

                            <div
                                style="
                                    width:100%;
                                    overflow-x:auto;
                                    overflow-y:hidden;
                                ">

                                <div
                                    style="
                                    width:{{ max(600, $project->workorder->quantity * 140) }}px;
                                    height:500px;
                                ">

                                    <canvas id="unitChart"></canvas>

                                </div>

                            </div>

                        </td>

                    </tr>

                    {{-- TASK TABLE --}}
                    <tr>

                        <td colspan="{{ 9 + $project->workorder->quantity }}" class="bg-white border border-gray-300 p-4">

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



                                <div
                                    style="
                                    width:100%;
                                    overflow-x:auto;
                                    overflow-y:hidden;
                                    border:1px solid #d1d5db;
                                    border-radius:18px;
                                ">

                                    <table
                                        style="
                                        border-collapse:collapse;
                                        width:max-content;
                                        font-size:11px;
                                    ">
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

                                                <th class="border border-gray-300 p-2 w-[120px]">
                                                    ACT FINISH
                                                </th>

                                                <th class="border border-gray-300 p-2 w-[90px]">
                                                    DURATION
                                                </th>


                                                <th class="border border-gray-300 p-2 w-[150px]">

                                                    UNIT DETAIL

                                                </th>
                                                {{-- AUTO UNIT
                                                @for ($i = 1; $i <= $project->workorder->quantity; $i++)
                                                    <th class="border border-gray-300 p-2 bg-cyan-500"
                                                        style="min-width:120px;">

                                                        UNIT {{ $i }}

                                                    </th>
                                                @endfor --}}

                                                <th class="border border-gray-300 p-2 w-[100px]">
                                                    PRIORITY
                                                </th>
                                            </tr>

                                        </thead>

                                        {{-- BODY --}}
                                        <tbody id="taskTable{{ $project->id }}">
                                            {{-- EXISTING TASK --}}
                                            @foreach ($project->tasks as $task)
                                                <tr class="bg-white text-black">

                                                    <td class="border border-gray-300 p-2"
                                                        ondblclick="
                                                            editCell(
                                                                this,
                                                                {{ $task->id }},
                                                                'task_name'
                                                            )
                                                             ">
                                                        {{ $task->task_name }}

                                                    </td>

                                                    <td class="border border-gray-300 p-2"
                                                        ondblclick="
                                                            editCell(
                                                                this,
                                                                {{ $task->id }},
                                                                'pic'
                                                            )
                                                            ">

                                                        {{ $task->pic }}

                                                    </td>

                                                    <td class="border border-gray-300 p-2 text-left"
                                                        ondblclick="
                                                            editCell(
                                                                this,
                                                                {{ $task->id }},
                                                                'activity_detail'
                                                            )
                                                            ">

                                                        {{ $task->activity_detail }}

                                                    </td>

                                                    <td class="border border-gray-300 p-2"
                                                        ondblclick="
                                                            editDateCell(
                                                                this,
                                                                {{ $task->id }},
                                                                'bl_start'
                                                            )
                                                             ">
                                                        {{ $task->bl_start }}
                                                    </td>

                                                    <td class="border border-gray-300 p-2"
                                                        ondblclick="
                                                            editDateCell(
                                                                this,
                                                                {{ $task->id }},
                                                                'bl_finish'
                                                            )
                                                        ">

                                                        {{ $task->bl_finish }}

                                                    </td>

                                                    <td class="border border-gray-300 p-2"
                                                        ondblclick="
                                                            editDateCell(
                                                                this,
                                                                {{ $task->id }},
                                                                'act_start'
                                                            )
                                                        ">

                                                        {{ $task->act_start }}

                                                    </td>

                                                    <td class="border border-gray-300 p-2"
                                                        ondblclick="
                                                            editDateCell(
                                                                this,
                                                                {{ $task->id }},
                                                                'act_finish'
                                                            )
                                                        ">

                                                        {{ $task->act_finish }}

                                                    </td>
                                                    <td class="border border-gray-300 p-2 font-bold text-blue-700">

                                                        @php

                                                            $start = \Carbon\Carbon::parse($task->bl_start);

                                                            $finish = \Carbon\Carbon::parse($task->bl_finish);

                                                            $days = $start->diffInDays($finish) + 1;

                                                        @endphp

                                                        {{ $days }} Days

                                                    </td>

                                                    <td class="border border-gray-300 p-2 text-center">

                                                        <button type="button"
                                                            onclick="
                                                            toggleUnitDetail(
                                                                {{ $task->id }}
                                                            )
                                                        "
                                                            class="
                                                            bg-cyan-500
                                                            hover:bg-cyan-600

                                                            text-black
                                                            font-bold

                                                            px-4 py-2
                                                            rounded-xl
                                                        ">

                                                            Detail Unit

                                                        </button>

                                                    </td>

                                                    <td class="border border-gray-300 p-2
                                                        font-bold text-center cursor-pointer"
                                                        ondblclick="
                                                            editSelectCell(
                                                                this,
                                                                {{ $task->id }},
                                                                'priority'
                                                            )
                                                        ">

                                                        {{ $task->priority ?? 'High' }}

                                                    </td>

                                                </tr>
                                                <tr id="unitDetail{{ $task->id }}" style="display:none;">

                                                    <td colspan="20" class="bg-slate-100 p-4">

                                                        <div
                                                            style="
            display:grid;
            grid-template-columns:
                repeat(auto-fill,minmax(220px,1fr));
            gap:12px;
        ">

                                                            @for ($i = 1; $i <= $project->workorder->quantity; $i++)
                                                                @php
                                                                    $status = $task->{'unit_' . $i} ?? 0;
                                                                @endphp

                                                                <div
                                                                    style="
                    background:white;
                    border:1px solid #d1d5db;
                    border-radius:14px;
                    padding:14px;
                ">

                                                                    <div
                                                                        style="
                        font-weight:700;
                        margin-bottom:10px;
                        color:#0f172a;
                    ">

                                                                        UNIT {{ $i }}

                                                                    </div>

                                                                    <select
                                                                        onchange="
                                                                        saveUnitStatus(
                                                                            this,
                                                                            {{ $task->id }},
                                                                            'unit_{{ $i }}',
                                                                            this.value
                                                                        )
                                                                    "
                                                                        style="
                                                                        width:100%;
                                                                        border:1px solid #d1d5db;
                                                                        border-radius:10px;
                                                                        padding:10px;
                                                                    ">

                                                                        <option value="0"
                                                                            {{ $status == 0 ? 'selected' : '' }}>

                                                                            Not Started

                                                                        </option>

                                                                        <option value="1"
                                                                            {{ $status == 1 ? 'selected' : '' }}>

                                                                            In Progress

                                                                        </option>

                                                                        <option value="2"
                                                                            {{ $status == 2 ? 'selected' : '' }}>

                                                                            Complete

                                                                        </option>

                                                                    </select>

                                                                </div>
                                                            @endfor

                                                        </div>

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

                                                    <input type="date" name="act_finish[]"
                                                        class="w-full rounded border border-gray-300 px-2 py-1 text-black text-[11px]">

                                                </td>


                                                <td class="border border-gray-300 p-1">

                                                    <input type="text" value="Automatic" readonly tabindex="-1"
                                                        class="w-full rounded border border-gray-300
                                                            px-2 py-1 text-gray-500 text-center
                                                            text-[11px]
                                                            bg-gray-100
                                                            pointer-events-none
                                                            select-none">
                                                </td>

                                                <td class="border border-gray-300 p-2 text-center">

                                                    <span
                                                        style="
                                                        color:#64748b;
                                                        font-size:11px;
                                                        font-style:italic;
                                                    ">

                                                        Auto Detail

                                                    </span>

                                                </td>

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

                </tbody>

            </table>

        </div>

    </div>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx =
            document.getElementById('unitChart');



        new Chart(ctx, {

            type: 'bar',

            data: {

                labels: [

                    @foreach ($project->units as $unit)

                        'UNIT {{ $unit->unit_no }}',
                    @endforeach

                ],

                datasets: [{

                    label: 'Progress %',

                    data: [

                        @foreach ($project->units as $unit)

                            {{ $unit->persentase }},
                        @endforeach

                    ],

                    backgroundColor: [

                        @foreach ($project->units as $unit)

                            @if ($unit->persentase == 100)

                                '#22C55E',
                            @elseif ($unit->persentase > 0)

                                '#EAB308',
                            @else

                                '#94A3B8',
                            @endif
                        @endforeach

                    ],

                    borderRadius: 12,
                    barThickness: 60,
                    maxBarThickness: 80,

                    borderSkipped: false,

                }]

            },

            options: {
                animation: false,

                devicePixelRatio: 2,

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {

                        display: false

                    }

                },

                scales: {

                    y: {

                        beginAtZero: true,

                        max: 100

                    }

                }
            }

        });
    </script>
    {{-- SCRIPT --}}
    <script>
        function addTaskRow(projectId) {
            let table =
                document.getElementById(
                    'taskTable' + projectId
                );

            <
            td class = "border border-gray-300 p-2 text-center" >

            <
            span style = "
            color: #64748b;
                                                                    font-size:11px;
                                                                    font-style:italic;
                                                                ">

                                                                    Auto Detail

                                                                </span>

                                                            </td>

                                                                        <td class= "border border-gray-300 p-1" >

                <
                select
            name = "priority[]"
            class = "w-full rounded border border-gray-300 px-2 py-1 text-black text-[11px]" >

            <
            option value = "High" >
                High <
                /option>

                <
                option value = "Medium" >
                Medium <
                /option>

                <
                option value = "Low" >
                Low <
                /option>

                <
                /select>

                <
                /td>

                <
                /tr>

            `;

                                                                        table.insertAdjacentHTML(
                                                                            'beforeend',
                                                                            row
                                                                        );
                                                                    }
    </script>

    <script>
        function editCell(td, id, field) {
            let oldValue =
                td.innerText.trim();

            td.innerHTML = `

            <input
            type="text"
            value="${oldValue}"
            class="w-full border rounded px-2 py-1 text-black"
            id="editInput">

            `;

            let input =
                td.querySelector('#editInput');

            input.focus();



            input.addEventListener(
                'blur',
                function() {

                    saveCell(
                        td,
                        id,
                        field,
                        this.value
                    );

                }
            );



            input.addEventListener(
                'keydown',
                function(e) {

                    if (e.key === 'Enter') {

                        this.blur();

                    }

                }
            );
        }

        function editSelectCell(
            td,
            id,
            field
        ) {
            let current =
                td.innerText.trim();



            td.innerHTML = `

        <select
            class="w-full border rounded px-2 py-1 text-black"
            id="editSelect">

            <option value="High"
                ${current == 'High' ? 'selected' : ''}>

                High

            </option>

            <option value="Medium"
                ${current == 'Medium' ? 'selected' : ''}>

                Medium

            </option>

            <option value="Low"
                ${current == 'Low' ? 'selected' : ''}>

                Low

            </option>

        </select>

    `;



            let select =
                td.querySelector('#editSelect');

            select.focus();



            select.addEventListener(
                'change',
                function() {

                    saveCell(
                        td,
                        id,
                        field,
                        this.value
                    );

                }
            );
        }

        function editDateCell(td, id, field) {
            let oldValue =
                td.innerText.trim();

            td.innerHTML = `

        <input
            type="date"
            value="${oldValue}"
            class="w-full border rounded px-2 py-1 text-black"
            id="editDateInput">

    `;

            let input =
                td.querySelector('#editDateInput');

            input.focus();


            input.addEventListener(
                'change',
                function() {

                    saveCell(
                        td,
                        id,
                        field,
                        this.value
                    );

                }
            );
        }


        function saveCell(td, id, field, value) {
            fetch(
                    "{{ route('projectmanager.task.updatecell') }}", {

                        method: "POST",

                        headers: {

                            "Content-Type": "application/json",

                            "X-CSRF-TOKEN": "{{ csrf_token() }}"

                        },

                        body: JSON.stringify({

                            id: id,

                            field: field,

                            value: value

                        })

                    }
                )

                .then(res => res.json())

                .then(data => {

                    td.innerHTML = value;

                })

                .catch(() => {

                    td.innerHTML = value;

                });
        }

        function editUnitStatus(
            td,
            id,
            field,
            current
        ) {
            td.innerHTML = `

        <select
            class="w-full border rounded px-2 py-1 text-black text-sm"
            id="unitStatusSelect">

            <option value="0"
                ${current == 0 ? 'selected' : ''}>

                Not Started

            </option>

            <option value="1"
                ${current == 1 ? 'selected' : ''}>

                In Progress

            </option>

            <option value="2"
                ${current == 2 ? 'selected' : ''}>

                Complete

            </option>

        </select>

    `;

            let select =
                td.querySelector('#unitStatusSelect');

            select.focus();



            select.addEventListener(
                'change',
                function() {

                    saveUnitStatus(
                        td,
                        id,
                        field,
                        this.value
                    );

                }
            );
        }

        function saveUnitStatus(
            td,
            id,
            field,
            value
        ) {
            fetch(
                    "{{ route('projectmanager.task.updatecell') }}", {

                        method: "POST",

                        headers: {

                            "Content-Type": "application/json",

                            "X-CSRF-TOKEN": "{{ csrf_token() }}"

                        },

                        body: JSON.stringify({

                            id: id,

                            field: field,

                            value: value

                        })

                    }
                )

                .then(res => res.json())

                .then(data => {

                    console.log('saved');

                });
        }

        function toggleUnitDetail(id) {

            let row =
                document.getElementById(
                    'unitDetail' + id
                );

            if (
                row.style.display === 'none'
            ) {

                row.style.display =
                    'table-row';

            } else {

                row.style.display =
                    'none';
            }
        }

        window.addEventListener(
            'DOMContentLoaded',
            function() {

                let openId =
                    localStorage.getItem(
                        'openTaskDetail'
                    );

                if (openId) {

                    let row =
                        document.getElementById(
                            'unitDetail' + openId
                        );

                    if (row) {

                        row.style.display =
                            'table-row';
                    }

                    localStorage.removeItem(
                        'openTaskDetail'
                    );
                }
            }
        );
    </script>

@endsection
