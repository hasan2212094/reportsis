<?php

namespace App\Http\Controllers;

use App\Models\Rab;
use App\Models\DirectP;
use App\Exports\RabsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class RabController extends Controller
{
    /* ================= FORM RAB ================= */

    // GET /rab/form
    public function index()
    {
        $direct_p_s = DirectP::orderBy('item_id', 'ASC')->get();
        return view('page.RAB.form.index', compact('direct_p_s'));
    }

    // POST /rab/form
    public function store(Request $request)
{
    // LOG: request masuk
    Log::info('RAB Store Request Masuk', [
        'user_id' => auth()->id(),
        'payload' => $request->except(['file_keterangan', 'invoice_file']),
    ]);

    try {
        $validated = $request->validate([
            'tipe_pengajuan' => 'required|in:1,2',
            'is_ppn'         => 'required|in:0,1',
            'direct_p_s_id'  => 'required',
            'bank'           => 'required',
            'no_rek'         => 'required',
            'atas_nama'      => 'required',
        ]);

        Log::info('RAB Validasi Berhasil', $validated);

        $data = array_merge(
            $validated,
            $request->only([
                'no_wo',
                'no_pr',
                'nama_barang',
                'qty',
                'nama_toko',
                'keterangan_kebutuhan',
            ])
        );

        // upload file keterangan
        if ($request->hasFile('file_keterangan')) {
            $data['file_keterangan'] =
                $request->file('file_keterangan')->store('rab/keterangan', 'public');

            Log::info('Upload file_keterangan sukses', [
                'path' => $data['file_keterangan'],
            ]);
        }

        // upload invoice
        if ($request->hasFile('invoice_file')) {
            $data['invoice_file'] =
                $request->file('invoice_file')->store('rab/invoice', 'public');

            Log::info('Upload invoice_file sukses', [
                'path' => $data['invoice_file'],
            ]);
        }

        $data['created_by'] = auth()->id();
        $data['status'] = 'draft';

        $rab = Rab::create($data);

        Log::info('RAB berhasil disimpan', [
            'rab_id' => $rab->id,
            'data'   => $rab->toArray(),
        ]);

        return redirect()
            ->route('rab.list.index')
            ->with('success', 'Pengajuan RAB berhasil disimpan');

    } catch (\Throwable $e) {

        Log::error('RAB GAGAL disimpan', [
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
            'trace'   => $e->getTraceAsString(),
            'payload' => $request->all(),
        ]);

        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan saat menyimpan data.');
    }
}


    // GET /rab/form/{id}/edit
    public function edit($id)
    {
        $rab = Rab::findOrFail($id);
        return view('page.RAB.form.edit', compact('rab'));
    }

    // PUT /rab/form/{id}
    public function update(Request $request, $id)
    {
        $rab = Rab::findOrFail($id);

        $validated = $request->validate([
            'bank'      => 'required|string|max:50',
            'no_rek'    => 'required|string|max:50',
            'atas_nama' => 'required|string|max:100',
        ]);

        $data = $validated;

        if ($request->hasFile('file_keterangan')) {
            $data['file_keterangan'] = $request->file('file_keterangan')
                ->store('rab/keterangan', 'public');
        }

        if ($request->hasFile('invoice_file')) {
            $data['invoice_file'] = $request->file('invoice_file')
                ->store('rab/invoice', 'public');
        }

        $rab->update($data);

        return redirect()
            ->route('rab.list.index')
            ->with('success', 'RAB berhasil diupdate');
    }

    /* ================= LIST RAB ================= */

    // GET /rab/list
    public function listIndex()
    {
        $rabs = Rab::latest()->get();
        return view('page.RAB.list.index', compact('rabs'));
    }

    /* ================= SUMMARY ================= */

    // GET /rab/summary
    public function summary(Request $request)
    {
        $query = Rab::query();

        if ($request->no_wo) {
            $query->where('no_wo', 'like', "%{$request->no_wo}%");
        }

        if ($request->nama_barang) {
            $query->where('nama_barang', 'like', "%{$request->nama_barang}%");
        }

        if ($request->tgl_pengajuan_from && $request->tgl_pengajuan_to) {
            $query->whereBetween('created_at', [
                $request->tgl_pengajuan_from,
                $request->tgl_pengajuan_to
            ]);
        }

        if ($request->tgl_approved_from && $request->tgl_approved_to) {
            $query->whereBetween('approved_at', [
                $request->tgl_approved_from,
                $request->tgl_approved_to
            ]);
        }

        if ($request->status !== null) {
            $query->where('status', $request->status);
        }

        $rabs = $query->orderBy('created_at', 'asc')->get();

        if ($request->has('export')) {
            return Excel::download(new RabsExport($rabs), 'summary_rab.xlsx');
        }

        return view('page.RAB.summary.index', compact('rabs'));
    }

    /* ================= APPROVAL ================= */

    // GET /rab/approval
    public function approvalIndex()
    {
        // status 1 = submitted
        $rabs = Rab::where('status', 1)->get();
        return view('page.RAB.approval.index', compact('rabs'));
    }

    // GET /rab/approval/{rab}/payment
    public function paymentForm(Rab $rab)
    {
        return view('page.RAB.approval.payment', compact('rab'));
    }

    // POST /rab/approval/{rab}/payment
    public function storePayment(Request $request, Rab $rab)
    {
        $validated = $request->validate([
            'nama_pt' => 'required|string',
            'nominal' => 'required|numeric',
            'bukti_pembayaran' => 'required|file',
        ]);

        // TODO: upload bukti pembayaran

        $rab->update([
            'status' => 2, // approved
            'approved_at' => now(),
            'nama_pt' => $validated['nama_pt'],
            'nominal' => $validated['nominal'],
        ]);

        return redirect()
            ->route('rab.approval.index')
            ->with('success', 'RAB berhasil di-approve & dibayar');
    }

    // GET /rab/approval/{rab}/reject
    public function rejectForm(Rab $rab)
    {
        return view('page.RAB.approval.reject', compact('rab'));
    }

    // POST /rab/approval/{rab}/reject
    public function reject(Request $request, Rab $rab)
    {
        $validated = $request->validate([
            'reject_note' => 'required|string'
        ]);

        $rab->update([
            'status' => 3, // rejected
            'reject_note' => $validated['reject_note'],
        ]);

        return redirect()
            ->route('rab.approval.index')
            ->with('success', 'Pengajuan berhasil ditolak');
    }
}
