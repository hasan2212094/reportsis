<?php

namespace App\Http\Controllers\Actual;

use Carbon\Carbon;
use App\Models\Ppn;
use App\Models\Ppna;
use App\Exports\PpnaExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;



class PpnaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       $query = Ppna::query();

      if ($request->start_date && $request->end_date) {
        $query->whereBetween('Date_actual', [
            $request->start_date,
            $request->end_date
        ]);
       }

    $data = $query->get();

    return view('page.actual.ppna.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ppn = Ppn::all(); // ambil semua data dari tabel direct_p_s
        return view('page.actual.ppna.create', compact('ppn'));
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
        //         'ppns_id'           => 'required|exists:ppns,id',
        //         'Qty'               => 'required|integer|min:1',
        //         'Unit'              => 'required|string|max:50',
        //         'Date_actual'       => 'required|date_format:Y-m-d',
        //         'Toko'              => 'required|string|max:255',
        //         'Transaksi'         => 'required|integer|min:0',
        //         'Total'             => 'required|numeric|min:0',
        //     ]);

        //     $ppn = new Ppna([
        //         'ppns_id'         => $request->ppns_id,
        //         'Qty'             => $request->Qty,
        //         'Unit'            => $request->Unit,
        //         'Date_actual'     => Carbon::parse($request->Date_actual)->format('Y-m-d'),
        //         'Toko'            => $request->Toko,
        //         'Transaksi'       => $request->Transaksi,
        //         'Total'           => $request->Total,
        //     ]);

        //     $ppn->save(); 

        //     return redirect()->route('page.ppna.index')->with('success', 'Pengajuan berhasil dibuat.');
        // } catch (\Exception $e) {
        //     return redirect()->route('page.ppna.index')->with('error', 'Terjadi kesalahan saat membuat pengajuan.');
        // }
    Log::info('Membuat pengajuan dengan data: ' . json_encode($request->all()));

    // Bersihkan format Rupiah sebelum validasi
    $request->merge([
        'Total' => preg_replace('/[^0-9]/', '', $request->Total)
    ]);

    // Validasi otomatis: kalau gagal, langsung redirect balik
    $validated = $request->validate([
          'ppns_id'           => 'required|exists:ppns,id',
            'Qty'               => 'required|integer|min:1',
            'Unit'              => 'required|string|max:50',
            'Date_actual'       => 'required|date_format:Y-m-d',
            'Toko'              => 'required|string|max:255',
            'Transaksi'         => 'required|integer|min:0',
            'Total'             => 'required|numeric|min:0',
    ],[
        'ppns_id.required'   => 'ITEM wajib diisi.',
        'Qty.required'             => 'Jumlah item wajib diisi.',
        'Unit.required'            => 'Satuan wajib diisi.',
        'Date_actual.required'     => 'Tanggal Actual wajib diisi.',
        'Toko.required'            => 'Toko wajib diisi.',
        'transaksi.required'       => 'Transaksi wajib diisi.',
        'Total.required'           => 'Total wajib diisi.',
    ]);

    // Kalau validasi berhasil → lanjut simpan
   $directa = new Ppna([
               'ppns_id'         => $request->ppns_id,
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
        ->route('page.ppna.index')
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
         $ppn = Ppn::all();
         $data = Ppna::findOrFail($id);
         return view('page.actual.ppna.edit', compact('ppn', 'data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try{
        $request->validate([
        'ppns_id'       => 'required|exists:ppns,id',
        'Qty'           => 'required|integer|min:1',
        'Unit'          => 'required|string|max:50',
        'Date_actual'   => 'required|date',
        'Toko'          => 'required|string|max:255',
        'Transaksi'     => 'required|integer',
        'Total'         => 'required|string|min:0 ',
    ]);

    $ppna = Ppna::findOrFail($id);

    $ppna->update($request->only([
        'ppns_id',
        'Qty',
        'Unit',
        'Date_actual',
        'Toko',
        'Transaksi',
        'Total'
    ]));

    return redirect()
        ->route('page.ppna.index')
        ->with('success', 'Pengajuan berhasil diperbarui.');
    } catch (\Exception $e) {
        return redirect()->route('page.ppna.index')->with('error', 'Terjadi kesalahan saat memperbarui pengajuan.');
        
    } 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         try {
            $ppna = Ppna::findOrFail($id);
            $ppna->delete();
            return redirect()->route('page.ppna.index')->with('success', 'Pengajuan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('page.ppna.index')->with('error', 'Terjadi kesalahan saat menghapus pengajuan.');
        }
    }
     public function exports()
    {
        return Excel::download(new PpnaExport, 'ppna.xlsx');
    }
}
