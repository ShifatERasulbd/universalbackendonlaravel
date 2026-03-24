<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SSAdmin - E-commerce Dashboard')</title>

    <!-- Using Google Fonts for modern typography -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Using Material Icons for visual elements -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <!-- Custom Admin Styles -->
    <link href="{{ asset('css/backend/admin.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body>

    @include('backend.components.sidebar')

    <!-- Main Content -->
    <main class="main-content">
        @include('backend.components.header')

        <!-- Dynamic Content Container -->
        <div class="dashboard-container" id="main-view">
            @yield('content')
        </div>
    </main>

    

    <!-- Custom Admin Scripts -->
    <script src="{{ asset('js/backend/admin.js') }}"></script>

    @stack('scripts')
</body>
</html>