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

    // default ambil semua data
    $queryDirectP   = Directp::query();
    $queryIndirectP = Indirectp::query();
    $queryPpn       = Ppn::query();

    $queryDirectA   = Directa::query();
    $queryIndirectA = Indirecta::query();
    $queryPpna      = Ppna::query();
    $queryLuarRab   = Luarrab::query();

    // filter bila ada tanggal
    if ($start && $end) {
        $queryDirectP->whereBetween('Date_pengajuan', [$start, $end]);
        $queryIndirectP->whereBetween('Date_pengajuan', [$start, $end]);
        $queryPpn->whereBetween('Date_pengajuan', [$start, $end]);

        $queryDirectA->whereBetween('Date_actual', [$start, $end]);
        $queryIndirectA->whereBetween('Date_actual', [$start, $end]);
        $queryPpna->whereBetween('Date_actual', [$start, $end]);
        $queryLuarRab->whereBetween('Date_actual', [$start, $end]);
    }

    // total pengajuan
    $totalDirectP   = $queryDirectP->get()->sum(fn($item) => (int) filter_var($item->Total, FILTER_SANITIZE_NUMBER_INT));
    $totalIndirectp = $queryIndirectP->get()->sum(fn($item) => (int) filter_var($item->Total, FILTER_SANITIZE_NUMBER_INT));
    $totalPpn       = $queryPpn->get()->sum(fn($item) => (int) filter_var($item->Total, FILTER_SANITIZE_NUMBER_INT));
    // total actual
    $totalDirecta   = $queryDirectA->get()->sum(fn($item) => (int) filter_var($item->Total, FILTER_SANITIZE_NUMBER_INT));
    $totalIndirecta = $queryIndirectA->get()->sum(fn($item) => (int) filter_var($item->Total, FILTER_SANITIZE_NUMBER_INT));
    $totalPpna      = $queryPpna->get()->sum(fn($item) => (int) filter_var($item->Total, FILTER_SANITIZE_NUMBER_INT));
    $totalluarrab   = $queryLuarRab->get()->sum(fn($item) => (int) filter_var($item->Total, FILTER_SANITIZE_NUMBER_INT));
    
    $total  = $totalDirectP + $totalIndirectp + $totalPpn;
    $total2 = $totalDirecta + $totalIndirecta + $totalPpna + $totalluarrab;

    return view('home.index', compact(
        'total', 'total2',
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
