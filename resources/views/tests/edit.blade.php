@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-12 p-6 bg-white rounded shadow">
    <h2 class="text-2xl font-semibold mb-6 text-center">✏️ Edit Test</h2>

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

    <!-- Edit Form -->
    <form action="{{ route('admin.tests.update', $test->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="test_name" class="block font-medium mb-1">Test Name:</label>
            <input type="text" name="test_name" id="test_name" required
                   value="{{ old('test_name', $test->test_name) }}"
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>

        <div>
            <label for="price" class="block font-medium mb-1">Price (৳):</label>
            <input type="number" name="price" id="price" min="0" step="0.01" required
                   value="{{ old('price', $test->price) }}"
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>

        <div class="text-center">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                Update Test
            </button>
        </div>
    </form>

    <div class="mt-6 text-center">
        <a href="{{ route('admin.tests.index') }}" class="text-blue-600 hover:underline">← Back to Tests List</a>
    </div>
</div>
@endsection
