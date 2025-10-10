<?php

namespace App\Http\Controllers;

use App\Models\Ppna;
use App\Models\Directa;
use App\Models\DirectP;
use App\Models\Luarrab;
use App\Models\Workorder;
use Illuminate\Http\Request;
use App\Exports\ActualExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     
    public function index()
    {
        $workorders = Workorder::select('id', 'kode_wo')->get();
        return view('page.Report.actual.index', compact('workorders'));
    }

   public function getData($wo)
{
    Log::info("=== MULAI getData untuk WO: {$wo} ===");

    // DIRECTA
    $directa = Directa::join('direct_p_s', 'direct_p_s.id', '=', 'directas.direct_p_s_id')
        ->where('direct_p_s.workorder_id', $wo)
        ->select([
            'directas.Qty as qty',
            'directas.Unit as unit',
            'directas.Date_actual as tanggal_actual',
            'directas.Toko as toko',
            'directas.Total as total',
            'direct_p_s.Item as item',
        ])
        ->get()
        ->map(function ($row) {
            $row->source = 'Direct cost actual';
            return $row;
        });

    // PPNA
    $ppna = DB::table('ppnas as pa')
    ->join('ppns as pn', 'pn.id', '=', 'pa.ppns_id')
    ->where('pn.workorder_id', $wo)
    ->select([
        'pa.Qty as qty',
        'pa.Unit as unit',
        'pa.Date_actual as tanggal_actual',
        'pa.Toko as toko',
        'pa.Total as total',
        'pn.Item as item',
    ])
    ->get()
    ->map(function ($row) {
        $row->source = 'Ppn actual';
        return $row;
    });

    $luarrab = Luarrab::leftJoin('workorders', 'workorders.id', '=', 'luarrabs.workorder_id')
    ->where('luarrabs.workorder_id', $wo)
    ->select([
        'luarrabs.Qty as qty',
        'luarrabs.Unit as unit',
        'luarrabs.Date_actual as tanggal_actual',
        'luarrabs.Toko as toko',
        'luarrabs.Total as total',
        'luarrabs.Item as item',
    ])
    ->get()
    ->map(function ($row) {
        $row->source = 'Luar RAB actual';
        return $row;
    });
    // Gabungkan semua hasil
    $data = collect()
        ->merge($directa)
        ->merge($ppna)
        ->merge($luarrab)
        ->values();

    return response()->json($data);
}
public function exportActual($workorder_id)
{
    return Excel::download(new ActualExport($workorder_id), 'report_actual_wo_' . $workorder_id . '.xlsx');
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
