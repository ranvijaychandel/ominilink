<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50">

    <!-- Navigation Bar -->
    <nav x-data="{ open: false }" class="bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">

                <!-- Brand Logo & Name -->
                <a href="{{ url('/') }}" class="inline-flex items-center gap-3 group transition-all duration-300">

                    <!-- Custom Icon: Link + Network Node -->
                    <span
                        class="relative flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-teal-500 to-teal-700 text-white shadow-lg shadow-teal-500/20 group-hover:shadow-teal-500/40 group-hover:scale-105 transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <!-- Chain Link Path -->
                            <path d="M10 13a5 5 0 0 0 7.07 0l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                            <path d="M14 11a5 5 0 0 0-7.07 0l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                            <!-- Network Node (Dot) -->
                            <circle cx="12" cy="12" r="2" fill="currentColor" stroke="none"></circle>
                        </svg>
                    </span>

                    <!-- Brand Text -->
                    <span class="flex flex-col justify-center">
                        <span
                            class="text-xl font-extrabold tracking-tight text-slate-900 group-hover:text-teal-700 transition-colors">
                            OminiLink
                        </span>
                        <span
                            class="text-[0.65rem] font-semibold uppercase tracking-widest text-slate-400 group-hover:text-teal-600/70 transition-colors">
                            Simplest URL Shortener
                        </span>
                    </span>
                </a>


                @if ($errors->any())
                    <div class="mb-4 rounded-lg bg-red-100 p-4 text-sm text-red-800" role="alert">
                        @foreach ($errors->all() as $error)
                            <p class="mb-1">{{ $error }}</p>
                        @endforeach
                    </div>
                @elseif (session('error'))
                    <div class="mb-4 rounded-lg bg-red-100 p-4 text-sm text-red-800" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Optional: Right side actions (Login/Signup) to balance the nav -->
                {{-- <div class="hidden md:flex items-center gap-4">
                    <a href="#" class="text-sm font-semibold text-slate-600 hover:text-teal-700 transition-colors">Sign In</a>
                    <a href="#" class="text-sm font-semibold bg-slate-900 text-white px-5 py-2.5 rounded-lg hover:bg-slate-800 transition-all shadow-sm hover:shadow-md">Get Started</a>
                </div> --}}

            </div>
        </div>
    </nav>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('[role="alert"]');

            setTimeout(function() {
                alerts.forEach(function(alert) {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';

                    setTimeout(function() {
                        alert.remove();
                    }, 500);
                });
            }, 2000);
        });
    </script>
</body>

</html>
