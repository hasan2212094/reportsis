<?php

namespace App\Http\Controllers\Pengajuan;

use Carbon\Carbon;
use App\Models\Ppn;
use App\Exports\PpnExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class PpnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         $query = Ppn::query();

      if ($request->start_date && $request->end_date) {
        $query->whereBetween('Date_pengajuan', [
            $request->start_date,
            $request->end_date
        ]);
       }

    $data = $query->get();

    return view('page.pengajuan.ppn.index', compact('data'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         $ppn = Ppn::all(); // ambil semua data dari tabel direct_p_s
       return view('page.pengajuan.ppn.create', compact('ppn'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
//    try {
//     Log::info('Membuat pengajuan dengan data: ' . json_encode($request->all()));

//      $request->merge([
//         'Total' => preg_replace('/[^0-9]/', '', $request->Total)
//     ]);

//     $request->validate([
//         'item_id'   => 'required|string|max:100|unique:ppns,item_id',
//         'Item'      => 'required|string|max:255',
//         'Qty'       => 'required|integer|min:1',
//         'Unit'      => 'required|string|max:50',
//         'Needed_by' => 'required|string|max:255',
//         'Date_pengajuan' => 'required|date_format:Y-m-d',
//         'Total'     => 'required|numeric|min:0',
//     ],[
//         'item_id.required'   => 'ITEM ID wajib diisi.',
//         'item_id.unique'     => 'ITEM ID sudah terdaftar.',
//         'item.required'      => 'Nama item wajib diisi.',
//         'qty.required'       => 'Jumlah (Qty) wajib diisi.',
//         'unit.required'      => 'Satuan wajib diisi.',
//         'needed_by.required' => 'Tanggal kebutuhan wajib diisi.',
//         'Date_pengajuan.required' => 'Tanggal pengajuan wajib diisi.',
//         'total.required'     => 'Total wajib diisi.',
//     ]);

//     $ppn = new Ppn([
//         'item_id'   => $request->item_id,
//         'Item'      => $request->Item,
//         'Qty'       => $request->Qty,
//         'Unit'      => $request->Unit,
//         'Needed_by' => $request->Needed_by,
//         'Date_pengajuan' => Carbon::parse($request->Date_pengajuan)->format('Y-m-d'),
//         'Total'     => $request->Total,
//         'Notes'     => $request->Notes,
//     ]);

//     $ppn->save();

//     Log::info('Pengajuan berhasil dibuat.');

//     return redirect()
//         ->route('page.ppn.index')
//         ->with('success', 'Pengajuan berhasil dibuat.');

// } catch (\Exception $e) {
//     Log::error('Error membuat pengajuan: ' . $e->getMessage());

//     return redirect()
//         ->route('page.ppn.index')
//         ->with('error', 'Terjadi kesalahan saat membuat pengajuan.');
// }
Log::info('Membuat pengajuan dengan data: ' . json_encode($request->all()));

    // Bersihkan format Rupiah sebelum validasi
    $request->merge([
        'Total' => preg_replace('/[^0-9]/', '', $request->Total)
    ]);

    // Validasi otomatis: kalau gagal, langsung redirect balik
    $validated = $request->validate([
        'item_id'        => 'required|string|max:100|unique:ppns,item_id',
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
   $pengajuan = new Ppn([
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
        ->route('page.ppn.index')
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
        $data = Ppn::findOrFail($id);
        return view('page.pengajuan.ppn.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $direct = Ppn::findOrFail($id);
            $direct->update($request->all());
            return redirect()->route('page.ppn.index')->with('success', 'Pengajuan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('page.ppn.index')->with('error', 'Terjadi kesalahan saat memperbarui pengajuan.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $direct = Ppn::findOrFail($id);
            $direct->delete();
            return redirect()->route('page.ppn.index')->with('success', 'direct cost berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('page.ppn.index')->with('error', 'Terjadi kesalahan saat menghapus pengajuan.');
        }
    }
    public function export(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        return Excel::download(new PpnExport($start_date, $end_date), 'ppn.xlsx');
    }
}
