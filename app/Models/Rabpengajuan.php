<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rabpengajuan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rabpengajuans';
    const STATUS_PENDING  = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;
    const STATUS_PARTIAL  = 3;

    /**
     * ===============================
     * KONSTANTA TIPE PENGAJUAN
     * ===============================
     */
    const TIPE_DIRECT   = 'direct';
    const TIPE_INDIRECT = 'indirect';
    const TIPE_LUARRAB     = 'luarrab';

    /**
     * ===============================
     * MASS ASSIGNMENT
     * ===============================
     */
    protected $fillable = [
        'rab_id',
        'kode_rab',
        'user_id',
        'workorder_id',
        'tipe_pengajuan',
        'kebutuhan',
        'nama_barang',
        'qty',
        'total',
        'total_approved',
        'unit',
        'bank',
        'no_rek',
        'atas_nama',
        'nama_toko',
        'no_pr',
        'tanggal_pengajuan',
        'file_keterangan',
        'invoice_file',
        'tanggal_approved',
        'image_buktibayar',
        'nama_pt',
        'note_reject',
        'status_ppn',
        'status_pengajuan',
        'comment_approved',
        'is_imported',
        'nama_pt_partial',
        'keterangan_partial',
        'qty_partial',
        'total_partial',
    ];

    /**
     * ===============================
     * CASTING
     * ===============================
     */
    protected $casts = [
        'status_ppn' => 'boolean',
        'status_pengajuan' => 'integer',
        'tanggal_pengajuan' => 'date',
        'tanggal_approved' => 'date',
    ];

    /**
     * ===============================
     * RELATIONSHIP
     * ===============================
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workorder()
    {
        return $this->belongsTo(Workorder::class);
    }
    public function realisasi()
{
    return $this->hasMany(RabRealisasi::class,'rabpengajuan_id');
}
public function getTotalRealisasiAttribute()
{
    return $this->realisasi->sum('qty_realisasi');
}
public function getSisaQtyAttribute()
{
    return $this->qty - $this->total_realisasi;
}
public function getStatusRealisasiAttribute()
{
    if ($this->total_realisasi == 0)
        return 'Belum';

    if ($this->total_realisasi < $this->qty)
        return 'Partial';

    return 'Full';
}

    /**
     * ===============================
     * ACCESSOR (LABEL)
     * ===============================
     */

    // Label Status Pengajuan
    public function getStatusPengajuanLabelAttribute()
    {
        return match ($this->status_pengajuan) {
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_PARTIAL  => 'Partial',
            default => 'Pending',
        };
    }

    // Warna Badge Status Pengajuan (Bootstrap / Tailwind)
    public function getStatusPengajuanColorAttribute()
    {
        return match ($this->status_pengajuan) {
            self::STATUS_APPROVED => 'success', // green
            self::STATUS_REJECTED => 'danger',  // red
            self::STATUS_PARTIAL  => 'info', // yellow
            default => 'warning',               // yellow
        };
    }

    // Label PPN
    public function getStatusPpnLabelAttribute()
    {
        return $this->status_ppn ? 'PPN' : 'Non PPN';
    }

    // Label Tipe Pengajuan
    public function getTipePengajuanLabelAttribute()
    {
        return ucfirst($this->tipe_pengajuan);
    }

    /**
     * ===============================
     * HELPER METHOD
     * ===============================
     */

    public function isPending()
    {
        return $this->status_pengajuan === self::STATUS_PENDING;
    }

    public function isApproved()
    {
        return $this->status_pengajuan === self::STATUS_APPROVED;
    }

    public function isRejected()
    {
        return $this->status_pengajuan === self::STATUS_REJECTED;
    }

    public function isDirect()
    {
        return $this->tipe_pengajuan === self::TIPE_DIRECT;
    }

    public function isIndirect()
    {
        return $this->tipe_pengajuan === self::TIPE_INDIRECT;
    }
}
