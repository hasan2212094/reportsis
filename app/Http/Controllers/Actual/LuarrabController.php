<?php

namespace App\Http\Controllers\Actual;

use Carbon\Carbon;
use App\Models\Luarrab;
use Illuminate\Http\Request;
use App\Exports\LuarrabExport;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class LuarrabController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Luarrab::query();

      if ($request->start_date && $request->end_date) {
        $query->whereBetween('Date_actual', [
            $request->start_date,
            $request->end_date
        ]);
       }

    $data = $query->get();

    return view('page.actual.luarrab.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $luarrab = Luarrab::all(); // ambil semua data dari tabel direct_p_s
        return view('page.actual.luarrab.create', compact('luarrab'));
    }

    /**
     * Store a newly created resource in storage.
     */
  public function store(Request $request)
{
    // try {
    //      $request->merge([
    //        'Total' => preg_replace('/[^0-9]/', '', $request->Total)
    //        ]);
    //     // 1. Validasi data
    //     $request->validate([
    //         'luarrabps_id'      => 'required|string|max:100|unique:luarrabs,luarrabps_id',
    //         'Needed_by'         => 'required|string|max:255',
    //         'Qty'               => 'required|integer|min:1',
    //         'Unit'              => 'required|string|max:50',
    //         'Date_actual'       => 'required|date_format:Y-m-d',
    //         'Toko'              => 'required|string|max:255',
    //         'Transaksi'         => 'required|integer|min:0',
    //         'Total'             => 'required|numeric|min:0',
    //     ]);

    //     // Log setelah validasi
    //     Log::info('Data request setelah validasi:', $request->all());

    //     // 2. Buat model baru
    //     $luarrab = new Luarrab([
    //         'luarrabps_id'    => $request->luarrabps_id,
    //         'Needed_by'       => $request->Needed_by,
    //         'Qty'             => $request->Qty,
    //         'Unit'            => $request->Unit,
    //         'Date_actual'     => Carbon::parse($request->Date_actual)->format('Y-m-d'),
    //         'Toko'            => $request->Toko,
    //         'Transaksi'       => $request->Transaksi,
    //         'Total'           => $request->Total,
    //     ]);

    //     // Log sebelum save
    //     Log::info('Data Luarrab yang akan disimpan:', $luarrab->toArray());

    //     // 3. Simpan ke database
    //     $luarrab->save();

    //     // Log setelah save
    //     Log::info('Data Luarrab berhasil disimpan:', $luarrab->toArray());

    //     return redirect()->route('page.luarrab.index')
    //         ->with('success', 'Pengajuan berhasil dibuat.');
    // } catch (\Exception $e) {
    //     // Log error detail
    //     Log::error('Error saat menyimpan Luarrab: '.$e->getMessage(), [
    //         'trace' => $e->getTraceAsString(),
    //     ]);

    //     return redirect()->route('page.luarrab.index')
    //         ->with('error', 'Terjadi kesalahan saat membuat Actual.');
    // }
    Log::info('Membuat pengajuan dengan data: ' . json_encode($request->all()));

    // Bersihkan format Rupiah sebelum validasi
    $request->merge([
        'Total' => preg_replace('/[^0-9]/', '', $request->Total)
    ]);

    // Validasi otomatis: kalau gagal, langsung redirect balik
    $validated = $request->validate([
          'luarrabps_id'      => 'required|string|max:100|unique:luarrabs,luarrabps_id',
          'Needed_by'         => 'required|string|max:255',
          'Qty'               => 'required|integer|min:1',
          'Unit'              => 'required|string|max:50',
          'Date_actual'       => 'required|date_format:Y-m-d',
          'Toko'              => 'required|string|max:255',
          'Transaksi'         => 'required|integer|min:0',
          'Total'             => 'required|numeric|min:0',
    ],[
        'luarrabps_id.required'     => 'ITEM wajib diisi.',
        'luarrabps_id.unique'       => 'ITEM sudah ada.',
        'Needed_by.required'        => 'Kebutuhan wajib diisi.',
        'Qty.required'             => 'Jumlah item wajib diisi.',
        'Unit.required'            => 'Satuan wajib diisi.',
        'Date_actual.required'     => 'Tanggal Actual wajib diisi.',
        'Toko.required'            => 'Toko wajib diisi.',
        'transaksi.required'       => 'Transaksi wajib diisi.',
        'Total.required'           => 'Total wajib diisi.',
    ]);

    // Kalau validasi berhasil → lanjut simpan
    $luarrab = new Luarrab([
             'luarrabps_id'    => $request->luarrabps_id,
             'Needed_by'       => $request->Needed_by,
             'Qty'             => $request->Qty,
             'Unit'            => $request->Unit,
             'Date_actual'     => Carbon::parse($request->Date_actual)->format('Y-m-d'),
             'Toko'            => $request->Toko,
             'Transaksi'       => $request->Transaksi,
             'Total'           => $request->Total,
         ]);

        //      Log sebelum save
     Log::info('Data Luarrab yang akan disimpan:', $luarrab->toArray());

        // 3. Simpan ke database
     $luarrab->save();
    Log::info('Pengajuan berhasil dibuat.');

    return redirect()
        ->route('page.luarrab.index')
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
         $luarrab = Luarrab::all();
         $data = Luarrab::findOrFail($id);
         return view('page.actual.luarrab.edit', compact('luarrab', 'data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $luarrab = Luarrab::findOrFail($id);
            $luarrab->update($request->all());
            return redirect()->route('page.luarrab.index')->with('success', 'Pengajuan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('page.luarrab.index')->with('error', 'Terjadi kesalahan saat memperbarui pengajuan.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $ppna = Luarrab::findOrFail($id);
            $ppna->delete();
            return redirect()->route('page.luarrab.index')->with('success', 'Pengajuan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('page.luarrab.index')->with('error', 'Terjadi kesalahan saat menghapus pengajuan.');
        }
    }
    public function export()
    {
        $luarrab = Luarrab::all();
        return Excel::download(new LuarrabExport($luarrab), 'luarrab.xlsx');
    }
}
