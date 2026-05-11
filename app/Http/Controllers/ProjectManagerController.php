<?php

namespace App\Http\Controllers;

use App\Models\ProjectManager;
use App\Models\Projectmanagerunit;
use App\Models\Workorder;
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
        'page.Projectmanager.index',
        compact('projectmanagers')
    );
}


public function monitoring($id)
{
    $project = ProjectManager::with('workorder')
        ->findOrFail($id);

    return view('page.Projectmanager.monitoring',
        compact('project'));
}

public function create()
 {
    $workorders = Workorder::all();
    return view('page.Projectmanager.create', compact('workorders')); 
 }
public function store(Request $request)
{
    try {

        $request->validate([
            'workorder_id' => 'required|exists:workorders,id',
            'date_awal' => 'required|date',
            'target_date' => 'required|date',
        ]);

        // CREATE PROJECT
        $project = ProjectManager::create([
            'workorder_id'     => $request->workorder_id,
            'date_awal'        => $request->date_awal,
            'target_date'      => $request->target_date,
            'persentase_A'     => 0,
            'status_pekerjaan' => 'Not Started',

        ]);
        $workorder = Workorder::findOrFail($request->workorder_id);
        // QTY UNIT
        $qty = $workorder->qty;
        // AUTO CREATE UNIT
        for ($i = 1; $i <= $qty; $i++) {
            ProjectManagerUnit::create([
                'project_manager_id' => $project->id,
                'unit_no'            => $i,
                'persentase'         => 0
            ]);
        }
        return redirect()->route('page.Projectmanager.index')
            ->with('success', '✅ Data berhasil dibuat');

    } catch (\Exception $e) {

        return back()
            ->withInput()
            ->with('error', $e->getMessage());
    }
}


}
