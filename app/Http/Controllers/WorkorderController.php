<?php

namespace App\Http\Controllers;

use App\Models\Workorder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Exports\WorkorderExport;
use Maatwebsite\Excel\Facades\Excel;

class WorkorderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $query = Workorder::query();

    // 🔎 Kolom 1 → No WO / Support By
    if ($request->filled('wo_support')) {
        $query->where(function ($q) use ($request) {
            $q->where('kode_wo', 'like', '%' . $request->wo_support . '%')
              ->orWhere('end_user', 'like', '%' . $request->wo_support . '%');
        });
    }

    // 🔎 Kolom 2 → Customer / Nama Produk
    if ($request->filled('customer_produk')) {
        $query->where(function ($q) use ($request) {
            $q->where('customer_name', 'like', '%' . $request->customer_produk . '%')
              ->orWhere('nama_produk', 'like', '%' . $request->customer_produk . '%');
        });
    }

    $totalHarga = (clone $query)->sum('total');
    // 📌 Urutan tetap seperti sebelumnya
    $workorders = $query->orderBy('kode_wo')
                        ->paginate(10)
                        ->appends($request->all());

    return view('page.workorder.index', compact('workorders', 'totalHarga'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       $workorders = Workorder::latest()->paginate(10);

       return view('page.workorder.create', compact('workorders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->merge([
        'kode_wo' => strtoupper(trim($request->kode_wo))
    ]);

    $validated = $request->validate([
        'kode_wo'          => 'required|string|max:100|unique:workorders,kode_wo',
        'customer_name'    => 'required|string|max:255',
        'address'          => 'nullable|string',
        'phone_fax'        => 'nullable|string|max:100',
        'contact_person'   => 'nullable|string|max:255',
        'customer_po_no'   => 'nullable|string|max:100',
        'customer_po_date' => 'nullable|date',
        'quantity'         => 'nullable|numeric|min:0',
        'wo_date'          => 'nullable|date',
        'nama_produk'      => 'nullable|string|max:255',
        'type_unit'        => 'nullable|string|max:100',
        'pekerjaan_selesai'=> 'nullable|date',
        'pekerjaan_termasuk'=> 'nullable|string',
        'pekerjaan_tidak_termasuk'=> 'nullable|string',
        'garansi'          => 'nullable|string',
        'total'            => 'nullable|numeric|min:0',
        'end_user'         => 'nullable|string|max:255',
        
    ]);

    Workorder::create($validated);

    return redirect()
        ->route('page.workorder.index') // pastikan ini benar
        ->with('success', 'Workorder berhasil ditambahkan.');
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
        $workorder = Workorder::findOrFail($id);
       return view('page.workorder.edit', compact('workorder'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, $id)
{
    $workorder = Workorder::findOrFail($id);

    $request->merge([
        'kode_wo' => strtoupper(trim($request->kode_wo))
    ]);

    $validated = $request->validate([
        'kode_wo' => [
            'required',
            'string',
            'max:100',
            Rule::unique('workorders', 'kode_wo')->ignore($workorder->id),
        ],
        'customer_name'    => 'required|string|max:255',
        'address'          => 'nullable|string',
        'phone_fax'        => 'nullable|string|max:100',
        'contact_person'   => 'nullable|string|max:255',
        'customer_po_no'   => 'nullable|string|max:100',
        'customer_po_date' => 'nullable|date',
        'quantity'         => 'nullable|numeric|min:0',
        'wo_date'          => 'nullable|date',
        'nama_produk'      => 'nullable|string|max:255',
        'type_unit'        => 'nullable|string|max:100',
        'pekerjaan_selesai'=> 'nullable|date',
        'pekerjaan_termasuk'=> 'nullable|string',
        'pekerjaan_tidak_termasuk'=> 'nullable|string',
        'garansi'          => 'nullable|string',
        'total'            => 'nullable|numeric|min:0',
        'end_user'         => 'nullable|string|max:255',
    ]);

    $workorder->update($validated);

    return redirect()
        ->route('page.workorder.index')
        ->with('success', 'Workorder berhasil diperbarui.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    try {
        $item = Workorder::findOrFail($id);
        $item->delete();

        Log::info('Berhasil soft delete', ['id' => $id]);

        return redirect()
            ->route('page.workorder.index')
            ->with('success', 'Data berhasil dihapus (soft delete)');
    } catch (\Exception $e) {
        Log::error('Gagal soft delete', [
            'id' => $id,
            'error' => $e->getMessage()
        ]);

        return redirect()
            ->route('page.workorder.index')
            ->with('error', 'Gagal menghapus data');
    }
}
public function restore($id)
{
    try {
        $item = Workorder::withTrashed()->findOrFail($id);
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
        $item = Workorder::withTrashed()->findOrFail($id);
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

    public function search(Request $request)
{
   
     $q = $request->get('q', '');
     $data = Workorder::where('kode_wo', 'like', "%{$q}%")->get();

    return response()->json($data);
}


public function showDetail($id)
{
    $wo = Workorder::findOrFail($id);

    return response()->json([
        'html' => view('page.workorder.detail', compact('wo'))->render()
    ]);
}

public function exportPdf($id)
{
    $wo = Workorder::findOrFail($id);

    $pdf = Pdf::loadView('page.workorder.exportpdf', compact('wo'))
        ->setPaper('A4', 'portrait');

    return $pdf->stream('workorder-'.$wo->kode_wo.'.pdf');
}

public function exportAllExcel()
{
    return Excel::download(new WorkorderExport, 'workorders_all.xlsx');
}


}
