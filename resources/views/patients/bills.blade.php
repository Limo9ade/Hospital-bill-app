@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto mt-12 p-6 bg-white border-2 border-blue-600 rounded shadow">

    <h2 class="text-2xl font-bold mb-6 text-center">🧾 Bill Details for {{ $patient->name }}</h2>

    <!-- Patient Details -->
    <section class="mb-6 p-6 bg-blue-50 border-2 border-blue-600 rounded shadow-sm">
        <h3 class="text-lg font-semibold mb-3">Patient Details:</h3>
        <p><strong>Name:</strong> {{ $patient->name }}</p>
        @php
    $latestBill = $patient->bills->last(); // or ->first() depending on order
    $doctorName = $latestBill && $latestBill->doctor ? $latestBill->doctor->name : 'Not Assigned';
@endphp

<p><strong>Doctor:</strong> {{ $doctorName }}</p>

        <p><strong>Phone:</strong> {{ $patient->phone }}</p>
        <p><strong>Age:</strong> {{ $patient->age }}</p>
        <p><strong>Registered At:</strong> {{ $patient->created_at->format('d M Y, H:i') }}</p>
    </section>

    @php
        $grandTotal = $patient->bills->sum(function($bill) {
            return $bill->tests->sum('price') + $bill->services->sum('price');
        });

        $paidAmount = $patient->paid_amount ?? 0;
        $dueAmount = $grandTotal - $paidAmount;
    @endphp

    <!-- Bills Breakdown -->
    @if($patient->bills->count())
        @foreach($patient->bills as $bill)
            @php
                $testsTotal = $bill->tests->sum('price');
                $servicesTotal = $bill->services->sum('price');
                $billTotal = $testsTotal + $servicesTotal;
            @endphp

            <div class="mb-8 p-5 bg-blue-50 border-2 border-blue-600 rounded shadow-sm">
                <h3 class="text-lg font-semibold mb-3 text-blue-700">Bill #{{ $bill->id }}</h3>

                <!-- Tests -->
                <div class="mb-4">
                    <strong class="text-gray-700">Tests:</strong>
                    @if($bill->tests->count())
                        <ul class="list-disc list-inside text-sm text-gray-800 mt-1">
                            @foreach($bill->tests as $test)
                                <li>{{ $test->test_name }} (৳{{ number_format($test->price, 2) }})</li>
                            @endforeach
                        </ul>
                        <p class="mt-2 text-sm text-green-700">
                            <strong>Subtotal (Tests):</strong> ৳{{ number_format($testsTotal, 2) }}
                        </p>
                    @else
                        <p class="text-gray-600 italic mt-1">No tests added for this bill.</p>
                    @endif
                </div>

                <!-- Services -->
                <div>
                    <strong class="text-gray-700">Services:</strong>
                    @if($bill->services->count())
                        <ul class="list-disc list-inside text-sm text-gray-800 mt-1">
                            @foreach($bill->services as $service)
                                <li>{{ $service->name }} (৳{{ number_format($service->price, 2) }})</li>
                            @endforeach
                        </ul>
                        <p class="mt-2 text-sm text-green-700">
                            <strong>Subtotal (Services):</strong> ৳{{ number_format($servicesTotal, 2) }}
                        </p>
                    @else
                        <p class="text-gray-600 italic mt-1">No services added for this bill.</p>
                    @endif
                </div>

                <!-- Total per Bill -->
                <div class="mt-4 text-right">
                    <strong>Total (This Bill):</strong>
                    <span class="text-green-600 font-semibold">৳{{ number_format($billTotal, 2) }}</span>
                </div>
            </div>
        @endforeach
    @else
        <div class="text-center mt-6 text-gray-600">
            No bills found for this patient.
        </div>
    @endif

    <!-- Total Summary -->
    <div class="mb-6 p-6 bg-blue-50 border-2 border-blue-600 rounded shadow-sm">
        <h3 class="text-lg font-semibold text-blue-700 mb-3">💰 Payment Summary:</h3>
        <p><strong>Total Bill:</strong> ৳{{ number_format($grandTotal, 2) }}</p>
        <p><strong>Paid Amount:</strong> ৳{{ number_format($paidAmount, 2) }}</p>
        <p><strong>Remaining Due:</strong> 
            <span class="text-{{ $dueAmount <= 0 ? 'green' : 'red' }}-600 font-semibold">
                ৳{{ number_format($dueAmount > 0 ? $dueAmount : 0, 2) }}
            </span>
        </p>
    </div>

    <!-- Actions -->
    <div class="flex flex-wrap justify-center gap-4 mt-8">
        <a href="{{ route('admin.patients.edit', $patient->id) }}"
           class="bg-yellow-500 text-white px-6 py-2 rounded hover:bg-yellow-600 transition">
            ✏️ Edit Patient
        </a>

        <a href="{{ route('admin.patients.payment', $patient->id) }}"
            class="bg-purple-600 text-white px-6 py-2 rounded hover:bg-purple-700 transition">
             💳 Payment
        </a>

        <a href="{{ route('admin.patients.print', $patient->id) }}" target="_blank"
           class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition">
            🖨️ Print Bill
        </a>

        <form action="{{ route('admin.patients.destroy', $patient->id) }}" method="POST"
              onsubmit="return confirm('Are you sure you want to delete this patient? All bills will be removed.')">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700 transition">
                🗑️ Delete Patient
            </button>
        </form>

        <a href="{{ route('admin.patients.index') }}"
           class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
            ← Back to Patients List
        </a>
    </div>

</div>
@endsection
