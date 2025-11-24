<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'visiting_time', 'speciality'];

    public function tests()
    {
        return $this->belongsToMany(Test::class, 'doctor_test');
    }

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }
}
