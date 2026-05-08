<?php

namespace App\Http\Controllers;

use App\Models\ProjectManager;
use App\Models\Workorder;
use Illuminate\Http\Request;

class ProjectManagerController extends Controller
{
  public function index()
{
    $projectmanagers = ProjectManager::with(['workorder'])->paginate(10);

    return view('page.Projectmanager.index', compact('projectmanagers'));
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
            'workarea' => 'required',
            'project' => 'required',
            'pt_id' => 'required|exists:name_p_t_s,id',
            'user_pm' => 'required',
            'qty' => 'required|integer',
            'bl_start' => 'required|date',
            'bl_end' => 'required|date',
            'task_name' => 'required',
            'pic' => 'required',
        ]);

        ProjectManager::create([
            'workorder_id' => $request->workorder_id,
            'workarea' => $request->workarea,
            'project' => $request->project,
            'pt_id' => $request->pt_id,
            'user_pm' => $request->user_pm,
            'qty' => $request->qty,
            'target_date' => $request->target_date,
            'bl_start' => $request->bl_start,
            'bl_end' => $request->bl_end,
            'task_name' => $request->task_name,
            'pic' => $request->pic,

            // default
            'status_pekerjaan' => 0,
            'unit' => 0,
            'status_proses' => 0,
            'persentase' => 0,

            'keterangan' => $request->keterangan ?? null,
        ]);

        return redirect()->route('presentasi.index')
            ->with('success', '✅ Data berhasil dibuat');

    } catch (\Exception $e) {
        return back()->withInput()->with('error', $e->getMessage());
    }
}


}
