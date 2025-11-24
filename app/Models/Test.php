<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Test extends Model
{
    use HasFactory;

    protected $fillable = ['test_name', 'price'];

    public function bills()
    {
        return $this->belongsToMany(Bill::class);
    }
    
    public function patients()
{
    return $this->belongsToMany(Patient::class);
}

}
