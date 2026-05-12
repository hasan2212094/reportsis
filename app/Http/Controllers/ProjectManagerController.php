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

            'task_name' => 'required',

        ]);


        // DURATION AUTO
        $duration = 0;

        if ($request->bl_start && $request->bl_finish) {

            $duration =
                Carbon::parse($request->bl_start)
                ->diffInDays($request->bl_finish);
        }


        ProjectManagerTask::create([

            'project_manager_id' => $request->project_manager_id,

            'task_name' => $request->task_name,

            'pic' => $request->pic,

            'activity_detail' => $request->activity_detail,

            'bl_start' => $request->bl_start,

            'bl_finish' => $request->bl_finish,

            'act_start' => $request->act_start,

            'act_finish' => $request->act_finish,

            'duration' => $duration,

            'priority' => $request->priority,

            'percentage' => 0,

        ]);


        return back()
            ->with('success', 'Task berhasil ditambahkan');

    } catch (\Exception $e) {

        return back()
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

    return view(
        'page.Projectmanager.detail',
        compact('project')
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



}
