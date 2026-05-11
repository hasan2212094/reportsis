<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projectmanagerunit extends Model
{
    use HasFactory;
    protected $table = 'project_manager_units';
    protected $fillable = [
        'project_manager_id',
        'unit_no',
        'persentase',
    ];
    public function project()
{
    return $this->belongsTo(ProjectManager::class);
}
}
