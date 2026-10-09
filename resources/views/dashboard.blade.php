<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard - Control de Posgrados') }}
            </h2>
            <div class="text-sm text-gray-600 dark:text-gray-400 font-medium" id="current-datetime">
                <!-- Date and Time will be rendered here -->
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Posgrados Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 flex flex-col items-center">
                        <div class="text-4xl font-bold text-blue-600 dark:text-blue-400">{{ $kpis['posgrados'] ?? 0 }}</div>
                        <div class="mt-2 text-sm text-gray-500 uppercase tracking-wide font-semibold">Total Posgrados</div>
                    </div>
                </div>

                <!-- Semestres Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 flex flex-col items-center">
                        <div class="text-4xl font-bold text-amber-600 dark:text-amber-400">{{ $kpis['semestres'] ?? 0 }}</div>
                        <div class="mt-2 text-sm text-gray-500 uppercase tracking-wide font-semibold">Total Semestres</div>
                    </div>
                </div>

                <!-- Grupos Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 flex flex-col items-center">
                        <div class="text-4xl font-bold text-emerald-600 dark:text-emerald-400">{{ $kpis['grupos'] ?? 0 }}</div>
                        <div class="mt-2 text-sm text-gray-500 uppercase tracking-wide font-semibold">Total Grupos</div>
                    </div>
                </div>

                <!-- Alumnos Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 flex flex-col items-center">
                        <div class="text-4xl font-bold text-indigo-600 dark:text-indigo-400">{{ $kpis['alumnos'] ?? 0 }}</div>
                        <div class="mt-2 text-sm text-gray-500 uppercase tracking-wide font-semibold">Total Alumnos</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Bienvenido al Sistema de Control de Posgrados</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-3">
                        Has iniciado sesión como: <span class="font-bold text-indigo-600 dark:text-indigo-400 capitalize">{{ Auth::user()->role ?? 'Usuario' }}</span>
                    </p>
                    <p class="text-gray-500 dark:text-gray-400">
                        Utiliza el menú de navegación superior para acceder a los catálogos de Alumnos, Profesores, Posgrados y Asignaturas. 
                        Desde allí podrás gestionar la información académica y realizar asignaciones de semestres, grupos y horarios.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function updateDateTime() {
                const datetimeElement = document.getElementById('current-datetime');
                if (!datetimeElement) return;

                const now = new Date();
                const options = { 
                    weekday: 'long', year: 'numeric', month: 'long', day: 'numeric',
                    hour: '2-digit', minute: '2-digit', second: '2-digit',
                    hour12: true
                };
                
                // Capitalize the first letter of the string and render
                let formatted = now.toLocaleDateString('es-MX', options);
                formatted = formatted.charAt(0).toUpperCase() + formatted.slice(1);
                datetimeElement.textContent = formatted;
            }
            
            // Update immediately and then every second
            updateDateTime();
            setInterval(updateDateTime, 1000);
        });
    </script>
</x-app-layout>
