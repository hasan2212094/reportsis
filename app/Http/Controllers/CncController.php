<?php

namespace App\Http\Controllers;

use App\Models\Cnc;
use Illuminate\Http\Request;

class CncController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'plat_ke' => 'required|integer',
            'arus' => 'required|numeric',
            'energi_cycle' => 'required|numeric',
        ]);

        // Simpan data ke DB
        Cnc::create($data);

        return response()->json([
            'ok'
        ]);
    }

    public function index()
{
    return response()
        ->json(
            Cnc::orderBy('id', 'desc')
               ->limit(100)
               ->get([
                   'plat_ke',
                   'arus',
                   'energi_cycle',
                   'created_at'
               ])
        )
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
        ->header('Pragma', 'no-cache');
}


    // ===== HALAMAN WEB: 20 DATA TERBARU =====
    public function page()
    {
        $data = Cnc::latest()->limit(20)->get();
        return view('page.cnc', compact('data'));
    }
}
