<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ServiceController;

// Redirect to dashboard 
Route::redirect('/', '/dashboard')->name('home');

// authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', function () {
        return view('profile.edit');
    })->name('profile.edit');

    // Dashboard view
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    
Route::prefix('admin')->as('admin.')->group(function () {

    // Bills actions
    Route::get('patients/{patient}/print', [BillController::class, 'printPatient'])
    ->name('patients.print');


    Route::delete('bills/{bill}', [BillController::class, 'destroy'])->name('bills.destroy');
    Route::resource('bills', BillController::class)->names('bills');

    // Patients
    Route::resource('patients', PatientController::class)->names('patients');

    // Extra routes
    Route::post('tests/import', [TestController::class, 'import'])->name('tests.import');
    Route::resource('tests', TestController::class)->names('tests');

    Route::get('patients/{patient}/bills', [BillController::class, 'patientBills'])->name('patients.bills');

    Route::resource('doctors', DoctorController::class)->names('doctors');
    Route::post('doctors/import', [DoctorController::class, 'import'])->name('doctors.import');
    // Services CRUD
    Route::resource('services', ServiceController::class)->names('services');

// Optional: import CSV/XLS
    Route::post('services/import', [ServiceController::class, 'import'])->name('services.import');
    Route::get('/patients/{patient}/payment', [PatientController::class, 'payment'])
        ->name('patients.payment');

    Route::put('/patients/{patient}/payment', [PatientController::class, 'updatePayment'])
        ->name('patients.payment.update');

});




    // Profile management 
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

// Auth 
require __DIR__.'/auth.php';
