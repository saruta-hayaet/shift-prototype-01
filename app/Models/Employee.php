<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function shifts()
    {
        return $this->hasMany(Shift::class);
    }

    public function payments()
    {
        return $this->hasMany(ProjectEmployeePayment::class);
    }
}