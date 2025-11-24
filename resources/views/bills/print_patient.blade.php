<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Patient Bill - {{ $patient->name }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        h2, h3 { text-align: center; margin-bottom: 10px; }
        p { margin: 4px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
        .total { text-align: right; font-weight: bold; margin-top: 15px; font-size: 16px; }
        .section-title { margin-top: 30px; font-weight: bold; font-size: 18px; border-bottom: 2px solid #000; }
        .summary-box {
            border: 2px solid #000;
            padding: 10px;
            margin-top: 25px;
            background-color: #f9f9f9;
        }
        .status-paid { color: green; font-weight: bold; }
        .status-partial { color: orange; font-weight: bold; }
        .status-unpaid { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <h2>🧾 Patient Bill Summary</h2>

    <p><strong>Name:</strong> {{ $patient->name }}</p>
    @php
    $latestBill = $patient->bills->last(); // or ->first() depending on order
    $doctorName = $latestBill && $latestBill->doctor ? $latestBill->doctor->name : 'Not Assigned';
@endphp

<p><strong>Doctor:</strong> {{ $doctorName }}</p>

    <p><strong>Phone:</strong> {{ $patient->phone }}</p>
    <p><strong>Age:</strong> {{ $patient->age }}</p>
    <p><strong>Registered At:</strong> {{ $patient->created_at->format('d M Y, H:i') }}</p>

    <!-- ===== Tests Section ===== -->
    <div class="section-title">Tests</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Test Name</th>
                <th>Price (৳)</th>
            </tr>
        </thead>
        <tbody>
            @php $counter = 1; $testsTotal = 0; @endphp
            @foreach($patient->bills as $bill)
                @foreach($bill->tests as $test)
                    <tr>
                        <td>{{ $counter++ }}</td>
                        <td>{{ $test->test_name }}</td>
                        <td>{{ number_format($test->price, 2) }}</td>
                    </tr>
                    @php $testsTotal += $test->price; @endphp
                @endforeach
            @endforeach
            @if ($testsTotal == 0)
                <tr><td colspan="3" style="text-align:center; color:#888;">No tests recorded</td></tr>
            @endif
        </tbody>
    </table>
    <div class="total">Subtotal (Tests): ৳{{ number_format($testsTotal, 2) }}</div>

    <!-- ===== Services Section ===== -->
    <div class="section-title">Services</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Service Name</th>
                <th>Price (৳)</th>
            </tr>
        </thead>
        <tbody>
            @php $counter = 1; $servicesTotal = 0; @endphp
            @foreach($patient->bills as $bill)
                @foreach($bill->services as $service)
                    <tr>
                        <td>{{ $counter++ }}</td>
                        <td>{{ $service->name }}</td>
                        <td>{{ number_format($service->price, 2) }}</td>
                    </tr>
                    @php $servicesTotal += $service->price; @endphp
                @endforeach
            @endforeach
            @if ($servicesTotal == 0)
                <tr><td colspan="3" style="text-align:center; color:#888;">No services recorded</td></tr>
            @endif
        </tbody>
    </table>
    <div class="total">Subtotal (Services): ৳{{ number_format($servicesTotal, 2) }}</div>

    <!-- ===== Grand Total & Payment Summary ===== -->
    @php
        $grandTotal = $testsTotal + $servicesTotal;
        $paidAmount = $patient->paid_amount ?? 0;
        $dueAmount = $grandTotal - $paidAmount;

        $status = $dueAmount <= 0 ? 'Paid' : ($paidAmount > 0 ? 'Partial' : 'Unpaid');
        $statusClass = $status === 'Paid' ? 'status-paid' : ($status === 'Partial' ? 'status-partial' : 'status-unpaid');
    @endphp

    <div class="summary-box">
        <h3>💳 Payment Summary</h3>
        <p><strong>Total Bill:</strong> ৳{{ number_format($grandTotal, 2) }}</p>
        <p><strong>Paid Amount:</strong> ৳{{ number_format($paidAmount, 2) }}</p>
        <p><strong>Remaining Due:</strong> ৳{{ number_format($dueAmount > 0 ? $dueAmount : 0, 2) }}</p>
        <p><strong>Status:</strong> <span class="{{ $statusClass }}">{{ $status }}</span></p>
    </div>

    <hr style="margin-top: 30px;">
    <p style="text-align:center; font-size:14px; margin-top:20px;">
        Thank you for visiting our diagnostics center 💙 <br>
        <em>Wishing you good health!</em>
    </p>
</body>
</html>
