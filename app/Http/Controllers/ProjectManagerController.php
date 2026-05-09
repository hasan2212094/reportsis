<?php

namespace App\Http\Controllers;

use App\Models\ProjectManager;
use App\Models\Workorder;
use Illuminate\Http\Request;

class ProjectManagerController extends Controller
{
  public function index()
{
    $projectmanagers = ProjectManager::with('workorder')
        ->paginate(10);

    return view('page.Projectmanager.index',
        compact('projectmanagers'));
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
        'persentase_A' => 'required|integer|min:0|max:100',
    ]);

    // STATUS PROJECT
    if ($request->persentase_A == 0) {

        $status = 'Not Started';

    } elseif ($request->persentase_A == 100) {

        $status = 'Completed';

    } else {

        $status = 'In Progress';
    }

    ProjectManager::create([
        'workorder_id'     => $request->workorder_id,
        'date_awal'        => $request->date_awal,
        'target_date'      => $request->target_date,
        'persentase_A'     => $request->persentase_A,
        'status_pekerjaan' => $status,
    ]);

    return redirect()->route('page.Projectmanager.index')
        ->with('success', '✅ Data berhasil dibuat');

} catch (\Exception $e) {

    return back()
        ->withInput()
        ->with('error', $e->getMessage());
}
}


}
