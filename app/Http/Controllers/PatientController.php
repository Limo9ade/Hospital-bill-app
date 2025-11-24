<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Test;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Doctor;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    // Show all patients
    public function index(Request $request)
    {
        $query = Patient::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('phone')) {
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }
        if ($request->filled('age')) {
            $query->where('age', $request->age);
        }
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        $allowedSorts = ['name', 'phone', 'age', 'created_at'];
        if (!in_array($sort, $allowedSorts)) $sort = 'created_at';
        if (!in_array($direction, ['asc', 'desc'])) $direction = 'desc';

        $patients = $query->orderBy($sort, $direction)->paginate(10)->withQueryString();

        return view('patients.index', compact('patients'));
    }

    // Show create form
    public function create()
    {
        $tests = Test::all();
        $services = Service::all();
        $doctors = Doctor::all();
        return view('bills.create', compact('tests','services','doctors'));
    }

    // Store new patient + bill
    public function store(Request $request)
    {    
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'age' => 'required|integer|min:1',
            'doctor_id' => 'required|exists:doctors,id',
            'tests' => 'nullable|array',
            'tests.*' => 'exists:tests,id',
            'services' => 'nullable|array',
            'services.*' => 'exists:services,id',
        ]);

        $patient = Patient::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'age' => $request->age,
        ]);

        $test_ids = $request->input('tests', []);
        $service_ids = $request->input('services', []);

        $test_total = Test::whereIn('id', $test_ids)->sum('price');
        $service_total = Service::whereIn('id', $service_ids)->sum('price');

        $bill = Bill::create([
            'patient_id' => $patient->id,
            'doctor_id' => $request->doctor_id,
            'total_amount' => $test_total + $service_total,
        ]);

        $bill->tests()->attach($test_ids);
        $bill->services()->attach($service_ids);

        return redirect()->route('admin.patients.bills', $patient->id)
                         ->with('success', 'Patient and bill created successfully!');
    }

    // Edit patient
    public function edit(Patient $patient)
    {
        $tests = Test::all();
        $services = Service::all();
        $doctors = Doctor::all();

        $selectedTestIds = $patient->bills()
            ->with('tests')
            ->get()
            ->pluck('tests.*.id')
            ->flatten()
            ->toArray();

        $selectedServiceIds = $patient->bills()
            ->with('services')
            ->get()
            ->pluck('services.*.id')
            ->flatten()
            ->toArray();

        $selectedDoctorId = optional($patient->bills()->latest()->first())->doctor_id;

        return view('patients.edit', compact(
            'patient',
            'tests',
            'services',
            'doctors',
            'selectedTestIds',
            'selectedServiceIds',
            'selectedDoctorId'
        ));
    }

    // Update patient’s latest bill
    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'tests' => 'nullable|array',
            'tests.*' => 'exists:tests,id',
            'services' => 'nullable|array',
            'services.*' => 'exists:services,id',
        ]);

        $bill = $patient->bills()->latest()->first();

        if ($bill) {
            $test_ids = $request->input('tests', []);
            $service_ids = $request->input('services', []);

            $bill->doctor_id = $request->doctor_id;
            $bill->tests()->sync($test_ids);
            $bill->services()->sync($service_ids);
            $bill->total_amount = Test::whereIn('id', $test_ids)->sum('price') +
                                   Service::whereIn('id', $service_ids)->sum('price');
            $bill->save();
        }

        return redirect()->route('admin.patients.bills', $patient->id)
                         ->with('success', 'Patient bill updated successfully!');
    }

    // Show patient bills
    public function bills($patientId)
    {
        $patient = Patient::findOrFail($patientId);
        $bills = $patient->bills()->with('tests', 'services', 'doctor')->get();
        return view('patients.bills', compact('patient', 'bills'));
    }

    // Delete patient
    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('admin.patients.index')
                         ->with('success', 'Patient deleted successfully!');
    }

    // Payment
    public function payment(Patient $patient)
    {
        $patient->load('bills.tests', 'bills.services');
        return view('patients.payment', compact('patient'));
    }

    public function updatePayment(Request $request, Patient $patient)
    {
        $request->validate([
            'paid_amount' => 'required|numeric|min:0',
        ]);

        $patient->paid_amount = $request->paid_amount;
        $patient->save();

        return redirect()->route('admin.patients.bills', $patient->id)
                         ->with('success', 'Payment updated successfully!');
    }
}
