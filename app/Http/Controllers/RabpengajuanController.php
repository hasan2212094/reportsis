<?php

namespace App\Http\Controllers;

use App\Models\Ppn;
use App\Models\DirectP;
use App\Models\Indirectp;
use App\Models\Workorder;
use App\Exports\RabsExport;
use App\Models\Rabpengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use App\Services\KodeRabService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class RabpengajuanController extends Controller
{
    public function index(Request $request)
{
    $workorders = Workorder::orderBy('kode_wo')->get();

    $pengajuannomors = RabPengajuan::withTrashed()
        ->whereNotNull('rab_id')
        ->distinct()
        ->orderBy('rab_id')
        ->pluck('rab_id');

    // ✅ JANGAN pakai get() di sini
    $query = RabPengajuan::with(['user', 'workorder'])
        ->orderBy('created_at', 'desc');

    // Filter tipe
    if ($request->filled('tipe_pengajuan')) {
        $query->where('tipe_pengajuan', $request->tipe_pengajuan);
    }

    // Filter status
    if ($request->filled('status_pengajuan')) {
        $query->where('status_pengajuan', $request->status_pengajuan);
    }

    // Filter workorder
    if ($request->filled('workorder')) {
        $query->where('workorder_id', $request->workorder);
    }

    // Search
    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('nama_barang', 'like', '%' . $request->search . '%')
              ->orWhere('kebutuhan', 'like', '%' . $request->search . '%')
              ->orWhere('no_pr', 'like', '%' . $request->search . '%');
        });
    }

    // ✅ PAGINATE DI AKHIR
    $rabpengajuans = $query->paginate(10)->withQueryString();

    return view(
        'page.RAB.form.index',
        compact('rabpengajuans', 'workorders', 'pengajuannomors')
    );
}


 public function store(Request $request, KodeRabService $kodeRabService)
{
    $user = auth()->user();

    if (!$user) {
        return redirect()->route('login');
    }

    /**
     * ===============================
     * BATAS JAM PENGAJUAN
     * ===============================
     */
    if (
        $user->role === 'user' &&
        now()->greaterThan(now()->setTime(17, 0))
    ) {
        return back()
            ->withInput()
            ->withErrors([
                'waktu' => 'Pengajuan RAB hanya dapat dilakukan sampai jam 17:00'
            ]);
    }

    /**
     * ===============================
     * NORMALISASI INPUT
     * ===============================
     */
    $request->merge([
        'status_ppn' => (int) $request->input('status_ppn', 0),
        'tipe_pengajuan' => strtolower(trim($request->tipe_pengajuan)),
    ]);

    /**
     * ===============================
     * VALIDATION RULE
     * ===============================
     */
    $validated = $request->validate([

        'tipe_pengajuan' => [
            'required',
            Rule::in([
                Rabpengajuan::TIPE_DIRECT,
                Rabpengajuan::TIPE_INDIRECT,
                Rabpengajuan::TIPE_LUARRAB
            ])
        ],

        'workorder_id' => [
            'nullable',
            'exists:workorders,id',
            Rule::requiredIf(
                $request->tipe_pengajuan === Rabpengajuan::TIPE_DIRECT
            ),
            Rule::prohibitedIf(
                $request->tipe_pengajuan === Rabpengajuan::TIPE_INDIRECT
            ),
        ],

        'kebutuhan'       => 'required|string',
        'nama_barang'     => 'required|string',
        'qty'             => 'required|string',
        'total'           => 'required|numeric|min:0',
        'unit'            => 'required|string',
        'bank'            => 'required|string',
        'no_rek'          => 'required|string',
        'atas_nama'       => 'required|string',
        'nama_toko'       => 'required|string',
        'no_pr'           => 'nullable|string',
        'file_keterangan' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10000',
        'invoice_file'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10000',
        'status_ppn'      => 'required|boolean',
    ]);

    /**
     * ===============================
     * SIMPAN DATA (TRANSACTION SAFE)
     * ===============================
     */
    DB::transaction(function () use ($validated, $request, $user, $kodeRabService) {

        $data = $validated;

        // Generate kode RAB otomatis (anti bentrok)
        $data['kode_rab'] = $kodeRabService->generate();

        $data['user_id']           = $user->id;
        $data['tanggal_pengajuan'] = today();
        $data['status_pengajuan']  = Rabpengajuan::STATUS_PENDING;

        /**
         * FORCE NULL kalau INDIRECT
         */
        if ($data['tipe_pengajuan'] === Rabpengajuan::TIPE_INDIRECT) {
            $data['workorder_id'] = null;
        }

        /**
         * Upload File
         */
        if ($request->hasFile('file_keterangan')) {
            $data['file_keterangan'] = $request->file('file_keterangan')
                ->store('rab/keterangan', 'public');
        }

        if ($request->hasFile('invoice_file')) {
            $data['invoice_file'] = $request->file('invoice_file')
                ->store('rab/invoice', 'public');
        }

        Rabpengajuan::create($data);
    });

    return back()->with('success', 'Pengajuan RAB berhasil disimpan');
}
// EDIT
public function edit($id)
{
    $rab = RabPengajuan::findOrFail($id);
    $workorders = Workorder::orderBy('kode_wo')->get();
    return view('page.RAB.form.edit', compact('rab','workorders'));
}

// UPDATE
public function update(Request $request, $id)
{
    $rab = RabPengajuan::findOrFail($id);

    $validated = $request->validate([
        'tipe_pengajuan'  => 'required|in:direct,indirect,luarrab',
        'workorder_id'    => 'nullable|required_if:tipe_pengajuan,direct|exists:workorders,id',
        'kebutuhan'       => 'required|string',
        'nama_barang'     => 'required|string',
        'qty'             => 'required|string',
        'total'           => 'required|numeric|min:0',
        'unit'            => 'required|string',
        'bank'            => 'required|string',
        'no_rek'          => 'required|string',
        'atas_nama'       => 'required|string',
        'nama_toko'       => 'required|string',
        'no_pr'           => 'nullable|string',
        'file_keterangan' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5000',
        'invoice_file'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5000',
        'status_ppn'      => 'required|boolean',
    ]);

    if ($validated['tipe_pengajuan'] === 'indirect') {
        $validated['workorder_id'] = null;
    }

    if ($request->hasFile('file_keterangan')) {
        $validated['file_keterangan'] =
            $request->file('file_keterangan')->store('rab/keterangan', 'public');
    }

    if ($request->hasFile('invoice_file')) {
        $validated['invoice_file'] =
            $request->file('invoice_file')->store('rab/invoice', 'public');
    }

    $rab->update($validated);

    return redirect()->route('page.rab.index')->with('success', 'RAB berhasil diupdate');
}
public function destroy($id)
{
    $rab = Rabpengajuan::findOrFail($id);
    $rab->delete(); // Ini tidak akan benar-benar menghapus, hanya menandai deleted_at

    return back()->with('success', 'RAB berhasil dihapus');
}

    /**
     * APPROVE PENGAJUAN
     */
  public function indexApproval(Request $request)
{
    $query = Rabpengajuan::with(['user', 'workorder'])
        ->whereIn('status_pengajuan', [
            Rabpengajuan::STATUS_PENDING,
            Rabpengajuan::STATUS_PARTIAL
        ])
        ->orderBy('created_at','desc');

    if ($request->filled('tipe_pengajuan')) {
        $query->where('tipe_pengajuan', $request->tipe_pengajuan);
    }

    if ($request->filled('search')) {
        $query->where(function($q) use ($request){
            $q->where('nama_barang','like','%'.$request->search.'%')
              ->orWhere('kebutuhan','like','%'.$request->search.'%')
              ->orWhere('no_pr','like','%'.$request->search.'%');
        });
    }

    if ($request->filled('status_ppn')) {
        $query->where('status_ppn', $request->status_ppn == 'ppn' ? 1 : 0);
    }

    $rabpengajuans = $query->paginate(10)->withQueryString();
  
    return view('page.RAB.approval.index', compact('rabpengajuans'));
}
public function indexpayment($id)
{
    $rab = Rabpengajuan::with(['user', 'workorder'])->findOrFail($id);

    return view('page.RAB.approval.payment', compact('rab'));
}
public function indexreject()
{
    $rabpengajuans = Rabpengajuan::with(['user', 'workorder'])
        ->where('status_pengajuan', Rabpengajuan::STATUS_PENDING)
        ->orderBy('created_at', 'desc')
        ->get();
        //  dd($rabpengajuans->first());

    return view('page.RAB.approval.reject', compact('rabpengajuans'));
}
public function indexPartial($id)
{
    $rab = Rabpengajuan::with(['user','workorder'])->findOrFail($id);
    return view('page.RAB.approval.partial', compact('rab'));
}
// public function approve(Request $request, $id)
// {
//     try {
//         $request->validate([
//             'nama_pt'          => 'required|string',
//             'total_approved'   => 'required|numeric|min:0',
//             'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10000',
//         ]);

//         $rab = Rabpengajuan::findOrFail($id);

//         // =====================
//         // UPLOAD BUKTI BAYAR
//         // =====================
//         if ($request->hasFile('bukti_pembayaran')) {
//             $file = $request->file('bukti_pembayaran');
//             $filename = time().'_'.$file->getClientOriginalName();
//             $path = $file->storeAs('bukti_pembayaran', $filename, 'public');
//         }

//         // =====================
//         // UPDATE DATA
//         // =====================
//         $rab->update([
//             'nama_pt'          => $request->nama_pt,
//             'total_approved'   => $request->total_approved,
//             'image_buktibayar' => $path ?? null, // ⬅️ sesuai model
//             'comment_approved' => $request->comment_approved,
//             'status_pengajuan' => Rabpengajuan::STATUS_APPROVED,
//             'tanggal_approved' => Carbon::now(), // ⬅️ tanggal manual
//         ]);

//         return redirect()
//             ->route('page.RAB.approval.index')
//             ->with('success', 'Pembayaran RAB berhasil diproses ✅');

//     } catch (\Exception $e) {
//         return redirect()
//             ->route('page.RAB.approval.index')
//             ->with('error', 'Pembayaran gagal ❌ : '.$e->getMessage());
//     }
// }
public function approve(Request $request, $id)
{
    try {

        $request->validate([
            'nama_pt'            => 'required|string',
            'total_approved'     => 'required|numeric|min:0',
            'bukti_pembayaran'   => 'required|array',
            'bukti_pembayaran.*' => 'file|mimes:jpg,jpeg,png,pdf|max:10000',
            'comment_approved'   => 'nullable|string',
        ]);

        $rab = Rabpengajuan::findOrFail($id);

        // =====================
        // AMBIL FILE LAMA
        // =====================
        $existingFiles = $rab->image_buktibayar ?? [];

        if (!is_array($existingFiles)) {
            $existingFiles = json_decode($existingFiles, true) ?? [];
        }

        // =====================
        // UPLOAD FILE BARU
        // =====================
        $newFiles = [];

        if ($request->hasFile('bukti_pembayaran')) {

            foreach ($request->file('bukti_pembayaran') as $file) {

                $filename = time().'_'.$file->getClientOriginalName();
                $path = $file->storeAs('bukti_pembayaran', $filename, 'public');

                $newFiles[] = [
                    'tipe' => 'approved',
                    'file' => $path,
                    'tanggal' => now()->toDateTimeString(),
                ];
            }
        }

        // =====================
        // MERGE FILE
        // =====================
        $allFiles = array_merge($existingFiles, $newFiles);

        // =====================
        // UPDATE DATA
        // =====================
        $rab->update([
            'nama_pt'          => $request->nama_pt,
            'total_approved'   => $request->total_approved,
            'image_buktibayar' => $allFiles,
            'comment_approved' => $request->comment_approved,
            'status_pengajuan' => Rabpengajuan::STATUS_APPROVED,
            'tanggal_approved' => now(),
        ]);

        return redirect()
            ->route('page.RAB.approval.index')
            ->with('success', 'Pembayaran RAB berhasil diproses ✅');

    } catch (\Exception $e) {

        return redirect()
            ->route('page.RAB.approval.index')
            ->with('error', 'Pembayaran gagal ❌ : '.$e->getMessage());
    }
}
public function partial(Request $request, $id)
{
    try {

        $request->validate([
            'nama_pt_partial'            => 'required|string',
            'qty_partial'                => 'required|numeric|min:1',
            'keterangan_partial'         => 'nullable|string',
            'total_partial'              => 'required|numeric|min:0',
            'bukti_pembayaran_partial'   => 'required|array',
            'bukti_pembayaran_partial.*' => 'file|mimes:jpg,jpeg,png,pdf|max:10000',
        ]);

        $rab = Rabpengajuan::findOrFail($id);

        // =====================
        // AMBIL FILE LAMA
        // =====================
        $existingFiles = $rab->image_buktibayar ?? [];

        if (!is_array($existingFiles)) {
            $existingFiles = json_decode($existingFiles, true) ?? [];
        }

        // =====================
        // UPLOAD FILE BARU
        // =====================
        $newFiles = [];

        if ($request->hasFile('bukti_pembayaran_partial')) {

            foreach ($request->file('bukti_pembayaran_partial') as $file) {

                $filename = time().'_'.$file->getClientOriginalName();
                $path = $file->storeAs('bukti_pembayaran', $filename, 'public');

                $newFiles[] = [
                    'tipe' => 'partial',
                    'file' => $path,
                    'tanggal' => now()->toDateTimeString(),
                    'qty' => $request->qty_partial,
                    'total' => $request->total_partial,
                ];
            }
        }

        // =====================
        // MERGE FILE
        // =====================
        $allFiles = array_merge($existingFiles, $newFiles);

        // =====================
        // UPDATE DATA
        // =====================
        $rab->update([
            'nama_pt_partial'    => $request->nama_pt_partial,
            'qty_partial'        => $request->qty_partial,
            'total_partial'      => $request->total_partial,
            'keterangan_partial' => $request->keterangan_partial,
            'image_buktibayar'   => $allFiles,
            'status_pengajuan'   => Rabpengajuan::STATUS_PARTIAL,
        ]);

        return redirect()
            ->route('page.RAB.approval.index')
            ->with('success', 'Pembayaran Partial berhasil diproses ✅');

    } catch (\Exception $e) {

        return redirect()
            ->route('page.RAB.approval.index')
            ->with('error', 'Partial gagal ❌ : '.$e->getMessage());
    }
}
    /**
     * REJECT PENGAJUAN
     */
    public function reject(Request $request, $id)
    {
     try {
        $request->validate([
           'note_reject' => 'required|string|max:255',
        ]);

        $rabpengajuan = Rabpengajuan::findOrFail($id);
        // =====================
        // UPDATE DATA
        // =====================
        $rabpengajuan->update([
            'status_pengajuan'  => Rabpengajuan::STATUS_REJECTED,
            'note_reject'       => $request->note_reject,
            'tanggal_approved' => null,
            'image_buktibayar'  => null,
            'nama_pt'           => null,
        ]);

        return redirect()
            ->route('page.RAB.approval.index')
            ->with('success', 'Pembayaran RAB berhasil diproses ❌');

    } catch (\Exception $e) {
        return redirect()
            ->route('page.RAB.approval.index')
            ->with('error', 'Pembayaran gagal ❌ : '.$e->getMessage());
    }
}
  public function summary(Request $request)
{
    $workorders = Workorder::orderBy('kode_wo')->get();
    $query = Rabpengajuan::with(['user', 'workorder']);

    // 🔍 Filter Workorder
    if ($request->filled('workorder')) {
        $query->whereHas('workorder', function ($q) use ($request) {
            $q->where('kode_wo', 'like', '%' . $request->workorder . '%');
        });
    }

    // 🔍 Filter Nama Barang
    if ($request->filled('nama_barang')) {
        $query->where('nama_barang', $request->nama_barang);
    }

    // 📅 Filter Tanggal (dynamic: pengajuan / approved)
    if ($request->filled('tanggal_from') && $request->filled('tanggal_to')) {

        if ($request->tanggal_type === 'approved') {
            $query->whereBetween('tanggal_approved', [
                $request->tanggal_from,
                $request->tanggal_to
            ]);
        } else {
            // default: tanggal pengajuan
            $query->whereBetween('tanggal_pengajuan', [
                $request->tanggal_from,
                $request->tanggal_to
            ]);
        }
    }

    // 🚦 Filter Status (mapping string → konstanta)
    if ($request->filled('status')) {
        $statusMap = [
            'pending'  => Rabpengajuan::STATUS_PENDING,
            'approved' => Rabpengajuan::STATUS_APPROVED,
            'rejected' => Rabpengajuan::STATUS_REJECTED,
            'partial'  => Rabpengajuan::STATUS_PARTIAL
        ];

        if (isset($statusMap[$request->status])) {
            $query->where('status_pengajuan', $statusMap[$request->status]);
        }
    }

    // 🧾 Filter PPN
    if ($request->filled('status_ppn')) {
        $query->where('status_ppn', $request->status_ppn);
    }

    $query->orderBy('tanggal_pengajuan', 'desc');

// 🧾 EXPORT (pakai get)
if ($request->has('export')) {
    return Excel::download(
        new RabsExport($query->get()),
        'summary_rab.xlsx'
    );
}

// 📄 PAGINATION (pakai paginate)
$rabpengajuans = $query
    ->paginate(10)
    ->appends($request->query());

return view('page.RAB.summary.index', compact(
    'rabpengajuans',
    'workorders'
));

} public function import(Request $request)
{
    $request->validate([
        'selected_ids' => 'required|array|min:1'
    ]);

    DB::transaction(function () use ($request) {

        $rabPengajuans = RabPengajuan::whereIn('id', $request->selected_ids)->get();

        foreach ($rabPengajuans as $rab) {

            switch ($rab->tipe_pengajuan) {

                // ================= DIRECT =================
                case 'direct':
                    DirectP::create([
                        'item_id'        => $rab->rab_id ?? $rab->id,
                        'Item'           => $rab->nama_barang,
                        'Qty'            => $rab->qty,
                        'Unit'           => $rab->unit,
                        'Needed_by'      => $rab->tanggal_pengajuan,
                        'workorder_id'   => $rab->workorder_id,
                        'Date_pengajuan' => $rab->tanggal_pengajuan,
                        'Total'          => $rab->total ?? 0,
                        'Notes'          => $rab->kebutuhan,
                    ]);
                    break;

                // ================= INDIRECT =================
                case 'indirect':
                    Indirectp::create([
                        'item_id'        => $rab->rab_id ?? $rab->id,
                        'Item'           => $rab->nama_barang,
                        'Qty'            => $rab->qty,
                        'Unit'           => $rab->unit,
                        'Needed_by'      => $rab->tanggal_pengajuan,
                        'Date_pengajuan' => $rab->tanggal_pengajuan,
                       'Total'          => $rab->total ?? 0,
                        'Notes'          => $rab->kebutuhan,
                    ]);
                    break;

                // ================= LUAR RAB =================
                case 'luarrab':
                    Ppn::create([
                        'item_id'        => $rab->rab_id ?? $rab->id,
                        'Item'           => $rab->nama_barang,
                        'Qty'            => $rab->qty,
                        'Unit'           => $rab->unit,
                        'Needed_by'      => $rab->tanggal_pengajuan,
                        'workorder_id'   => $rab->workorder_id,
                        'Date_pengajuan' => $rab->tanggal_pengajuan,
                        'Total'          => $rab->total ?? 0,
                        'Notes'          => 'LUAR RAB | ' . $rab->kebutuhan,
                    ]);
                    break;
            }

            // ===== UPDATE STATUS RAB =====
         RabPengajuan::where('id', $rab->id)->update([
    'is_imported' => 1,
]);
        }
    });

    return back()->with('success', 'Data berhasil diimport sesuai tipe pengajuan');
}

public function create()
{
    $user = auth()->user();

    $isLimitedPeriod = false;

    if ($user && $user->role === 'user') {
        $now = Carbon::now(); // pastikan timezone Laravel sudah di config/app.php (Asia/Jakarta)

        $isLimitedPeriod = (
            ($now->isThursday() && $now->gt(Carbon::parse('17:00'))) ||
            $now->isFriday() ||
            $now->isSaturday() ||
            $now->isSunday() ||
            ($now->isMonday() && $now->lt(Carbon::parse('08:00')))
        );
    }

    $workorders = Workorder::all(); // contoh data dropdown
    $rabpengajuans = RabPengajuan::paginate(10); // contoh pagination

    return view('page.RAB.form.index', compact('isLimitedPeriod', 'workorders', 'rabpengajuans'));
}



}
