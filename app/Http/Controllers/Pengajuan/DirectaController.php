<?php

namespace App\Http\Controllers\Pengajuan;

use App\Models\Directa;
use App\Models\DirectP;
use Illuminate\Http\Request;
use App\Exports\DirectaExport;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class DirectaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         $query = Directa::query();

      if ($request->start_date && $request->end_date) {
        $query->whereBetween('Date_actual', [
            $request->start_date,
            $request->end_date
        ]);
       }

    $data = $query->get();
    $trashed = Directa::onlyTrashed()->get();

    return view('page.actual.Directcost.index', compact('data', 'trashed'));
    }

    
   
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       $direct_p_s = DirectP::orderBy('item_id', 'ASC')->get(); // ambil semua data dari tabel direct_p_s
       return view('page.actual.directcost.create', compact('direct_p_s'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // try {
            
        //      $request->merge([
        //    'Total' => preg_replace('/[^0-9]/', '', $request->Total)
        //    ]);

        //     $request->validate([
        //         'direct_p_s_id'     => 'required|exists:direct_p_s,id',
        //         'Qty'               => 'required|integer|min:1',
        //         'Unit'              => 'required|string|max:50',
        //         'Date_actual'       => 'required|date_format:Y-m-d',
        //         'Toko'              => 'required|string|max:255',
        //         'Transaksi'         => 'required|integer|min:0',
        //         'Total'             => 'required|numeric|min:0',
        //     ], [
        //         'direct_p_s_id.required' => 'Kolom diisi.',
        //         'Qty.required'           => 'Kolom diisi.',
        //         'Unit.required'          => 'Kolom diisi.',
        //         'Date_actual.required'   => 'Kolom diisi.',
        //         'Toko.required'          => 'Kolom diisi.',
        //         'Transaksi.required'     => 'Kolom diisi.',
        //         'Total.required'         => 'Kolom diisi.',
        //     ]);

        //     $directa = new Directa([
        //         'direct_p_s_id'   => $request->direct_p_s_id,
        //         'Qty'             => $request->Qty,
        //         'Unit'            => $request->Unit,
        //         'Date_actual'     => Carbon::parse($request->Date_actual)->format('Y-m-d'),
        //         'Toko'            => $request->Toko,
        //         'Transaksi'       => $request->Transaksi,
        //         'Total'           => $request->Total,
        //     ]);

        //     $directa->save(); 

        //     return redirect()->route('page.directa.index')->with('success', 'Pengajuan berhasil dibuat.');
        // } catch (\Exception $e) {
        //     return redirect()->route('page.directa.index')->with('error', 'Terjadi kesalahan saat membuat pengajuan.');
        // }
     Log::info('Membuat pengajuan dengan data: ' . json_encode($request->all()));

    // Bersihkan format Rupiah sebelum validasi
    $request->merge([
        'Total' => preg_replace('/[^0-9]/', '', $request->Total)
    ]);

    // Validasi otomatis: kalau gagal, langsung redirect balik
    $validated = $request->validate([
          'direct_p_s_id'     => 'required|exists:direct_p_s,id',
            'Qty'               => 'required|integer|min:1',
            'Unit'              => 'required|string|max:50',
            'Date_actual'       => 'required|date_format:Y-m-d',
            'Toko'              => 'required|string|max:255',
            'Transaksi'         => 'required|integer|min:0',
            'Total'             => 'required|numeric|min:0',
    ],[
        'direct_p_s_id.required'   => 'ITEM wajib diisi.',
        'Qty.required'             => 'Jumlah item wajib diisi.',
        'Unit.required'            => 'Satuan wajib diisi.',
        'Date_actual.required'     => 'Tanggal Actual wajib diisi.',
        'Toko.required'            => 'Toko wajib diisi.',
        'transaksi.required'       => 'Transaksi wajib diisi.',
        'Total.required'           => 'Total wajib diisi.',
    ]);

    // Kalau validasi berhasil → lanjut simpan
   $directa = new Directa([
                'direct_p_s_id'   => $request->direct_p_s_id,
                'Qty'             => $request->Qty,
                'Unit'            => $request->Unit,
                'Date_actual'     => Carbon::parse($request->Date_actual)->format('Y-m-d'),
                'Toko'            => $request->Toko,
                'Transaksi'       => $request->Transaksi,
                'Total'           => $request->Total,
            ]);

            $directa->save(); 
            
    Log::info('Pengajuan berhasil dibuat.');

    return redirect()
        ->route('page.directa.index')
        ->with('success', 'Pengajuan berhasil dibuat.');
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
       $direct_p_s = DirectP::all();
       $data    = Directa::findOrFail($id);
       return view('page.actual.directcost.edit', compact('direct_p_s', 'data'));
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $request->validate([
        'direct_p_s_id' => 'required|exists:direct_p_s,id',
        'Qty'        => 'required|integer|min:1',
        'Unit'       => 'required|string|max:50',
        'Date_actual'=> 'required|date',
        'Toko'       => 'required|string|max:255',
        'Transaksi'  => 'required|integer',
        'Total'      => 'required|string|min:0 ',
    ]);

    $directa = Directa::findOrFail($id);

    $directa->update($request->only([
        'direct_p_s_id',
        'Qty',
        'Unit',
        'Date_actual',
        'Toko',
        'Transaksi',
        'Total'
    ]));

    return redirect()
        ->route('page.directa.index')
        ->with('success', 'Pengajuan berhasil diperbarui.');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    try {
        $item = Directa::findOrFail($id);
        $item->delete();

        Log::info('Berhasil soft delete', ['id' => $id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus (soft delete)',
        ]);
    } catch (\Exception $e) {
        Log::error('Gagal soft delete', ['id' => $id, 'error' => $e->getMessage()]);

        return response()->json([
            'status' => 'error',
            'message' => 'Gagal menghapus data',
        ], 500);
    }
}
public function restore($id)
{
    try {
        $item = Directa::withTrashed()->findOrFail($id);
        $item->restore();

        Log::info('Restore berhasil', ['id' => $id]);

        if (request()->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil direstore',
            ]);
        }

        return redirect()->back()->with('success', 'Data berhasil direstore');
    } catch (\Exception $e) {
        Log::error('Gagal restore', ['id' => $id, 'error' => $e->getMessage()]);

        if (request()->ajax()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal restore data',
            ], 500);
        }

        return redirect()->back()->withErrors('Gagal restore data');
    }
}

public function forceDelete($id)
{
    try {
        $item = Directa::withTrashed()->findOrFail($id);
        $item->forceDelete();

        Log::info('Force delete berhasil', ['id' => $id]);

        if (request()->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data dihapus permanen',
            ]);
        }

        return redirect()->back()->with('success', 'Data dihapus permanen');
    } catch (\Exception $e) {
        Log::error('Gagal force delete', ['id' => $id, 'error' => $e->getMessage()]);

        if (request()->ajax()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus permanen',
            ], 500);
        }

        return redirect()->back()->withErrors('Gagal menghapus permanen');
    }
}

    public function export()
  {
    return Excel::download(new DirectaExport, 'directa.xlsx');
  }
}
