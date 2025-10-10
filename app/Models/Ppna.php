<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ppna extends Model
{
    use HasFactory,SoftDeletes;
     const DELETED_AT = 'deleted_at';
     protected $fillable=[
        'ppns_id',
        'Qty',
        'Unit',
        'Date_actual',
        'Toko',
        'Transaksi',
        'Total',
    ];
    public function ppn()
{
    return $this->belongsTo(Ppn::class, 'ppns_id');
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
