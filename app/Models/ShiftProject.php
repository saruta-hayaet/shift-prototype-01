<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftProject extends Model
{
    use HasFactory;

    protected $table = 'shift_projects';

    protected $fillable = [
        'shift_id',
        'project_id',
        'time_of_day',
    ];

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

}
