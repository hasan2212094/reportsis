<?php

namespace App\Models;

use App\Models\Directa;
use App\Models\DirectP;
use App\Models\Luarrab;
use App\Models\Ppn;
use App\Models\Ppna;
use App\Models\Rabpengajuan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workorder extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable =
    ['kode_wo',
    'customer_name',
    'address',
    'phone_fax',
    'contact_person',
    'customer_po_no',
    'customer_po_date',
    'quantity',
    'total',
    'wo_date',
    'nama_produk',
    'type_unit',
    'pekerjaan_selesai',
    'pekerjaan_termasuk',
    'pekerjaan_tidak_termasuk',
    'garansi',
    'end_user'];
      public function directPs()
    {
        return $this->hasMany(DirectP::class, 'workorder_id');
    }
     public function ppns()
    {
        return $this->hasMany(Ppn::class, 'workorder_id');
    }
    public function directas ()
    {
        return $this->hasMany(Directa::class, 'workorder_id');
    }
     public function ppnas ()
    {
        return $this->hasMany(Ppna::class, 'workorder_id');
    }
    public function luarrab ()
    {
        return $this->hasMany(Luarrab::class, 'workorder_id');
    }
    public function Rabpengajuan ()
    {
        return $this->hasMany(Rabpengajuan::class, 'workorder_id');
    }

}
