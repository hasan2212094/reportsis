<?php

namespace App\Http\Controllers;

use App\Models\Ppn;
use App\Models\Ppna;
use App\Models\Directa;
use App\Models\DirectP;
use App\Models\Luarrab;
use App\Models\Indirecta;
use App\Models\Indirectp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class dashboardController extends Controller
{
  public function index(Request $request)
{
    $start = $request->start_date;
    $end   = $request->end_date;

    // ✅ Buat daftar periode mingguan
    $firstDate = Directp::min('Date_pengajuan');
    $lastDate  = Directp::max('Date_pengajuan');

    if (!$firstDate || !$lastDate) {
        $firstDate = now()->startOfWeek();
        $lastDate  = now()->endOfWeek();
    }

    $periodeList = [];
    $current = \Carbon\Carbon::parse($firstDate)->startOfWeek();
    $endDate = \Carbon\Carbon::parse($lastDate)->endOfWeek();

    while ($current->lessThanOrEqualTo($endDate)) {
        $startWeek = $current->copy();
        $endWeek = $current->copy()->endOfWeek();
        $periodeList[] = [
            'label' => $startWeek->format('d M Y') . ' - ' . $endWeek->format('d M Y'),
            'start' => $startWeek->toDateString(),
            'end'   => $endWeek->toDateString(),
        ];
        $current->addWeek();
    }

    // ✅ Query dasar
    $queryDirectP   = Directp::query();
    $queryIndirectP = Indirectp::query();
    $queryPpn       = Ppn::query();
    $queryDirectA   = Directa::query();
    $queryIndirectA = Indirecta::query();
    $queryPpna      = Ppna::query();
    $queryLuarRab   = Luarrab::query();

    // ✅ Filter berdasarkan periode dipilih
    if ($start && $end) {
        $queryDirectP->whereBetween('Date_pengajuan', [$start, $end]);
        $queryIndirectP->whereBetween('Date_pengajuan', [$start, $end]);
        $queryPpn->whereBetween('Date_pengajuan', [$start, $end]);

        $queryDirectA->whereBetween('Date_actual', [$start, $end]);
        $queryIndirectA->whereBetween('Date_actual', [$start, $end]);
        $queryPpna->whereBetween('Date_actual', [$start, $end]);
        $queryLuarRab->whereBetween('Date_actual', [$start, $end]);
    }

    // ✅ Hitung total pengajuan
    $totalDirectP   = $queryDirectP->get()->sum(fn($i) => (int) filter_var($i->Total, FILTER_SANITIZE_NUMBER_INT));
    $totalIndirectp = $queryIndirectP->get()->sum(fn($i) => (int) filter_var($i->Total, FILTER_SANITIZE_NUMBER_INT));
    $totalPpn       = $queryPpn->get()->sum(fn($i) => (int) filter_var($i->Total, FILTER_SANITIZE_NUMBER_INT));

    // ✅ Hitung total actual
    $totalDirecta   = $queryDirectA->get()->sum(fn($i) => (int) filter_var($i->Total, FILTER_SANITIZE_NUMBER_INT));
    $totalIndirecta = $queryIndirectA->get()->sum(fn($i) => (int) filter_var($i->Total, FILTER_SANITIZE_NUMBER_INT));
    $totalPpna      = $queryPpna->get()->sum(fn($i) => (int) filter_var($i->Total, FILTER_SANITIZE_NUMBER_INT));
    $totalluarrab   = $queryLuarRab->get()->sum(fn($i) => (int) filter_var($i->Total, FILTER_SANITIZE_NUMBER_INT));

    // ✅ Total keseluruhan
    $totalPengajuan = $totalDirectP + $totalIndirectp + $totalPpn;
    $totalActual = $totalDirecta + $totalIndirecta + $totalPpna + $totalluarrab;

    // ✅ Hitung efisiensi (perbandingan)
    $efisiensi = 0;
    if ($totalPengajuan > 0) {
        $efisiensi = (($totalPengajuan - $totalActual) / $totalPengajuan) * 100;
    }

    return view('home.index', compact(
        'periodeList', 'start', 'end',
        'totalPengajuan', 'totalActual', 'efisiensi',
        'totalDirectP', 'totalIndirectp', 'totalPpn',
        'totalDirecta', 'totalIndirecta', 'totalPpna', 'totalluarrab'
    ));
}


   public function login2(){

      return view('Auth.login2');
   }
   public function register2(){

      return view('Auth.register2');
   }
}
