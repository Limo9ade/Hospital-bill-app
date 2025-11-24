@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto mt-12 p-6 bg-white border-2 border-blue-600 rounded shadow">
    <h2 class="text-2xl font-semibold mb-6 text-center">Add New Bill</h2>

    <form action="{{ route('admin.patients.store') }}" method="POST" class="space-y-6">
        @csrf

        <div>
            <label for="name" class="block font-medium mb-1">Patient Name:</label>
            <input type="text" name="name" id="name" required
                class="w-full border-2 border-blue-600 rounded px-3 py-2" />
        </div>

        <div>
            <label for="age" class="block font-medium mb-1">Age:</label>
            <input type="number" name="age" id="age" min="1" required
                class="w-full border-2 border-blue-600 rounded px-3 py-2" />
        </div>

        <div>
            <label for="phone" class="block font-medium mb-1">Phone Number:</label>
            <input type="text" name="phone" id="phone" required
                class="w-full border-2 border-blue-600 rounded px-3 py-2" />
        </div>

        <!-- Doctor -->
        <div>
            <label for="doctor_id" class="block font-medium mb-1">Select Doctor:</label>
            <select name="doctor_id" id="doctor_id" required class="w-full border-2 border-blue-600 rounded px-3 py-2">
                <option value="" disabled selected>-- Choose Doctor --</option>
                @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tests -->
        <div>
            <p class="font-medium mb-2">Select Tests:</p>
            @foreach($tests as $test)
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="tests[]" value="{{ $test->id }}" />
                    <span>{{ $test->test_name }} (৳{{ number_format($test->price, 2) }})</span>
                </label>
            @endforeach
        </div>

        <!-- Services -->
        <div>
            <p class="font-medium mb-2">Select Services:</p>
            @foreach($services as $service)
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="services[]" value="{{ $service->id }}" />
                    <span>{{ $service->name }} (৳{{ number_format($service->price, 2) }})</span>
                </label>
            @endforeach
        </div>

        <div class="text-center">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">Create Bill</button>
        </div>
    </form>
</div>
@endsection
