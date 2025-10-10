<?php

namespace App\Http\Controllers\Actual;

use Carbon\Carbon;
use App\Models\Luarrab;
use App\Models\Workorder;
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
       $query = Luarrab::with('workorder'); // << ini penting

      if ($request->start_date && $request->end_date) {
        $query->whereBetween('Date_actual', [
            $request->start_date,
            $request->end_date
        ]);
       }

    $data = $query->get();
    $trashed = Luarrab::onlyTrashed()->get();

    return view('page.actual.luarrab.index', compact('data', 'trashed'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
          $itemList = Luarrab::select('luarrabps_id', 'Item')
        ->orderBy('luarrabps_id')
        ->get();

    // Ambil semua Workorder untuk dropdown
    $workorders = Workorder::orderBy('kode_wo', 'asc')->get();

    // Cari Luar RAB terakhir
    $lastItem = Luarrab::orderBy('id', 'desc')->first();

    if ($lastItem && preg_match('/ITEM(\d+)/', $lastItem->luarrabps_id, $matches)) {
        $nextNumber = (int) $matches[1] + 1;
    } else {
        $nextNumber = 1;
    }

    // Format jadi ITEM0001, ITEM0002, dst
    $newLuarrabpsId = 'ITEM' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

    // Kirim ke view
    return view('page.actual.luarrab.create', compact('itemList', 'newLuarrabpsId', 'workorders'));
    }

    /**
     * Store a newly created resource in storage.
     */
//   public function store(Request $request)
// {
//     // try {
//     //      $request->merge([
//     //        'Total' => preg_replace('/[^0-9]/', '', $request->Total)
//     //        ]);
//     //     // 1. Validasi data
//     //     $request->validate([
//     //         'luarrabps_id'      => 'required|string|max:100|unique:luarrabs,luarrabps_id',
//     //         'Needed_by'         => 'required|string|max:255',
//     //         'Qty'               => 'required|integer|min:1',
//     //         'Unit'              => 'required|string|max:50',
//     //         'Date_actual'       => 'required|date_format:Y-m-d',
//     //         'Toko'              => 'required|string|max:255',
//     //         'Transaksi'         => 'required|integer|min:0',
//     //         'Total'             => 'required|numeric|min:0',
//     //     ]);

//     //     // Log setelah validasi
//     //     Log::info('Data request setelah validasi:', $request->all());

//     //     // 2. Buat model baru
//     //     $luarrab = new Luarrab([
//     //         'luarrabps_id'    => $request->luarrabps_id,
//     //         'Needed_by'       => $request->Needed_by,
//     //         'Qty'             => $request->Qty,
//     //         'Unit'            => $request->Unit,
//     //         'Date_actual'     => Carbon::parse($request->Date_actual)->format('Y-m-d'),
//     //         'Toko'            => $request->Toko,
//     //         'Transaksi'       => $request->Transaksi,
//     //         'Total'           => $request->Total,
//     //     ]);

//     //     // Log sebelum save
//     //     Log::info('Data Luarrab yang akan disimpan:', $luarrab->toArray());

//     //     // 3. Simpan ke database
//     //     $luarrab->save();

//     //     // Log setelah save
//     //     Log::info('Data Luarrab berhasil disimpan:', $luarrab->toArray());

//     //     return redirect()->route('page.luarrab.index')
//     //         ->with('success', 'Pengajuan berhasil dibuat.');
//     // } catch (\Exception $e) {
//     //     // Log error detail
//     //     Log::error('Error saat menyimpan Luarrab: '.$e->getMessage(), [
//     //         'trace' => $e->getTraceAsString(),
//     //     ]);

//     //     return redirect()->route('page.luarrab.index')
//     //         ->with('error', 'Terjadi kesalahan saat membuat Actual.');
//     // }
//     // 1️⃣ Log awal semua request masuk
//    Log::info('📦 Membuat Luar RAB baru', $request->all());

//     // Bersihkan format angka Rupiah agar tidak error
//     $request->merge([
//         'luarrabps_id' => $request->item_id,
//         'Total' => preg_replace('/[^0-9]/', '', $request->Total)
//     ]);

//     // Validasi input
//     $validated = $request->validate([
//         'luarrabps_id' => 'required|string|max:100|unique:luarrabs,luarrabps_id',
//         'Needed_by' => 'nullable|string|max:255',
//         'needed_by_manual' => 'nullable|string|max:255',
//         'Qty' => 'required|integer|min:1',
//         'Unit' => 'required|string|max:50',
//         'Date_actual' => 'required|date_format:Y-m-d',
//         'Toko' => 'required|string|max:255',
//         'Transaksi' => 'required|integer|min:0',
//         'Total' => 'required|numeric|min:0',
//     ], [
//         'luarrabps_id.required' => 'ITEM wajib diisi.',
//         'luarrabps_id.unique' => 'ITEM sudah ada.',
//         'Qty.required' => 'Jumlah item wajib diisi.',
//         'Unit.required' => 'Satuan wajib diisi.',
//         'Date_actual.required' => 'Tanggal Actual wajib diisi.',
//         'Toko.required' => 'Toko wajib diisi.',
//         'Transaksi.required' => 'Transaksi wajib diisi.',
//         'Total.required' => 'Total wajib diisi.',
//     ]);
//       Log::info('✅ Validation passed', $validated);

//     $luarrab = new Luarrab();
//     $luarrab->luarrabps_id = $validated['luarrabps_id'];
//     $luarrab->Qty = $validated['Qty'];
//     $luarrab->Unit = $validated['Unit'];
//     $luarrab->Date_actual = Carbon::parse($validated['Date_actual'])->format('Y-m-d');
//     $luarrab->Toko = $validated['Toko'];
//     $luarrab->Transaksi = $validated['Transaksi'];
//     $luarrab->Total = $validated['Total'];

//     // 🔹 Tentukan kebutuhan manual atau workorder
//     $this->assignWorkorderOrManual($luarrab, $request);

//     $luarrab->save();

//     Log::info('✅ Luar RAB berhasil dibuat', ['id' => $luarrab->id]);

//     return redirect()
//         ->route('page.luarrab.index')
//         ->with('success', 'Pengajuan Luar RAB berhasil dibuat.');
// }
public function store(Request $request)
{
    Log::info('📦 Membuat Luar RAB baru', $request->all());

    // Bersihkan format angka Rupiah agar tidak error
    $request->merge([
        'Total' => preg_replace('/[^0-9]/', '', $request->Total)
    ]);

    // Validasi input
    $validated = $request->validate([
        'luarrabps_id' => 'required|string|max:100|unique:luarrabs,luarrabps_id',
        'Needed_by' => 'nullable|string|max:255',
        'needed_by_input' => 'nullable|string|max:255',
        'Item' => 'required|string|max:255',
        'Qty' => 'required|integer|min:1',
        'Unit' => 'required|string|max:50',
        'Date_actual' => 'required|date_format:Y-m-d',
        'Toko' => 'required|string|max:255',
        'Transaksi' => 'required|integer|min:0',
        'Total' => 'required|numeric|min:0',
    ]);

    Log::info('✅ Validation passed', $validated);

    $luarrab = new Luarrab();
    $luarrab->luarrabps_id = $validated['luarrabps_id'];
    $luarrab->Item = $validated['Item'];
    $luarrab->Qty = $validated['Qty'];
    $luarrab->Unit = $validated['Unit'];
    $luarrab->Date_actual = Carbon::parse($validated['Date_actual'])->format('Y-m-d');
    $luarrab->Toko = $validated['Toko'];
    $luarrab->Transaksi = $validated['Transaksi'];
    $luarrab->Total = $validated['Total'];

    // 🔹 Tentukan kebutuhan manual atau workorder
    if ($request->Needed_by === 'manual') {
        // Input manual
        $luarrab->Needed_by = $request->needed_by_input;
        $luarrab->workorder_id = null;
        Log::info('📝 Input manual dipilih', ['needed_by' => $luarrab->Needed_by]);
    } else {
        // Cari workorder berdasarkan kode WO
        $workorder = \App\Models\Workorder::where('kode_wo', $request->Needed_by)->first();
        if ($workorder) {
            $luarrab->workorder_id = $workorder->id;
            $luarrab->Needed_by = null;
            Log::info('🔗 Workorder ditemukan', ['workorder_id' => $workorder->id]);
        } else {
            // fallback — simpan string apa adanya agar tidak hilang
            $luarrab->Needed_by = $request->Needed_by;
            $luarrab->workorder_id = null;
            Log::warning('⚠️ Workorder tidak ditemukan, disimpan manual', ['needed_by' => $request->Needed_by]);
        }
    }

    // Simpan ke database
    $luarrab->save();

    Log::info('💾 Data tersimpan', $luarrab->toArray());

    return redirect()
        ->route('page.luarrab.index')
        ->with('success', 'Pengajuan Luar RAB berhasil dibuat.');
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
         $data = Luarrab::findOrFail($id);
         $workorders = Workorder::orderBy('kode_wo', 'asc')->get();
         return view('page.actual.luarrab.edit', compact('data', 'workorders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       Log::info('✏️ Update Luar RAB ID: ' . $id, $request->all());

    $request->merge([
        'Total' => preg_replace('/[^0-9]/', '', $request->Total)
    ]);

    $validated = $request->validate([
        'Needed_by' => 'nullable|string|max:255',
        'needed_by_input' => 'nullable|string|max:255',
        'Item' => 'required|string|max:255',
        'Qty' => 'required|integer|min:1',
        'Unit' => 'required|string|max:50',
        'Date_actual' => 'required|date_format:Y-m-d',
        'Toko' => 'required|string|max:255',
        'Transaksi' => 'required|integer|min:0',
        'Total' => 'required|numeric|min:0',
    ]);

    $luarrab = Luarrab::findOrFail($id);

    $luarrab->Item = $validated['Item'];
    $luarrab->Qty = $validated['Qty'];
    $luarrab->Unit = $validated['Unit'];
    $luarrab->Date_actual = Carbon::parse($validated['Date_actual'])->format('Y-m-d');
    $luarrab->Toko = $validated['Toko'];
    $luarrab->Transaksi = $validated['Transaksi'];
    $luarrab->Total = $validated['Total'];

    // 🔹 Tentukan kebutuhan manual atau workorder
    if ($request->Needed_by === 'manual') {
        $luarrab->Needed_by = $request->needed_by_input;
        $luarrab->workorder_id = null;
        Log::info('📝 Update manual', ['needed_by' => $luarrab->Needed_by]);
    } else {
        $workorder = Workorder::where('kode_wo', $request->Needed_by)->first();
        if ($workorder) {
            $luarrab->workorder_id = $workorder->id;
            $luarrab->Needed_by = null;
            Log::info('🔗 Update pakai Workorder', ['workorder_id' => $workorder->id]);
        } else {
            $luarrab->Needed_by = $request->Needed_by;
            $luarrab->workorder_id = null;
            Log::warning('⚠️ Workorder tidak ditemukan, simpan manual', ['needed_by' => $request->Needed_by]);
        }
    }

    $luarrab->save();
    Log::info('✅ Update berhasil', $luarrab->toArray());

    return redirect()
        ->route('page.luarrab.index')
        ->with('success', 'Data Luar RAB berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
     public function destroy($id)
{
    try {
        $item = Luarrab::findOrFail($id);
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
        $item = Luarrab::withTrashed()->findOrFail($id);
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
        $item = Luarrab::withTrashed()->findOrFail($id);
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
        return Excel::download(new LuarrabExport, 'luarrab.xlsx');
    }
    private function assignWorkorderOrManual($luarrab, $request)
{
    if ($request->Needed_by === 'manual') {
        // input manual
        $luarrab->Needed_by = $request->needed_by_input;
        $luarrab->workorder_id = null;
    } else {
        // cari workorder berdasarkan kode WO
        $workorder = \App\Models\Workorder::where('kode_wo', $request->Needed_by)->first();

        if ($workorder) {
            $luarrab->workorder_id = $workorder->id;
            $luarrab->Needed_by = null;
        } else {
            // kalau tidak ketemu, simpan tetap di kolom Needed_by sebagai teks agar tidak hilang
            $luarrab->Needed_by = $request->Needed_by;
            $luarrab->workorder_id = null;
        }
    }
}
}
