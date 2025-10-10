<?php

namespace App\Http\Controllers\Pengajuan;

use Carbon\Carbon;
use App\Models\Indirectp;
use Illuminate\Http\Request;

use App\Exports\IndirectpExport;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class IndirectpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
          $query = Indirectp::query();

      if ($request->start_date && $request->end_date) {
        $query->whereBetween('Date_pengajuan', [
            $request->start_date,
            $request->end_date
        ]);
       }

    $data = $query->get();
    $trashed = Indirectp::onlyTrashed()->get();

    return view('page.pengajuan.indirectcost.index', compact('data', 'trashed'));
    
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $indirectp = Indirectp::all(); // ambil semua data dari tabel direct_p_s
       return view('page.pengajuan.indirectcost.create', compact('indirectp'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
//     try {
//     Log::info('Membuat pengajuan dengan data: ' . json_encode($request->all()));

//      $request->merge([
//         'Total' => preg_replace('/[^0-9]/', '', $request->Total)
//     ]);

//     $request->validate([
//         'item_id'        => 'required|string|max:100|unique:indirectps,item_id',
//         'Item'           => 'required|string|max:255',
//         'Qty'            => 'required|integer|min:1',
//         'Unit'           => 'required|string|max:50',
//         'Needed_by'      => 'required|string|max:255',
//         'Date_pengajuan' => 'required|date_format:Y-m-d',
//         'Total'          => 'required|numeric|min:0',
//     ],[
//         'item_id.required'        => 'ITEM ID wajib diisi.',
//         'item_id.unique'          => 'ITEM ID sudah terdaftar.',
//         'item.required'           => 'Nama item wajib diisi.',
//         'qty.required'            => 'Jumlah (Qty) wajib diisi.',
//         'unit.required'           => 'Satuan wajib diisi.',
//         'Needed_by.required'      => 'kebutuhan wajib diisi.',
//         'Date_pengajuan.required' => 'Tanggal pengajuan wajib diisi.',
//         'total.required'          => 'Total wajib diisi.',
//     ]);

//     $indirectp = new Indirectp([
//         'item_id'        => $request->item_id,
//         'Item'           => $request->Item,
//         'Qty'            => $request->Qty,
//         'Unit'           => $request->Unit,
//         'Needed_by'      => $request->Needed_by,
//         'Date_pengajuan' => Carbon::parse($request->Date_pengajuan)->format('Y-m-d'),
//         'Total'          => $request->Total,
//         'Notes'          => $request->Notes,
//     ]);

//     $indirectp->save();

//     Log::info('Pengajuan berhasil dibuat.');

//     return redirect()
//         ->route('page.indirectp.index')
//         ->with('success', 'Pengajuan berhasil dibuat.');

// } catch (\Exception $e) {
//     Log::error('Error membuat pengajuan: ' . $e->getMessage());

//     return redirect()
//         ->route('page.indirectp.index')
//         ->with('error', 'Terjadi kesalahan saat membuat pengajuan.');
// }
Log::info('Membuat pengajuan dengan data: ' . json_encode($request->all()));

    // Bersihkan format Rupiah sebelum validasi
    $request->merge([
        'Total' => preg_replace('/[^0-9]/', '', $request->Total)
    ]);

    // Validasi otomatis: kalau gagal, langsung redirect balik
    $validated = $request->validate([
        'item_id'        => 'required|string|max:100|unique:indirectps,item_id',
        'Item'           => 'required|string|max:255',
        'Qty'            => 'required|integer|min:1',
        'Unit'           => 'required|string|max:50',
        'Needed_by'      => 'required|string|max:255',
        'Date_pengajuan' => 'required|date_format:Y-m-d',
        'Total'          => 'required|numeric|min:0',
    ],[
        'item_id.required'     => 'ITEM ID wajib diisi.',
        'item_id.unique'       => 'ITEM ID sudah terdaftar.',
        'Item.required'        => 'Nama item wajib diisi.',
        'Qty.required'         => 'Jumlah Qty wajib diisi.',
        'Unit.required'        => 'Satuan wajib diisi.',
        'Needed_by.required'   => 'Kebutuhan wajib diisi.',
        'Date_pengajuan.required' => 'Tanggal pengajuan wajib diisi.',
        'Total.required'       => 'Total wajib diisi.',
    ]);

    // Kalau validasi berhasil → lanjut simpan
   $pengajuan = new Indirectp([
          'item_id'        => $request->item_id,
          'Item'           => $request->Item,
          'Qty'            => $request->Qty,
          'Unit'           => $request->Unit,
          'Needed_by'      => $request->Needed_by,
          'Date_pengajuan' => Carbon::parse($request->Date_pengajuan)->format('Y-m-d'),
          'Total'          => $request->Total, // Sudah dalam bentuk angka murni (contoh: 1500000)
          'Notes'          => $request->Notes,
    ]);

    $pengajuan->save();

    Log::info('Pengajuan berhasil dibuat.');

    return redirect()
        ->route('page.indirectp.index')
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
       
         $data = Indirectp::findOrFail($id);
        return view('page.pengajuan.indirectcost.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $direct = Indirectp::findOrFail($id);
            $direct->update($request->all());
            return redirect()->route('page.indirectp.index')->with('success', 'Pengajuan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('page.indirectp.index')->with('error', 'Terjadi kesalahan saat memperbarui pengajuan.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
      public function destroy($id)
{
    try {
        $item = Indirectp::findOrFail($id);
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
        $item = Indirectp::withTrashed()->findOrFail($id);
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
        $item = Indirectp::withTrashed()->findOrFail($id);
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
     public function export(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

       return Excel::download(new IndirectpExport ($start_date, $end_date), 'indirectp.xlsx');

     }
}
