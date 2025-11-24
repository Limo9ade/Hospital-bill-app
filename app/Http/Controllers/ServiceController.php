<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Imports\ServicesImport;
use Maatwebsite\Excel\Facades\Excel;

class ServiceController extends Controller
{
    // List all services
    public function index(Request $request)
    {
        $query = Service::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $services = $query->orderBy('name')->get();

        return view('services.index', compact('services'));
    }

    // Show create form
    public function create()
    {
        return view('services.create');
    }

    // Store new service
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        Service::create($request->all());

        return redirect()->route('admin.services.index')
                         ->with('success', 'Service added successfully!');
    }

    // Show edit form
    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    // Update service
    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        $service->update($request->all());

        return redirect()->route('admin.services.index')
                         ->with('success', 'Service updated successfully!');
    }

    // Delete service
    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index')
                         ->with('success', 'Service deleted successfully!');
    }

    // Optional: Import (if you plan to handle CSV/XLS import)
    public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,csv,xls',
    ]);

    Excel::import(new ServicesImport, $request->file('file'));

    return redirect()->route('admin.services.index')
                     ->with('success', 'Services imported successfully!');
}
}
