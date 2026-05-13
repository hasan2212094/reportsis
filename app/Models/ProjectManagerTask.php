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
        'percentage',
        'unit_1',
        'unit_2',
        'unit_3',
        'unit_4',
        'unit_5',
        'unit_6',
        'unit_7',
        'unit_8',
        'unit_9',
        'unit_10',
        'unit_11',
        'unit_12',
        'unit_13',
        'unit_14',
        'unit_15',
        'unit_16',
        'unit_17',
        'unit_18',
        'unit_19',
        'unit_20',

    ];

    public function project()
    {
        return $this->belongsTo(ProjectManager::class);
    }
}
