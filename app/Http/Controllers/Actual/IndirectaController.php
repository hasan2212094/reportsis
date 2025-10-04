<?php

namespace App\Http\Controllers\Actual;

use Carbon\Carbon;
use App\Models\Indirecta;
use App\Models\Indirectp;
use Illuminate\Http\Request;
use App\Exports\IndirectaExport;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;



class IndirectaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Indirecta::query();

      if ($request->start_date && $request->end_date) {
        $query->whereBetween('Date_actual', [
            $request->start_date,
            $request->end_date
        ]);
       }

    $data = $query->get();
    $trashed = Indirecta::onlyTrashed()->get();

    return view('page.actual.indirectcost.index', compact('data', 'trashed'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $inderectp = Indirectp::all(); // ambil semua data dari tabel direct_p_s
        return view('page.actual.indirectcost.create', compact('inderectp'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //  try {
        //      $request->merge([
        //    'Total' => preg_replace('/[^0-9]/', '', $request->Total)
        //    ]);
        //     $request->validate([
        //         'indirectps_id'     => 'required|exists:indirectps,id',
        //         'Qty'               => 'required|integer|min:1',
        //         'Unit'              => 'required|string|max:50',
        //         'Date_actual'       => 'required|date_format:Y-m-d',
        //         'Toko'              => 'required|string|max:255',
        //         'Transaksi'         => 'required|integer|min:0',
        //         'Total'             => 'required|numeric|min:0',
        //     ]);

        //     $indirecta = new Indirecta([
        //         'indirectps_id'   => $request->indirectps_id,
        //         'Qty'             => $request->Qty,
        //         'Unit'            => $request->Unit,
        //         'Date_actual'     => Carbon::parse($request->Date_actual)->format('Y-m-d'),
        //         'Toko'            => $request->Toko,
        //         'Transaksi'       => $request->Transaksi,
        //         'Total'           => $request->Total,
        //     ]);

        //     $indirecta->save(); 

        //     return redirect()->route('page.indirecta.index')->with('success', 'Pengajuan berhasil dibuat.');
        // } catch (\Exception $e) {
        //     return redirect()->route('page.indirecta.index')->with('error', 'Terjadi kesalahan saat membuat pengajuan.');
        // }
     Log::info('Membuat pengajuan dengan data: ' . json_encode($request->all()));

    // Bersihkan format Rupiah sebelum validasi
    $request->merge([
        'Total' => preg_replace('/[^0-9]/', '', $request->Total)
    ]);

    // Validasi otomatis: kalau gagal, langsung redirect balik
    $validated = $request->validate([
          'indirectps_id'     => 'required|exists:indirectps,id',
            'Qty'               => 'required|integer|min:1',
            'Unit'              => 'required|string|max:50',
            'Date_actual'       => 'required|date_format:Y-m-d',
            'Toko'              => 'required|string|max:255',
            'Transaksi'         => 'required|integer|min:0',
            'Total'             => 'required|numeric|min:0',
    ],[
        'indirectps_id.required'   => 'ITEM wajib diisi.',
        'Qty.required'             => 'Jumlah item wajib diisi.',
        'Unit.required'            => 'Satuan wajib diisi.',
        'Date_actual.required'     => 'Tanggal Actual wajib diisi.',
        'Toko.required'            => 'Toko wajib diisi.',
        'transaksi.required'       => 'Transaksi wajib diisi.',
        'Total.required'           => 'Total wajib diisi.',
    ]);

    // Kalau validasi berhasil → lanjut simpan
   $directa = new Indirecta([
                'indirectps_id'   => $request->indirectps_id,
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
        ->route('page.indirecta.index')
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
       $indirectp = Indirectp::all();
       $data    = Indirecta::findOrFail($id);
       return view('page.actual.indirectcost.edit', compact('indirectp', 'data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    { 

    try{
        $request->validate([
        'indirectps_id' => 'required|exists:indirectps,id',
        'Qty'           => 'required|integer|min:1',
        'Unit'          => 'required|string|max:50',
        'Date_actual'   => 'required|date',
        'Toko'          => 'required|string|max:255',
        'Transaksi'     => 'required|integer',
        'Total'         => 'required|string|min:0 ',
    ]);

    $indirecta = Indirecta::findOrFail($id);

    $indirecta->update($request->only([
        'indirectps_id',
        'Qty',
        'Unit',
        'Date_actual',
        'Toko',
        'Transaksi',
        'Total'
    ]));

    return redirect()
        ->route('page.indirecta.index')
        ->with('success', 'Pengajuan berhasil diperbarui.');
    } catch (\Exception $e) {
        return redirect()->route('page.indirecta.index')->with('error', 'Terjadi kesalahan saat memperbarui pengajuan.');
        
    }
}

    /**
     * Remove the specified resource from storage.
     */
      public function destroy($id)
{
    $item = Indirecta::findOrFail($id);
    $item->delete(); // soft delete
    return redirect()->back()->with('success', 'Data berhasil dihapus (soft delete)');
}

public function restore($id)
{
    $item = Indirecta::withTrashed()->findOrFail($id);
    $item->restore();
    return redirect()->back()->with('success', 'Data berhasil direstore');
}

public function forceDelete($id)
{
    $item = Indirecta::withTrashed()->findOrFail($id);
    $item->forceDelete();
    return redirect()->back()->with('success', 'Data dihapus permanen');
}


    public function exports()
    {
        return Excel::download(new IndirectaExport, 'indirecta.xlsx');
    }
  
}
