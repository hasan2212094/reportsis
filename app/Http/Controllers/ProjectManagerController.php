<?php

namespace App\Http\Controllers;

use App\Models\ProjectManager;
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
        ]);

        $workorder = Workorder::findOrFail($request->workorder_id);

        // CONVERT DATE INDONESIA
       $bulan = [
    'Januari' => '01',
    'Februari' => '02',
    'Maret' => '03',
    'April' => '04',
    'Mei' => '05',
    'Juni' => '06',
    'Juli' => '07',
    'Agustus' => '08',
    'September' => '09',
    'Oktober' => '10',
    'November' => '11',
    'Desember' => '12',
];

$pecah = explode(' ', trim($workorder->pekerjaan_selesai));

if (count($pecah) < 3) {
    throw new \Exception('Format tanggal pekerjaan_selesai salah');
}

$hari = $pecah[0];
$bulanText = $pecah[1];
$tahun = $pecah[2];

$bulanAngka = $bulan[$bulanText] ?? '00';

$targetDate = $tahun . '-' . $bulanAngka . '-' . $hari;

        $project = ProjectManager::create([

            'workorder_id' => $workorder->id,
            'date_awal' => $workorder->wo_date,
            'target_date' => $targetDate,
            'persentase_A' => 0,
            'status_pekerjaan' => 'Not Started',

        ]);

        $qty = $workorder->quantity;

        for ($i = 1; $i <= $qty; $i++) {

            Projectmanagerunit::create([
                'project_manager_id' => $project->id,
                'unit_no' => $i,
                'persentase' => 0
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
