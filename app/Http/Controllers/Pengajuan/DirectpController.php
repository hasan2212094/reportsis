<?php

namespace App\Http\Controllers\Pengajuan;

use Carbon\Carbon;
use App\Models\DirectP;
use App\Models\Workorder;
use Illuminate\Http\Request;
use App\Exports\DirectpExport;
use App\Imports\DirectpImport;
use Illuminate\Support\Facades\DB;
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
        $query = DirectP::with('workorder'); // << ini penting

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
    // $itemList = DirectP::select('item_id', 'Item')
    //     ->orderBy('item_id')
    //     ->get();

    // // Ambil semua Workorder (No WO) untuk dropdown
    // $workorders = Workorder::orderBy('kode_wo', 'asc')->get();

    // // Auto-generate ID baru (misal: ITEM001, ITEM002, dst)
    // $lastItem = DirectP::orderBy('id', 'desc')->first();
    // if ($lastItem && preg_match('/ITEM(\d+)/', $lastItem->item_id, $matches)) {
    //     $nextNumber = (int)$matches[1] + 1;
    // } else {
    //     $nextNumber = 1;
    // }

    // $newItemId = 'ITEM' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

    // // Kirim data ke view
    // return view('page.pengajuan.Directcost.create', compact('itemList', 'newItemId', 'workorders'));
     // Ambil semua Item untuk referensi (kalau masih diperlukan)
    $itemList = DirectP::select('item_id', 'Item')
        ->orderBy('item_id')
        ->get();

    // Ambil semua Workorder untuk dropdown
    $workorders = Workorder::orderBy('kode_wo', 'asc')->get();

    // Cari ITEM terakhir
    $lastItem = DirectP::orderBy('id', 'desc')->first();

    if ($lastItem && preg_match('/ITEM(\d+)/', $lastItem->item_id, $matches)) {
        $nextNumber = (int)$matches[1] + 1;
    } else {
        $nextNumber = 1;
    }

    // Format jadi ITEM003, ITEM010, dst
    $newItemId = 'ITEM' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

    return view('page.pengajuan.Directcost.create', compact('itemList', 'newItemId', 'workorders'));
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
    // Bersihkan format angka
    $request->merge([
        'Total' => preg_replace('/[^0-9]/', '', $request->Total)
    ]);

    // Ambil item_id dari user
    $itemId = $request->item_id;

    // Jika user tidak pilih (kosong), baru generate otomatis
    if (empty($itemId)) {
        $lastItem = DB::table('direct_p_s')
            ->where('item_id', 'like', 'ITEM%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastItem && preg_match('/ITEM(\d+)/', $lastItem->item_id, $matches)) {
            $nextNumber = (int)$matches[1] + 1;
        } else {
            $nextNumber = 1;
        }

        $itemId = 'ITEM' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    // ✅ Cek apakah item_id sudah ada
    $exists = DB::table('direct_p_s')->where('item_id', $itemId)->exists();
    if ($exists) {
        return back()
            ->withErrors(['item_id' => 'ITEM ID ' . $itemId . ' sudah ada di database.'])
            ->withInput();
    }

   // Validasi input lain
    // $validated = $request->validate([
    //     'Item'           => 'required|string|max:255',
    //     'Qty'            => 'required|integer|min:1',
    //     'Unit'           => 'required|string|max:50',
    //     'Needed_by'      => 'required|string|max:255',
    //     'Date_pengajuan' => 'required|date',
    //     'Total'          => 'required|numeric|min:0',
    // ],[
    //     'Item.required'           => 'Nama item wajib diisi.',
    //     'Qty.required'            => 'Jumlah Qty wajib diisi.',
    //     'Unit.required'           => 'Satuan wajib diisi.',
    //     'Needed_by.required'      => 'Kebutuhan wajib diisi.',
    //     'Date_pengajuan.required' => 'Tanggal pengajuan wajib diisi.',
    //     'Total.required'          => 'Total wajib diisi.',
    // ]);

    // // Simpan data
    // $validated = $request->validate([
    //     'Item_id' => 'required|string|max:255',
    //     'Workorder_id' => 'required|string|max:255',
    //     'Qty' => 'required|numeric|min:1',
    //     'Unit' => 'required|string|min:0',
    //     'Needed_by' => 'nullable|string',
    //     'needed_by_input' => 'nullable|string|max:255',
    //     'Date_pengajuan' => 'required|date',
    //     'Total' => 'required|numeric|min:0',
    //     'Notes' => 'nullable|string|max:500',
    // ]);

     Log::info('🔹 DirectP store called', $request->all()); // log semua input request

    try {
    $validated = $request->validate([
        'item_id' => 'required|string|max:100|unique:direct_p_s,item_id',
        'Item' => 'required|string|max:255',
        'Qty' => 'required|numeric|min:1',
        'Unit' => 'required|string|max:255',
        'needed_by' => 'nullable|string|max:255',
        'needed_by_input' => 'nullable|string|max:255',
        'Date_pengajuan' => 'required|date',
        'Total' => 'required|numeric|min:0',
        'Notes' => 'nullable|string|max:500',
    ]);

    Log::info('✅ Validation passed', $validated);

    $directCost = new DirectP();
    $directCost->item_id = $validated['item_id'];
    $directCost->Item = $validated['Item'];
    $directCost->Qty = $validated['Qty'];
    $directCost->Unit = $validated['Unit'];
    $directCost->Date_pengajuan = $validated['Date_pengajuan'];
    $directCost->Total = $validated['Total'];
    $directCost->Notes = $validated['Notes'] ?? null;

    // tentukan kebutuhan manual atau WO
    $this->assignWorkorderOrManual($directCost, $request);

    $directCost->save();

    Log::info('💾 DirectP saved successfully', ['id' => $directCost->id]);

    return redirect()->route('page.pengajuan.Directcost.index')->with('success', 'Data Direct Cost berhasil ditambahkan.');
} catch (\Throwable $e) {
    Log::error('❌ Error in DirectP store', [
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
    ]);

    return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
}
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
     
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
         $data = DirectP::findOrFail($id);
         $workorders = Workorder::all(); // ambil semua WO
         return view('page.pengajuan.Directcost.edit', compact('data', 'workorders'));
    }

    /**
     * Update the specified resource in storage.
     */
//    public function update(Request $request, $id)
// {
//     try {
//         // 🔹 Validasi input
//         $validated = $request->validate([
//             'item_id' => 'required|string|max:255',
//             'Item' => 'required|string|max:255',
//             'Qty' => 'required|numeric',
//             'Unit' => 'required|string|max:50',
//             'Needed_by' => 'nullable', // 🟢 jangan integer
//             'Needed_by_manual' => 'nullable|string|max:255',
//             'Date_pengajuan' => 'required|date',
//             'Total' => 'required|numeric',
//             'Notes' => 'nullable|string',
//         ]);

//         // 🔹 Ambil data DirectP berdasarkan ID
//         $directp = DirectP::findOrFail($id);

//         // 🔹 Update field umum
//         $directp->item_id = $request->item_id;
//         $directp->Item = $request->Item;
//         $directp->Qty = $request->Qty;
//         $directp->Unit = $request->Unit;
//         $directp->Date_pengajuan = $request->Date_pengajuan;
//         $directp->Total = $request->Total;
//         $directp->Notes = $request->Notes;

//         // 🔹 Logika khusus: pilihan WO atau manual
//       if ($request->Needed_by === 'manual') {
//     // input manual
//     $directp->Needed_by = $request->Needed_by_manual;
//     $directp->workorder_id = null;
// } elseif (is_numeric($request->Needed_by)) {
//     // pilih workorder
//     $directp->workorder_id = $request->Needed_by;
//     $directp->Needed_by = null;
// } else {
//     $directp->Needed_by = null;
//     $directp->workorder_id = null;
// }
//         // 🔹 Simpan perubahan
//         $directp->save();

//         Log::info('Update Direct Cost berhasil', [
//             'id' => $id,
//             'needed_by' => $directp->Needed_by,
//             'workorder_id' => $directp->workorder_id
//         ]);

//         return redirect()
//             ->route('page.pengajuan.Directcost.index')
//             ->with('success', 'Data berhasil diperbarui!');
//     } catch (\Exception $e) {
//         Log::error('Gagal update Direct Cost', [
//             'id' => $id,
//             'error' => $e->getMessage()
//         ]);

//         return redirect()->back()
//             ->with('error', 'Terjadi kesalahan saat memperbarui data.');
//     }
// }
// public function update(Request $request, $id)
// {
//     $directCost = DirectP::findOrFail($id);

//     // Cek pilihan "manual" atau "workorder"
//     if ($request->needed_by === 'manual') {
//     $directCost->needed_by = $request->needed_by_manual;
//     $directCost->workorder_id = null;
// } else {
//     // cari id berdasarkan kode_wo
//     $wo = Workorder::where('kode_wo', $request->needed_by)->first();
//     $directCost->workorder_id = $wo ? $wo->id : null;
//     $directCost->needed_by = null;
// }

//     dd($request->needed_by);

//     $directCost->save();

//     Log::info('Update Direct Cost berhasil', [
//         'id' => $id,
//         'needed_by' => $directCost->needed_by,
//         'workorder_id' => $directCost->workorder_id,
//     ]);

//     // ✅ Redirect ke halaman index
//     return redirect()->route('page.pengajuan.Directcost.index')
//         ->with('success', 'Data Direct Cost berhasil diperbarui!');
// }
//     /**
//      * Remove the specified resource from storage.

public function update(Request $request, $id)
{
//   $request->validate([
//             'item_id' => 'required|string|max:255',
//             'Item' => 'nullable|string|max:255',
//             'Qty' => 'nullable|numeric|min:0',
//             'Unit' => 'nullable|string|max:50',
//             'Needed_by' => 'nullable|string|max:255',
//             'Date_pengajuan' => 'nullable|date',
//             'Total' => 'nullable|numeric|min:0',
//             'Notes' => 'nullable|string|max:500',
//         ]);

//         $directCost = DirectP::findOrFail($id);
//         $this->assignWorkorderOrManual($directCost, $request);

//         $directCost->item_id = $request->item_id;
//         $directCost->Item = $request->Item;
//         $directCost->Qty = $request->Qty;
//         $directCost->Unit = $request->Unit;
//         $directCost->Date_pengajuan = $request->Date_pengajuan;
//         $directCost->Total = $request->Total;
//         $directCost->Notes = $request->Notes;

//         $directCost->save();

//         return redirect()->route('page.pengajuan.Directcost.index')
//             ->with('success', 'Data Direct Cost berhasil diperbarui.');
   $validated = $request->validate([
        'item_id' => 'required|string|max:255',
        'Item' => 'nullable|string|max:255',
        'Qty' => 'required|numeric|min:1',
        'Unit' => 'required|string|max:50',
        'needed_by' => 'nullable|string',             // workorder id atau 'manual'
        'needed_by_manual' => 'nullable|string|max:255', // input manual
        'Date_pengajuan' => 'required|date',
        'Total' => 'required|numeric|min:0',
        'Notes' => 'nullable|string|max:500',
    ]);

    $directCost = DirectP::findOrFail($id);

    // Isi field dasar
    $directCost->item_id = $validated['item_id'];
    $directCost->Item = $validated['Item'];
    $directCost->Qty = $validated['Qty'];
    $directCost->Unit = $validated['Unit'];
    $directCost->Total = $validated['Total'];
    $directCost->Date_pengajuan = $validated['Date_pengajuan'];
    $directCost->Notes = $validated['Notes'] ?? null;

    // 🔹 Pilihan: manual atau workorder
    if ($request->needed_by === 'manual') {
        $directCost->Needed_by = $request->needed_by_manual; // teks manual
        $directCost->workorder_id = null;                    // hapus relasi WO
    } else {
        $directCost->workorder_id = $request->needed_by;     // relasi ke WO
        $directCost->Needed_by = null;                       // kosongkan manual
    }

    $directCost->save();

    return redirect()
        ->route('page.pengajuan.Directcost.index')
        ->with('success', 'Data Direct Cost berhasil diperbarui.');
}
     public function destroy($id)
{
    try {
        $item = DirectP::findOrFail($id);
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
        $item = DirectP::withTrashed()->findOrFail($id);
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
        $item = DirectP::withTrashed()->findOrFail($id);
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
        $start_date = $request->get('start_date');
        $end_date   = $request->get('end_date');

       return Excel::download(new DirectpExport($start_date, $end_date), 'directp.xlsx');
}
 private function assignWorkorderOrManual($directCost, Request $request)
    {
        $needed = $request->Needed_by;
    $manualValue = $request->needed_by_input ?? null;

    if ($needed === 'manual') {
        // Input manual (bukan dari WO)
        $directCost->needed_by = $manualValue;
        $directCost->workorder_id = null;
        return;
    }

    if ($needed) {
        // Kalau bentuknya WO2025xxx
        if (preg_match('/^WO\d+$/', $needed)) {
            $workorder = Workorder::where('kode_wo', $needed)->first();
            if ($workorder) {
                $directCost->workorder_id = $workorder->id;
                $directCost->needed_by = null;
            } else {
                // Tidak ditemukan di tabel WO
                $directCost->workorder_id = null;
                $directCost->needed_by = $needed;
            }
        } else {
            // Kalau user kirim angka langsung (id workorder)
            $directCost->workorder_id = is_numeric($needed) ? $needed : null;
            $directCost->needed_by = null;
        }
    } else {
        // Tidak pilih apapun
        $directCost->workorder_id = null;
        $directCost->needed_by = null;
    }
    }
   public function checkItem($item_id)
{
    Log::info("Cek item dipanggil untuk:" . $item_id);

    $exists = DB::table('direct_p_s')->where('item_id', $item_id)->exists();
    Log::info("Item {$item_id} status: " . ($exists ? 'ADA' : 'BELUM ADA'));

    return response()->json(['exists' => $exists]);
}

    public function import(Request $request)
    {
    $request->validate([
        'file' => 'required|mimes:xlsx,xls'
    ]);

    try {
        // Jalankan import
        Excel::import(new  DirectpImport, $request->file('file'));

        return redirect()->back()->with('success', '✅ Data Directcost berhasil di import!');
    } catch (\Exception $e) {
        Log::error('❌ Import gagal: ' . $e->getMessage());
        return redirect()->back()->with('error', '❌ Terjadi kesalahan saat import. Lihat log untuk detail.');
    }

    }
}
