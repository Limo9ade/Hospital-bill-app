<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <!-- Nav -->
    <nav class="bg-white shadow p-4 flex justify-between items-center">
        <div class="space-x-4">
            <a href="{{ route('home') }}" class="text-blue-600 hover:underline">Home</a>
            <a href="{{ route('admin.patients.index') }}" class="text-blue-600 hover:underline">Patients</a>
            <a href="{{ route('admin.bills.index') }}" class="text-blue-600 hover:underline">Create Bill</a>
            <a href="{{ route('admin.tests.index') }}" class="text-blue-600 hover:underline">Tests List</a>
            <a href="{{ route('admin.tests.create') }}" class="text-blue-600 hover:underline">Create Test</a>
        </div>
       <div class="flex items-center space-x-4">
    <a href="{{ route('profile.edit') }}" class="text-gray-700 hover:underline">Profile</a>

    <form method="POST" action="{{ route('logout') }}" class="m-0">
        @csrf
        <button type="submit" class="text-gray-700 hover:underline bg-transparent border-none cursor-pointer p-0">
            Logout
        </button>
    </form>
</div>

    </nav>

    <!-- Header -->
    <header class="bg-white shadow p-6">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </header>

    <!-- Main Content -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Update Profile Information -->
            <div class="p-6 bg-white shadow rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Update Profile Information</h3>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <!-- Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}"
                               class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:ring focus:ring-blue-200">
                        @error('name')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}"
                               class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:ring focus:ring-blue-200">
                        @error('email')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Save -->
                    <div class="mt-6">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Save
                        </button>
                    </div>
                </form>
            </div>

            <!-- Update Password -->
            <div class="p-6 bg-white shadow rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Update Password</h3>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')

                    <!-- Current Password -->
                    <div class="mb-4">
                        <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                        <input type="password" name="current_password" id="current_password" autocomplete="current-password"
                               class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:ring focus:ring-blue-200">
                        @error('current_password')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                        <input type="password" name="password" id="password" autocomplete="new-password"
                               class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:ring focus:ring-blue-200">
                        @error('password')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password"
                               class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:ring focus:ring-blue-200">
                    </div>

                    <!-- Save -->
                    <div class="mt-6">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

            <!-- Delete User Account -->
            <div class="p-6 bg-white shadow rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Delete Account</h3>

                <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you sure you want to delete your account?');">
                    @csrf
                    @method('DELETE')

                    <p class="text-sm text-gray-600 mb-4">
                        Once your account is deleted, all of its resources and data will be permanently deleted.
                    </p>

                    <div class="mb-4">
                        <label for="password_delete" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" id="password_delete" required autocomplete="current-password"
                               class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:ring focus:ring-red-200">
                        @error('password')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                            Delete Account
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

</body>
</html>
