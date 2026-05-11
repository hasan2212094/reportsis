<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectManagerTask extends Model
{
    use HasFactory;

    protected $fillable = [

        'project_manager_id',
        'task_name',
        'pic',
        'activity_detail',
        'bl_start',
        'bl_finish',
        'act_start',
        'act_finish',
        'duration',
        'priority',
        'percentage'

    ];

    public function project()
    {
        return $this->belongsTo(ProjectManager::class);
    }
}
