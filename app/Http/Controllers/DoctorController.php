<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Imports\DoctorsImport;
use Maatwebsite\Excel\Facades\Excel;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $doctors = Doctor::when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('speciality', 'like', "%{$search}%")
                  ->orWhereHas('tests', function($q) use ($search) {
                      $q->where('test_name', 'like', "%{$search}%");
                  });
        })->latest()->get();

        return view('doctors.index', compact('doctors'));
    }


    public function import(Request $request)
   {
        $request->validate([
                    'file' => 'required|file|mimes:xlsx,csv,xls',
        ]);

       Excel::import(new DoctorsImport, $request->file('file'));

    return redirect()->route('admin.doctors.index')
                     ->with('success', 'Doctors imported successfully!');
   }


    
}
