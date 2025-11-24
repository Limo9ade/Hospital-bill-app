<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Imports\TestsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class TestController extends Controller
{
    
   
    public function index(Request $request)
{
    $search = $request->query('search');

    $query = Test::query();

    if ($search) {
        // Filter tests where test_name contains the search term (case insensitive)
        $query->where('test_name', 'LIKE', "%{$search}%");
    }

    $tests = $query->get();

    return view('tests.index', compact('tests', 'search'));
}

    


    // Store new test
    public function store(Request $request)
    {
        $request->validate([
            'test_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        Test::create($request->all());

        return redirect()->route('admin.tests.index')->with('success', 'Test added successfully!');
    }

    // Show edit form
    public function edit(Test $test)
    {
        return view('tests.edit', compact('test'));
    }

    // Update test
    public function update(Request $request, Test $test)
    {
        $request->validate([
            'test_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $test->update($request->all());

        return redirect()->route('admin.tests.index')->with('success', 'Test updated successfully!');
    }

    // Delete test
    public function destroy(Test $test)
    {
        $test->delete();

        return redirect()->route('admin.tests.index')->with('success', 'Test deleted successfully!');
    }

    // Import tests from Excel/CSV
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls',
        ]);

        Excel::import(new TestsImport, $request->file('file'));

        return back()->with('success', 'Tests imported successfully!');
    }
}
