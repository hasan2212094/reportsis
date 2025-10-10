<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Directa extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable=[
        'direct_p_s_id',
        'workorder_id',
        'Qty',
        'Unit',
        'Date_actual',
        'Toko',
        'Transaksi',
        'Total',
    ];
   public function direct_ps()
{
    return $this->belongsTo(DirectP::class, 'direct_p_s_id','id');
}

    public function getTransaksiBadgeAttribute()
{
    switch ($this->Transaksi) {
        case 0:
            return '<span class="badge bg-success">Cash</span>';
        case 1:
            return '<span class="badge bg-warning">Transfer</span>';
        default:
            return '<span class="badge bg-secondary">Lainnya</span>';
    }
}
public function workorder()
    {
        return $this->belongsTo(Workorder::class, 'workorder_id');
    }
     
}
