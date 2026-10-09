<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Consulta: Asignaturas por Profesor') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8">
            
            <!-- Selector de Profesor -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="GET" action="{{ route('consultas.asignaturas_profesor') }}" class="flex items-end space-x-4">
                        <div class="flex-grow">
                            <x-input-label for="profesor_id" value="Selecciona un Profesor" />
                            <select id="profesor_id" name="profesor_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">-- Seleccionar --</option>
                                @foreach($profesores as $profesor)
                                    <option value="{{ $profesor->id_profesor }}" {{ request('profesor_id') == $profesor->id_profesor ? 'selected' : '' }}>
                                        {{ $profesor->nombre }} {{ $profesor->apellido }} (Cod: {{ $profesor->cod_profesor }})
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
            @if(request()->has('profesor_id') && request('profesor_id') != '')
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        @if($profesorSeleccionado)
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                                    Asignaturas impartidas por: {{ $profesorSeleccionado->nombre }} {{ $profesorSeleccionado->apellido }}
                                </h3>
                                @if(!$horarios->isEmpty())
                                    <div class="flex gap-4">
                                        <a href="{{ route('consultas.asignaturas_profesor.export', ['profesor_id' => $profesorSeleccionado->id_profesor]) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            Exportar a Excel
                                        </a>
                                        <form method="POST" action="{{ route('consultas.asignaturas_profesor.email') }}">
                                            @csrf
                                            <input type="hidden" name="profesor_id" value="{{ $profesorSeleccionado->id_profesor }}">
                                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150" onclick="return confirm('¿Enviar reporte por correo al profesor?')">
                                                Enviar por Correo
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                            
                            @if($horarios->isEmpty())
                                <p class="text-gray-500">Este profesor no tiene asignaturas programadas actualmente.</p>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Código</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Asignatura</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Grupo</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Horario</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Alumnos Asignados</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($horarios as $horario)
                                                @php
                                                    $alumnos = collect();
                                                    if ($horario->grupo && $horario->grupo->alumnos) {
                                                        $alumnos = $horario->grupo->alumnos;
                                                    }
                                                @endphp
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $horario->asignatura->codigo_asignatura ?? 'N/A' }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $horario->asignatura->nombre_asignatura }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $horario->grupo ? $horario->grupo->nombre_grupo : 'N/A' }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $horario->horario }} ({{ $horario->horas_lectivas }}h)</td>
                                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                        <span class="font-semibold text-gray-700 dark:text-gray-300">Total: {{ $alumnos->count() }}</span>
                                                        @if($alumnos->isNotEmpty())
                                                            <div class="mt-1 text-xs text-gray-500" style="max-width: 250px; max-height: 80px; overflow-y: auto;">
                                                                {{ $alumnos->map(function($a) { return $a->nombre . ' ' . $a->apellido; })->implode(', ') }}
                                                            </div>
                                                        @else
                                                            <div class="mt-1 text-xs text-red-500">Sin alumnos inscritos</div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @else
                            <p class="text-red-500">El profesor seleccionado no existe.</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
