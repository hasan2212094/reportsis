<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RabRealisasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'rabpengajuan_id',
        'qty_realisasi',
        'tanggal_realisasi',
        'keterangan',
        'status_pengajuan' // 🔥 TAMBAHIN INI
    ];

    public function rabpengajuan()
    {
        return $this->belongsTo(Rabpengajuan::class, 'rabpengajuan_id');
    }

    // ==============================
    // 🔵 STATUS LABEL
    // ==============================
    public function getStatusPengajuanLabelAttribute()
    {
        return match ($this->status_pengajuan) {
            'pending'  => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            default    => '-'
        };
    }

    // ==============================
    // 🎨 STATUS COLOR
    // ==============================
    public function getStatusPengajuanColorAttribute()
    {
        return match ($this->status_pengajuan) {
            'pending'  => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            default    => 'secondary'
        };
    }
}
