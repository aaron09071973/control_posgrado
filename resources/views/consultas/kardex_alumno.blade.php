<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Consulta: Kardex por Alumno') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8">
            
            <!-- Selector de Alumno -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="GET" action="{{ route('consultas.kardex') }}" class="flex items-end space-x-4">
                        <div class="flex-grow">
                            <x-input-label for="alumno_id" value="Selecciona un Alumno" />
                            <select id="alumno_id" name="alumno_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">-- Seleccionar --</option>
                                @foreach($alumnos as $alumno)
                                    <option value="{{ $alumno->id_alumno }}" {{ request('alumno_id') == $alumno->id_alumno ? 'selected' : '' }}>
                                        {{ $alumno->nombre }} {{ $alumno->apellido }} (Cuenta: {{ $alumno->Cta_alumno }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-primary-button type="submit">Consultar</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Resultados -->
            @if(request()->has('alumno_id') && request('alumno_id') != '')
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm" role="alert">
                        <span class="block sm:inline font-medium">{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow-sm" role="alert">
                        <span class="block sm:inline font-medium">{{ session('error') }}</span>
                    </div>
                @endif

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        @if($alumnoSeleccionado)
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                                <div>
                                    <h3 class="text-xl font-bold text-indigo-600 dark:text-indigo-400">
                                        Kardex de: {{ $alumnoSeleccionado->nombre }} {{ $alumnoSeleccionado->apellido }}
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Cuenta: <span class="font-mono">{{ $alumnoSeleccionado->Cta_alumno }}</span> | Correo: {{ $alumnoSeleccionado->correo_electronico }}</p>
                                </div>
                                @if(!$horarios->isEmpty())
                                    <div class="flex gap-4 w-full md:w-auto justify-end">
                                        <a href="{{ route('consultas.kardex.export', ['alumno_id' => $alumnoSeleccionado->id_alumno]) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                                            Exportar a Excel
                                        </a>
                                        <form method="POST" action="{{ route('consultas.kardex.email') }}">
                                            @csrf
                                            <input type="hidden" name="alumno_id" value="{{ $alumnoSeleccionado->id_alumno }}">
                                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm" onclick="return confirm('¿Enviar Kardex por correo al alumno?')">
                                                Enviar por Correo
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                            
                            @if($horarios->isEmpty())
                                <p class="text-gray-500 dark:text-gray-400">Este alumno no tiene asignaturas programadas (o no está inscrito en ningún grupo con asignaturas).</p>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Código</th>
                                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Asignatura</th>
                                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Horario</th>
                                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Hrs Lectivas</th>
                                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider font-bold">Calificación</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($horarios as $horario)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-mono">{{ $horario->asignatura->codigo_asignatura ?? 'N/A' }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $horario->asignatura->nombre_asignatura }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $horario->horario }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $horario->horas_lectivas }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold">
                                                        @php
                                                            $gradeVal = floatval($horario->calificacion);
                                                            $badgeColor = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                                                            if ($horario->calificacion !== 'Sin Calificación') {
                                                                if (is_numeric($horario->calificacion)) {
                                                                    if ($gradeVal >= 8.0) {
                                                                        $badgeColor = 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
                                                                    } elseif ($gradeVal >= 6.0) {
                                                                        $badgeColor = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400';
                                                                    } else {
                                                                        $badgeColor = 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
                                                                    }
                                                                }
                                                            }
                                                        @endphp
                                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ $badgeColor }}">
                                                            {{ $horario->calificacion }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @else
                            <p class="text-red-500">El alumno seleccionado no existe.</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
