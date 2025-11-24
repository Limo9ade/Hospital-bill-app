@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto mt-12 p-6 bg-white rounded shadow">
    <h1 class="text-2xl font-semibold mb-6 text-center">👨‍⚕️ All Patients</h1>

    @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-700 text-center">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter Form -->
    <form method="GET" action="{{ route('admin.patients.index') }}" 
          class="mb-6 mx-auto flex flex-nowrap justify-center items-center gap-3 
                 max-w-full overflow-x-auto bg-blue-50 p-4 rounded shadow-sm">
        <input type="text" name="name" placeholder="Name" value="{{ request('name') }}" 
               class="border-2 border-blue-600 bg-white px-4 py-2 rounded-md w-32 text-sm shadow-sm 
                      focus:outline-none focus:ring-2 focus:ring-blue-400 placeholder-black flex-shrink-0" />
        <input type="text" name="phone" placeholder="Phone" value="{{ request('phone') }}" 
               class="border-2 border-blue-600 bg-white px-4 py-2 rounded-md w-32 text-sm shadow-sm 
                      focus:outline-none focus:ring-2 focus:ring-blue-400 placeholder-black flex-shrink-0" />
        <input type="number" name="age" placeholder="Age" value="{{ request('age') }}" min="1" 
               class="border-2 border-blue-600 bg-white px-4 py-2 rounded-md w-20 text-sm shadow-sm 
                      focus:outline-none focus:ring-2 focus:ring-blue-400 placeholder-black flex-shrink-0" />
        <input type="date" name="date" value="{{ request('date') }}" 
               class="border-2 border-blue-600 bg-white px-4 py-2 rounded-md w-40 text-sm shadow-sm 
                      focus:outline-none focus:ring-2 focus:ring-blue-400 placeholder-black flex-shrink-0" />
        <button type="submit" 
                class="bg-blue-600 text-white px-5 py-2 rounded-md text-sm hover:bg-blue-700 shadow-md flex-shrink-0">
            Filter
        </button>
        <a href="{{ route('admin.patients.index') }}" 
           class="text-blue-700 hover:underline text-sm whitespace-nowrap flex-shrink-0 font-semibold">
            Reset
        </a>
    </form>

    @php
        $currentSort = request('sort', '');
        $currentDir = request('direction', 'asc');

        function sortLink($column, $label, $currentSort, $currentDir) {
            $dir = ($currentSort === $column && $currentDir === 'asc') ? 'desc' : 'asc';
            $arrow = ($currentSort === $column) ? ($currentDir === 'asc' ? ' ↑' : ' ↓') : '';
            $params = request()->all();
            $params['sort'] = $column;
            $params['direction'] = $dir;
            $url = url()->current() . '?' . http_build_query($params);
            return "<a href=\"$url\" class=\"hover:underline font-semibold\">$label$arrow</a>";
        }
    @endphp

    <!-- Scrollable Table -->
    <div class="overflow-y-auto max-h-[500px] border-2 border-blue-600 rounded shadow">
        <table class="min-w-full bg-white border-collapse border-2 border-blue-600">
            <thead class="bg-gray-200 text-gray-700 sticky top-0 z-10">
                <tr>
                    <th class="py-3 px-4 text-left border-2 border-blue-600">Name</th>
                    <th class="py-3 px-4 text-left border-2 border-blue-600">Phone</th>
                    <th class="py-3 px-4 text-left border-2 border-blue-600">Age</th>
                    <th class="py-3 px-4 text-left border-2 border-blue-600">Created At</th>
                    <th class="py-3 px-4 text-center border-2 border-blue-600">Actions</th>
                </tr>
            </thead>
            <tbody>
    @foreach ($patients as $patient)
        <tr class="hover:bg-blue-100 cursor-pointer">
            <td class="py-2 px-4 border-2 border-blue-600" onclick="window.location='{{ route('admin.patients.bills', $patient->id) }}';">
                {{ $patient->name }}
            </td>
            <td class="py-2 px-4 border-2 border-blue-600" onclick="window.location='{{ route('admin.patients.bills', $patient->id) }}';">
                {{ $patient->phone }}
            </td>
            <td class="py-2 px-4 border-2 border-blue-600" onclick="window.location='{{ route('admin.patients.bills', $patient->id) }}';">
                {{ $patient->age }}
            </td>
            <td class="py-2 px-4 border-2 border-blue-600" onclick="window.location='{{ route('admin.patients.bills', $patient->id) }}';">
                {{ $patient->created_at->format('d M Y, H:i') }}
            </td>
            <td class="py-2 px-4 border-2 border-blue-600 text-center space-x-2">
                <!-- Edit Button -->
                <a href="{{ route('admin.patients.edit', $patient->id) }}" 
                   class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition">
                    Edit
                </a>
                <!-- Delete Button -->
                <form action="{{ route('admin.patients.destroy', $patient->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this patient?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
</tbody>

        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $patients->links() }}
    </div>
</div>
@endsection
