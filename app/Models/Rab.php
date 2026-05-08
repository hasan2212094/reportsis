<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rab extends Model
{
    use HasFactory;

    protected $table = 'rabs';

    /**
     * Kolom yang boleh diisi (mass assignment)
     */
    protected $fillable = [
        'direct_p_s_id',
        'no_wo',
        'keterangan_kebutuhan',
        'file_keterangan',
        'nama_barang',
        'qty',
        'bank',
        'atas_nama',
        'no_rek',
        'nama_toko',
        'no_pr',
        'item_id',
        'invoice_file',
        'tipe_pengajuan',
        'status',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    /**
     * Default value
     */
    protected $attributes = [
        'status' => 'draft',
    ];

    /**
     * Casting tipe data
     */
    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function direct_ps()
    {
    return $this->belongsTo(DirectP::class, 'direct_p_s_id','id');
    }

    // ================= RELATION (NANTI DIPAKAI) =================

    // public function item()
    // {
    //     return $this->belongsTo(Item::class, 'item_id');
    // }

    // public function creator()
    // {
    //     return $this->belongsTo(User::class, 'created_by');
    // }

    // public function approver()
    // {
    //     return $this->belongsTo(User::class, 'approved_by');
    // }
}
