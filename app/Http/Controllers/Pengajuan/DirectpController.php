<?php

namespace App\Http\Controllers\Pengajuan;

use App\Exports\DirectpExport;
use Carbon\Carbon;
use App\Models\DirectP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class DirectpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         $query = DirectP::query();

      if ($request->start_date && $request->end_date) {
        $query->whereBetween('Date_pengajuan', [
            $request->start_date,
            $request->end_date
        ]);
       }

    $data = $query->get();
    $trashed = DirectP::onlyTrashed()->get();

    return view('page.pengajuan.Directcost.index', compact('data', 'trashed'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       return view('page.pengajuan.Directcost.create');
    }

    /**
     * Store a newly created resource in storage.
     */
//     public function store(Request $request)
//     {
//  try {
//     Log::info('Membuat pengajuan dengan data: ' . json_encode($request->all()));

//     // Bersihkan format Rupiah sebelum validasi
//     $request->merge([
//         'Total' => preg_replace('/[^0-9]/', '', $request->Total)
//     ]);

//     $request->validate([
//         'item_id'        => 'required|string|max:100|unique:direct_p_s,item_id',
//         'Item'           => 'required|string|max:255',
//         'Qty'            => 'required|integer|min:1',
//         'Unit'           => 'required|string|max:50',
//         'Needed_by'      => 'required|string|max:255',
//         'Date_pengajuan' => 'required|date_format:Y-m-d',
//         'Total'          => 'required|numeric|min:0',
//     ],[
//         'item_id.required'     => 'ITEM ID wajib diisi.',
//         'item_id.unique'       => 'ITEM ID sudah terdaftar.',
//         'Item.required'        => 'Nama item wajib diisi.',
//         'Qty.required'         => 'Jumlah Qty wajib diisi.',
//         'Unit.required'        => 'Satuan wajib diisi.',
//         'Needed_by.required'   => 'Kebutuhan wajib diisi.',
//         'Date_pengajuan.required' => 'Tanggal pengajuan wajib diisi.',
//         'Total.required'       => 'Total wajib diisi.',
//     ]);

//     $pengajuan = new DirectP([
//         'item_id'        => $request->item_id,
//         'Item'           => $request->Item,
//         'Qty'            => $request->Qty,
//         'Unit'           => $request->Unit,
//         'Needed_by'      => $request->Needed_by,
//         'Date_pengajuan' => Carbon::parse($request->Date_pengajuan)->format('Y-m-d'),
//         'Total'          => $request->Total, // Sudah dalam bentuk angka murni (contoh: 1500000)
//         'Notes'          => $request->Notes,
//     ]);

//     $pengajuan->save();

//     Log::info('Pengajuan berhasil dibuat.');

//     return redirect()
//         ->route('page.pengajuan.Directcost.index')
//         ->with('success', 'Pengajuan berhasil dibuat.');

// } catch (\Exception $e) {
//     Log::error('Error membuat pengajuan: ' . $e->getMessage());

//     return redirect()
//         ->route('page.pengajuan.Directcost.index')
//         ->with('error', 'Terjadi kesalahan saat membuat pengajuan.');
// }


// }
public function store(Request $request)
{
    Log::info('Membuat pengajuan dengan data: ' . json_encode($request->all()));

    // Bersihkan format Rupiah sebelum validasi
    $request->merge([
        'Total' => preg_replace('/[^0-9]/', '', $request->Total)
    ]);

    // Validasi otomatis: kalau gagal, langsung redirect balik
    $validated = $request->validate([
        'item_id'        => 'required|string|max:100|unique:direct_p_s,item_id',
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
   $pengajuan = new DirectP([
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
        ->route('page.pengajuan.Directcost.index')
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
         $data = DirectP::findOrFail($id);
        return view('page.pengajuan.Directcost.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         try {
            $direct = DirectP::findOrFail($id);
            $direct->update($request->all());
            return redirect()->route('page.pengajuan.Directcost.index')->with('success', 'Pengajuan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('page.pengajuan.Directcost.index')->with('error', 'Terjadi kesalahan saat memperbarui pengajuan.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
     public function destroy($id)
{
    $item = DirectP::findOrFail($id);
    $item->delete(); // soft delete
    return redirect()->back()->with('success', 'Data berhasil dihapus (soft delete)');
}

public function restore($id)
{
    $item = DirectP::withTrashed()->findOrFail($id);
    $item->restore();
    return redirect()->back()->with('success', 'Data berhasil direstore');
}

public function forceDelete($id)
{
    $item = DirectP::withTrashed()->findOrFail($id);
    $item->forceDelete();
    return redirect()->back()->with('success', 'Data dihapus permanen');
}

    public function export(Request $request)
    {
        $start_date = $request->get('start_date');
        $end_date   = $request->get('end_date');

       return Excel::download(new DirectpExport($start_date, $end_date), 'directp.xlsx');
}
}
