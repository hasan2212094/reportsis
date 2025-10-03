<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indirecta extends Model
{
    use HasFactory;
    protected $fillable=[
        'indirectps_id',
        'Qty',
        'Unit',
        'Date_actual',
        'Toko',
        'Transaksi',
        'Total',
    ];
    public function indirectp()
{
    return $this->belongsTo(Indirectp::class, 'indirectps_id');
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

}
