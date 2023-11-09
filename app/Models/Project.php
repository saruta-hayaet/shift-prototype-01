<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'retail_price', 'driver_price'];

    public function payments()
    {
        return $this->hasMany(ProjectEmployeePayment::class);
    }

    // public function shifts()
    // {
    //     return $this->hasMany(Shift::class);
    // }
    public function shifts()
    {
        return $this->belongsToMany(Shift::class, 'shift_projects');
    }

}
