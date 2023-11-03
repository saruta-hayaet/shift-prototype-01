<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id','am_vehicle_id','pm_vehicle_id','date'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function shiftProjects()
    {
        return $this->hasMany(ShiftProject::class);
    }

    public function am_vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'am_vehicle_id');
    }

    public function pm_vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'pm_vehicle_id');
    }
}
