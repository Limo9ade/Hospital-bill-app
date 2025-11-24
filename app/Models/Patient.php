<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'phone', 'age'];



    public function bills()
    {
        return $this->hasMany(Bill::class);
    }
    
    public function tests()
{
    return $this->belongsToMany(Test::class, 'patient_test'); 
}

public function doctor() {
    return $this->belongsTo(Doctor::class);
}
}
