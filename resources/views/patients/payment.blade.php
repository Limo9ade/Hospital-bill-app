@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-12 p-8 bg-white border-2 border-blue-600 rounded shadow">

    <h2 class="text-2xl font-bold mb-6 text-center text-blue-700">💳 Payment Summary for {{ $patient->name }}</h2>

    <!-- Patient Info -->
    <div class="p-6 bg-blue-50 border border-blue-400 rounded mb-6">
        <p><strong>Name:</strong> {{ $patient->name }}</p>
        @php
    $latestBill = $patient->bills->last(); // or ->first() depending on order
    $doctorName = $latestBill && $latestBill->doctor ? $latestBill->doctor->name : 'Not Assigned';
@endphp

<p><strong>Doctor:</strong> {{ $doctorName }}</p>

        <p><strong>Phone:</strong> {{ $patient->phone }}</p>
        <p><strong>Age:</strong> {{ $patient->age }}</p>
        <p><strong>Registered:</strong> {{ $patient->created_at->format('d M Y, H:i') }}</p>
    </div>

    @php
        // Calculate totals
        $testsTotal = $patient->bills->flatMap->tests->sum('price');
        $servicesTotal = $patient->bills->flatMap->services->sum('price');
        $totalAmount = $testsTotal + $servicesTotal;

        // Payment info
        $paidAmount = $patient->paid_amount ?? 0; 
        $dueAmount = $totalAmount - $paidAmount;

        // Payment status
        $status = $dueAmount <= 0 ? 'Paid' : ($paidAmount > 0 ? 'Partial' : 'Unpaid');
    @endphp

    <!-- Billing Breakdown -->
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-blue-700 mb-2">🧾 Bill Breakdown:</h3>

        <ul class="list-disc list-inside text-gray-800 text-sm">
            <li><strong>Tests Total:</strong> ৳{{ number_format($testsTotal, 2) }}</li>
            <li><strong>Services Total:</strong> ৳{{ number_format($servicesTotal, 2) }}</li>
        </ul>

        <p class="mt-3 text-right text-lg font-semibold text-green-700">
            Grand Total: ৳{{ number_format($totalAmount, 2) }}
        </p>
    </div>

    <!-- Payment Form -->
    <form action="{{ route('admin.patients.payment.update', $patient->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="paid_amount" class="block text-gray-700 font-medium mb-2">💵 Enter Paid Amount:</label>
            <input type="number" name="paid_amount" id="paid_amount"
                   value="{{ old('paid_amount', $paidAmount) }}"
                   min="0" step="0.01"
                   class="w-full border border-blue-400 rounded px-4 py-2 focus:ring focus:ring-blue-300" required>
        </div>

        <!-- Status & Due -->
        <div class="flex justify-between items-center bg-blue-50 p-4 border rounded">
            <div>
                <p><strong>Status:</strong>
                    <span class="text-{{ $status === 'Paid' ? 'green' : ($status === 'Partial' ? 'yellow' : 'red') }}-600 font-semibold">
                        {{ $status }}
                    </span>
                </p>
                <p><strong>Due Amount:</strong>
                    ৳{{ number_format($dueAmount, 2) }}
                </p>
            </div>
        </div>

        <div class="flex justify-center gap-4 mt-6">
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition">
                💰 Update Payment
            </button>

            <a href="{{ route('admin.patients.index') }}"
               class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                ← Back
            </a>
        </div>
    </form>
</div>
@endsection
