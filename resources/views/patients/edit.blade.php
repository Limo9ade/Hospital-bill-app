@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-12 p-6 bg-white border-2 border-blue-600 rounded shadow">

    <h2 class="text-2xl font-semibold mb-6 text-center">✏️ Edit Patient: {{ $patient->name }}</h2>

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.patients.update', $patient->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PATCH')

        <!-- Patient Info -->
        <div>
            <label for="name" class="block font-medium mb-1">Patient Name:</label>
            <input type="text" name="name" id="name" value="{{ old('name', $patient->name) }}" required
                class="w-full border-2 border-blue-600 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600" />
        </div>

        <div>
            <label for="age" class="block font-medium mb-1">Age:</label>
            <input type="number" name="age" id="age" min="1" value="{{ old('age', $patient->age) }}" required
                class="w-full border-2 border-blue-600 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600" />
        </div>

        <div>
            <label for="phone" class="block font-medium mb-1">Phone Number:</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone', $patient->phone) }}" required
                class="w-full border-2 border-blue-600 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600" />
        </div>
        
        <!-- Doctor Selection -->
<div class="mb-4">
    <label for="doctor_id" class="block font-medium mb-1">Select Doctor:</label>
    <select name="doctor_id" id="doctor_id" required
        class="w-full border-2 border-blue-600 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600">
        <option value="" disabled selected>-- Choose Doctor --</option>
        @foreach($doctors as $doctor)
            <option value="{{ $doctor->id }}"
                {{ old('doctor_id', $selectedDoctorId ?? $patient->bills->first()->doctor_id ?? '') == $doctor->id ? 'selected' : '' }}>
                {{ $doctor->name }}
            </option>
        @endforeach
    </select>
</div>


        <!-- Tests Selection -->
        <div>
            <p class="font-medium mb-2 flex justify-between items-center">
                <span>Select Tests:</span>
                <input
                    type="text"
                    id="testSearch"
                    placeholder="Search tests..."
                    class="border-2 border-blue-600 rounded px-2 py-1 text-sm bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-600"
                    onkeyup="filterItems('testSearch', 'testsList', 'test-name')"
                />
            </p>

            <div id="testsList" class="space-y-2 max-h-48 overflow-y-auto border-2 border-blue-600 rounded p-3 bg-blue-50">
                @foreach ($tests as $test)
                    <label class="flex items-center space-x-2 test-item">
                        <input type="checkbox" name="tests[]" value="{{ $test->id }}" class="form-checkbox text-blue-600"
                            {{ in_array($test->id, old('tests', $selectedTestIds ?? [])) ? 'checked' : '' }} />
                        <span class="test-name">{{ $test->test_name }} (৳{{ number_format($test->price, 2) }})</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Services Selection -->
        <div>
            <p class="font-medium mb-2 flex justify-between items-center">
                <span>Select Services:</span>
                <input
                    type="text"
                    id="serviceSearch"
                    placeholder="Search services..."
                    class="border-2 border-blue-600 rounded px-2 py-1 text-sm bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-600"
                    onkeyup="filterItems('serviceSearch', 'servicesList', 'service-name')"
                />
            </p>

            <div id="servicesList" class="space-y-2 max-h-48 overflow-y-auto border-2 border-blue-600 rounded p-3 bg-blue-50">
                @foreach ($services as $service)
                    <label class="flex items-center space-x-2 service-item">
                        <input type="checkbox" name="services[]" value="{{ $service->id }}" class="form-checkbox text-blue-600"
                            {{ in_array($service->id, old('services', $selectedServiceIds ?? [])) ? 'checked' : '' }} />
                        <span class="service-name">{{ $service->name }} (৳{{ number_format($service->price, 2) }})</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Update Button -->
        <div class="text-center mt-4">
            <button type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition duration-200">
                💾 Update Patient
            </button>
        </div>
    </form>
</div>

<!-- Universal Filter Script -->
<script>
function filterItems(searchId, listId, nameClass) {
    const input = document.getElementById(searchId);
    const filter = input.value.toLowerCase();
    const list = document.getElementById(listId);
    const items = list.getElementsByTagName('label');

    for (let i = 0; i < items.length; i++) {
        const itemName = items[i].querySelector('.' + nameClass).textContent.toLowerCase();
        items[i].style.display = itemName.includes(filter) ? '' : 'none';
    }
}
</script>
@endsection
