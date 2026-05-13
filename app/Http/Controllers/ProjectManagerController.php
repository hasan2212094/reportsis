<?php

namespace App\Http\Controllers;

use App\Models\ProjectManager;
use App\Models\ProjectManagerTask;
use App\Models\Projectmanagerunit;
use App\Models\Workorder;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProjectManagerController extends Controller
{
  public function index()
{
    $projectmanagers = ProjectManager::with([
        'workorder',
        'units'
    ])->paginate(10);

    return view(
        'page.Projectmanager.list',
        compact('projectmanagers')
    );
}

public function createpm()
{
    $workorders = Workorder::all()->map(function ($wo) {

        // FIX WO DATE
        try {

            $wo->wo_date_fix =
                \Carbon\Carbon::parse(
                    str_replace('/', '-', $wo->wo_date)
                )->format('Y-m-d');

        } catch (\Exception $e) {

            $wo->wo_date_fix = null;
        }


        // FIX PEKERJAAN SELESAI
        try {

            $wo->finish_fix =
                \Carbon\Carbon::parse(
                    str_replace('/', '-', $wo->pekerjaan_selesai)
                )->format('Y-m-d');

        } catch (\Exception $e) {

            $wo->finish_fix = null;
        }

        return $wo;
    });

    return view(
        'page.Projectmanager.create',
        compact('workorders')
    );
}

public function create()
 {
    $workorders = Workorder::all();
    return view('page.Projectmanager.create', compact('workorders')); 
 }

 public function edit($id)
{
    $project = ProjectManager::with([
        'workorder',
        'units'
    ])->findOrFail($id);

    return view(
        'page.Projectmanager.edit',
        compact('project')
    );
}

public function update(Request $request, $id)
{
    try {

        $project = ProjectManager::findOrFail($id);


        // UPDATE PERSEN
        $persentase = $request->persentase_A;


        // AUTO STATUS
        if ($persentase == 0) {

            $status = 1;

        } elseif ($persentase == 100) {

            $status = 3;

        } else {

            $status = 2;
        }


        $project->update([

            'persentase_A' => $persentase,

            'status_pekerjaan' => $status,

        ]);


        return redirect()
            ->route('page.Projectmanager.index')
            ->with('success', '✅ Progress berhasil diupdate');

    } catch (\Exception $e) {

        return back()
            ->with('error', $e->getMessage());
    }
}
public function store(Request $request)
{
    try {

        $request->validate([

            'workorder_id' => 'required|exists:workorders,id',

        ]);


        // AMBIL DATA WO
        $workorder = Workorder::findOrFail(
            $request->workorder_id
        );


        // CREATE PROJECT
        $project = ProjectManager::create([

            'workorder_id' => $workorder->id,

            'date_awal' => $workorder->wo_date,

            'target_date' => $workorder->pekerjaan_selesai,

            'persentase_A' => 0,

            // 1 = NOT STARTED
            'status_pekerjaan' => 1,

        ]);


        // AUTO CREATE UNIT
        $qty = $workorder->quantity ?? 0;

        for ($i = 1; $i <= $qty; $i++) {

            Projectmanagerunit::create([

                'project_manager_id' => $project->id,

                'unit_no' => $i,

                'persentase' => 0

            ]);
        }


        return redirect()
            ->route('page.Projectmanager.index')
            ->with('success', '✅ Data berhasil dibuat');

    } catch (\Exception $e) {

        return back()
            ->withInput()
            ->with('error', $e->getMessage());
    }
}

public function storeTask(Request $request)
{
    try {

        $request->validate([

            'project_manager_id' => 'required',

            'task_name.*' => 'required',

        ]);


        foreach ($request->task_name as $index => $taskName) {

            // AUTO DURATION
            $duration = 0;

            if (
                !empty($request->bl_start[$index]) &&
                !empty($request->bl_finish[$index])
            ) {

                $duration =
                    Carbon::parse($request->bl_start[$index])
                        ->diffInDays(
                            Carbon::parse($request->bl_finish[$index])
                        );
            }


            // BASE DATA
            $data = [

                'project_manager_id' =>
                    $request->project_manager_id,

                'task_name' =>
                    $request->task_name[$index] ?? null,

                'pic' =>
                    $request->pic[$index] ?? null,

                'activity_detail' =>
                    $request->activity_detail[$index] ?? null,

                'bl_start' =>
                    $request->bl_start[$index] ?? null,

                'bl_finish' =>
                    $request->bl_finish[$index] ?? null,

                'act_start' =>
                    $request->act_start[$index] ?? null,

                'act_finish' =>
                    $request->act_finish[$index] ?? null,

                'duration' =>
                    $duration,

                'priority' =>
                    $request->priority[$index] ?? null,

                'percentage' =>
                    $request->percentage[$index] ?? 0,

            ];


            // AUTO SAVE UNIT STATUS
            for ($i = 1; $i <= 20; $i++) {

                $data['unit_'.$i] =
                    $request->unit_status_new[$index][$i] ?? 0;

            }


            // CREATE TASK
            ProjectManagerTask::create($data);

        }


        return back()
            ->with('success', '✅ Task berhasil ditambahkan');

    } catch (\Exception $e) {

        return back()
            ->withInput()
            ->with('error', $e->getMessage());
    }
}

public function detail($id)
{
    $project = ProjectManager::with([
        'workorder',
        'units',
        'tasks'
    ])->findOrFail($id);



    // AUTO CALCULATE PERCENTAGE
    foreach ($project->units as $unit) {

        $totalValue = 0;

        $totalTask =
            $project->tasks->count();


        foreach ($project->tasks as $task) {

            $status =
                $task->{'unit_'.$unit->unit_no} ?? 0;


            // STATUS VALUE
            if ($status == 2) {

                // COMPLETE
                $totalValue += 100;

            } elseif ($status == 1) {

                // IN PROGRESS
                $totalValue += 50;

            }
        }


        // FINAL PERCENTAGE
        $unit->persentase =
            $totalTask > 0
            ? round($totalValue / $totalTask)
            : 0;
    }


$totalCells = 0;

$notStarted = 0;

$inProgress = 0;

$complete = 0;



foreach ($project->tasks as $task) {

    for (
        $i = 1;
        $i <= $project->workorder->quantity;
        $i++
    ) {

        $status =
            $task->{'unit_'.$i} ?? 0;


        $totalCells++;


        if ($status == 0) {

            $notStarted++;

        } elseif ($status == 1) {

            $inProgress++;

        } else {

            $complete++;
        }
    }
}

if ($complete == $totalCells && $totalCells > 0) {

    $projectStatus = 'COMPLETE';

} elseif ($inProgress > 0 || $complete > 0) {

    $projectStatus = 'IN PROGRESS';

} else {

    $projectStatus = 'NOT STARTED';
}

$notStartedPercent =
    $totalCells > 0
    ? round(($notStarted / $totalCells) * 100)
    : 0;


$inProgressPercent =
    $totalCells > 0
    ? round(($inProgress / $totalCells) * 100)
    : 0;


$completePercent =
    $totalCells > 0
    ? round(($complete / $totalCells) * 100)
    : 0;

$overallProgress = 0;

if ($totalCells > 0) {

    $overallProgress = round(

        (

            ($inProgress * 50)

            +

            ($complete * 100)

        )

        / $totalCells

    );

}

$project->update([

    'persentase_A' => $overallProgress

]);

    return view(
        'page.Projectmanager.detail',
        compact('project','projectStatus','notStartedPercent','inProgressPercent','completePercent'
)
    );

}

public function dashboard()
{
    $projectmanagers = ProjectManager::with([
        'workorder',
        'units',
        'tasks'
    ])->get();

    return view(
        'page.Projectmanager.dashboard',
        compact('projectmanagers')
    );
}

public function updateCell(Request $request)
{
    try {

        $task = ProjectManagerTask::findOrFail(
            $request->id
        );


        $task->update([

            $request->field =>
                $request->value

        ]);


        return response()->json([

            'success' => true

        ]);

    } catch (\Exception $e) {

        return response()->json([

            'success' => false,

            'message' => $e->getMessage()

        ]);
    }
}


}
