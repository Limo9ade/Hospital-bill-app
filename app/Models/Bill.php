<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id','doctor_id', 'total_amount'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function tests()
{
    return $this->belongsToMany(Test::class, 'bill_test');
}

public function services()
{
    return $this->belongsToMany(Service::class, 'bill_service');
}


public function doctor() {
    return $this->belongsTo(Doctor::class);
}


}
