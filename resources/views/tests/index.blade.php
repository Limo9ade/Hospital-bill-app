@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto mt-12 p-6 bg-white rounded shadow">

    <!-- Page Heading -->
    <h1 class="text-2xl font-semibold mb-6 text-center">🧪 All Tests</h1>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search Form -->
    <form method="GET" action="{{ route('admin.tests.index') }}" class="mb-6 flex flex-col sm:flex-row sm:items-center sm:space-x-2 space-y-2 sm:space-y-0">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search tests..."
            class="border border-gray-300 rounded px-3 py-2 flex-grow"
        />
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-200">
            Search
        </button>

        @if(request('search'))
            <a href="{{ route('admin.tests.index') }}" class="text-gray-600 underline ml-0 sm:ml-4">Clear Search</a>
        @endif
    </form>

    <!-- Import Form -->
    <form action="{{ route('admin.tests.import') }}" method="POST" enctype="multipart/form-data" class="mb-6">
        @csrf
        <label for="file" class="block mb-2 font-semibold">Import Tests (CSV, XLSX, XLS):</label>
        <input type="file" name="file" id="file" required class="border border-gray-300 rounded px-3 py-2 w-full max-w-sm" />
        <button type="submit" class="mt-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-200">Import</button>
    </form>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded shadow-md">
            <thead class="bg-gray-200 text-gray-700">
                <tr>
                    <th class="py-3 px-6 text-left border-b border-gray-300">#</th>
                    <th class="py-3 px-6 text-left border-b border-gray-300">Test Name</th>
                    <th class="py-3 px-6 text-left border-b border-gray-300">Price (৳)</th>
                    <th class="py-3 px-6 text-left border-b border-gray-300">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tests as $test)
                    <tr class="hover:bg-gray-50 cursor-pointer">
                        <td class="py-3 px-6 border-b border-gray-300">{{ $loop->iteration }}</td>
                        <td class="py-3 px-6 border-b border-gray-300">{{ $test->test_name }}</td>
                        <td class="py-3 px-6 border-b border-gray-300">{{ number_format($test->price, 2) }}</td>
                        <td class="py-3 px-6 border-b border-gray-300 space-x-3">
                            <a href="{{ route('admin.tests.edit', $test->id) }}" class="text-blue-600 hover:underline">Edit</a>
                            <form action="{{ route('admin.tests.destroy', $test->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure?')" class="text-red-600 hover:underline">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-4 text-center text-gray-500">No tests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
