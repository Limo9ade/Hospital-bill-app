<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Diagnostic Center</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans bg-white">
    @include('layouts.navigation')

    <main class="min-h-screen p-6">
        @yield('content')
    </main>
</body>

</html>
