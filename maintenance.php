<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Servicing - Routine Flow</title>
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <script>
        (function () {
            const theme = localStorage.getItem('theme') || 'light';
            if (theme === 'dark') document.documentElement.classList.add('dark');
        })();
    </script>
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .dark .glass-card {
            background: rgba(11, 17, 33, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        @keyframes float {
            0% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(5deg);
            }

            100% {
                transform: translateY(0px) rotate(0deg);
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>

<body
    class="bg-gray-50 dark:bg-[#0B1121] min-h-screen flex items-center justify-center p-6 text-gray-900 dark:text-white transition-colors duration-500 overflow-hidden">

    
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-indigo-500/10 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-purple-500/10 blur-[120px] rounded-full">
        </div>
    </div>

    <main class="relative z-10 w-full max-w-2xl">
        <div class="glass-card rounded-[60px] p-12 lg:p-20 text-center space-y-10 shadow-2xl">
            
            <div class="relative inline-block">
                <div
                    class="w-32 h-32 rounded-[40px] bg-gradient-to-br from-indigo-600 to-purple-700 flex items-center justify-center text-white text-6xl shadow-2xl shadow-indigo-500/30 animate-float">
                    <i class="ri-tools-fill"></i>
                </div>
                <div
                    class="absolute -bottom-2 -right-2 w-12 h-12 rounded-2xl bg-white dark:bg-[#16213e] flex items-center justify-center text-indigo-600 shadow-xl">
                    <i class="ri-settings-4-line animate-spin-slow"></i>
                </div>
            </div>

            <div class="space-y-4">
                <h1
                    class="text-4xl lg:text-6xl font-black tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 via-purple-600 to-indigo-600">
                    System Servicing</h1>
                <p class="text-xl text-gray-500 dark:text-gray-400 font-medium max-w-md mx-auto">Routine Flow is
                    currently undergoing scheduled maintenance to enhance your experience. We'll be back shortly.</p>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                <div
                    class="px-8 py-4 rounded-3xl bg-gray-100 dark:bg-white/5 border border-transparent font-bold text-sm flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Database Optimization
                </div>
                <div
                    class="px-8 py-4 rounded-3xl bg-gray-100 dark:bg-white/5 border border-transparent font-bold text-sm flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                    UI Enhancements
                </div>
            </div>

            <div class="pt-10 border-t border-gray-100 dark:border-white/5">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-[0.3em]">Estimated Completion: 2.5 Hours
                </p>
                <div class="w-48 h-1 bg-gray-100 dark:bg-white/5 mx-auto mt-6 rounded-full overflow-hidden">
                    <div class="h-full bg-indigo-600 w-2/3 rounded-full shadow-[0_0_20px_rgba(79,70,229,0.5)]"></div>
                </div>
            </div>
        </div>

        <p class="mt-10 text-center text-gray-400 font-medium text-sm">Need immediate assistance? Contact <a
                href="mailto:support@routineflow.com" class="text-indigo-600 font-bold hover:underline">Support</a></p>
    </main>

    <style>
        .animate-spin-slow {
            animation: spin 8s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>
</body>

</html>
