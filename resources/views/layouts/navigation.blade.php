<nav class="bg-gray-700 text-white shadow-md p-6 flex justify-between items-center">
    <!-- Left: Logo + Links -->
    <div class="flex items-center">
        <a href="{{ route('home') }}" class="mr-12">
            <x-application-logo class="w-20 h-20" />
        </a>
        <div class="flex space-x-8 text-lg">
            <a href="{{ route('home') }}" class="hover:underline">Home</a>
            <a href="{{ route('admin.patients.index') }}" class="hover:underline">Patients</a>
            <a href="{{ route('admin.bills.index') }}" class="hover:underline">Create Bill</a>
            <a href="{{ route('admin.tests.index') }}" class="hover:underline">Tests List</a>
            <a href="{{ route('admin.doctors.index') }}" class="hover:underline">Doctors</a>
            <a href="{{ route('admin.services.index') }}" class="hover:underline">Services List</a>

        </div>
    </div>

    <!-- Right: Profile + Logout -->
    <div class="flex items-center space-x-6 text-lg">
        <a href="{{ route('profile.edit') }}" class="hover:underline">Profile</a>
        <form method="POST" action="{{ route('logout') }}" class="inline-flex m-0">
            @csrf
            <button type="submit" class="bg-transparent border-none cursor-pointer p-0 hover:underline">
                Logout
            </button>
        </form>
    </div>
</nav>
