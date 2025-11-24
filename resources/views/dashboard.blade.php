@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 p-6">

    <div class="mt-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Dashboard</h1>
    </div>

    
    <div class="flex flex-col space-y-4 w-64 mt-8">
        <a href="{{ route('admin.patients.index') }}" 
           class="bg-blue-600 text-white py-3 px-4 rounded-lg border hover:bg-blue-700 hover:scale-105 transform transition duration-200 text-center">
            Patient List
        </a>

        <a href="{{ route('admin.bills.create') }}" 
           class="bg-blue-600 text-white py-3 px-4 rounded-lg border hover:bg-blue-700 hover:scale-105 transform transition duration-200 text-center">
            Create Bill
        </a>

        <a href="{{ route('admin.tests.index') }}" 
           class="bg-blue-600 text-white py-3 px-4 rounded-lg border hover:bg-blue-700 hover:scale-105 transform transition duration-200 text-center">
            Test List
        </a>
    </div>

</div>
@endsection
