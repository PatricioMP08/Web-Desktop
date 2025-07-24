<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escritorio Web</title>

    @vite('resources/css/app.css')
    @livewireStyles
    <script src="https://unpkg.com/lucide@latest/dist/lucide.min.js"></script>

    
</head>
<body class="bg-gradient-to-br from-blue-200 to-indigo-300 h-screen overflow-hidden">
    @yield('content')
    @livewireScripts
    
</body>
</html>