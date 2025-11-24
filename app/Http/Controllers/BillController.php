<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Patient;
use App\Models\Test;
use App\Models\Service;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BillController extends Controller
{
    // Create Bill Form
    public function create()
    {
        $patients = Patient::all();
        $tests = Test::all();
        $services = Service::all();
        $doctors = Doctor::all(); 

        return view('bills.create', compact('patients', 'tests', 'services', 'doctors'));
    }

    // Store Bill with Tests & Services
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'age' => 'required|integer|min:1',
            'tests' => 'nullable|array',
            'tests.*' => 'exists:tests,id',
            'services' => 'nullable|array',
            'services.*' => 'exists:services,id',
        ]);

        // Create a new patient
        $patient = Patient::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'age' => $request->age,
        ]);

        // Selected items
        $test_ids = $request->input('tests', []);
        $service_ids = $request->input('services', []);

        // Calculate total (tests + services)
        $test_total = Test::whereIn('id', $test_ids)->sum('price');
        $service_total = Service::whereIn('id', $service_ids)->sum('price');
        $total_amount = $test_total + $service_total;

        // Create bill
        $bill = Bill::create([
    'patient_id' => $patient->id,
    'doctor_id' => $request->doctor_id,
    'total_amount' => $total_amount,
]);

        // Attach relationships
        if (!empty($test_ids)) {
            $bill->tests()->attach($test_ids);
        }
        if (!empty($service_ids)) {
            $bill->services()->attach($service_ids);
        }

        return redirect()->route('admin.patients.bills', $patient->id)
            ->with('success', 'New patient and bill created successfully!');
    }

    // Redirect to bill creation
    public function index()
    {
        return redirect()->route('admin.bills.create');
    }

    // Show all bills for a patient
    public function patientBills(Patient $patient)
    {
        $bills = $patient->bills()->with(['tests', 'services'])->get();

        return view('patients.bills', compact('patient', 'bills'));
    }

    // Delete a bill
    public function destroy(Bill $bill)
    {
        $bill->delete();
        return back()->with('success', 'Bill deleted successfully!');
    }

    // Print patient PDF (Tests + Services)
    public function printPatient(Patient $patient)
    {
        // Load related tests & services
        $patient->load(['bills.tests', 'bills.services']);

        // Calculate total for all bills (tests + services)
        $totalAmount = 0;
        foreach ($patient->bills as $bill) {
            $testsTotal = $bill->tests->sum('price');
            $servicesTotal = $bill->services->sum('price');
            $totalAmount += $testsTotal + $servicesTotal;
        }

        // Pass to view
        return Pdf::loadView('bills.print_patient', [
            'patient' => $patient,
            'totalAmount' => $totalAmount,
        ])->stream('patient-' . $patient->id . '.pdf');
    }
}
