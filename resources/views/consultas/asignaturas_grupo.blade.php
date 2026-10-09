<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Consulta: Asignaturas por Grupo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8">
            
            <!-- Selector de Grupo -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="GET" action="{{ route('consultas.asignaturas') }}" class="flex items-end space-x-4">
                        <div class="flex-grow">
                            <x-input-label for="grupo_id" value="Selecciona un Grupo" />
                            <select id="grupo_id" name="grupo_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">-- Seleccionar --</option>
                                @foreach($grupos as $grupo)
                                    <option value="{{ $grupo->id_grupo }}" {{ request('grupo_id') == $grupo->id_grupo ? 'selected' : '' }}>
                                        {{ $grupo->nombre_grupo }} 
                                        (Semestre: {{ $grupo->semestre->numero_semestre }}, Posgrado: {{ $grupo->semestre->posgrado->nombre_posgrado }})
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
            @if(request()->has('grupo_id') && request('grupo_id') != '')
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        @if($grupoSeleccionado)
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                                    Asignaturas programadas para: {{ $grupoSeleccionado->nombre_grupo }} ({{ $grupoSeleccionado->semestre->posgrado->nombre_posgrado }})
                                </h3>
                                @if(!$horarios->isEmpty())
                                    <a href="{{ route('consultas.asignaturas.export', ['grupo_id' => $grupoSeleccionado->id_grupo]) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Exportar a Excel
                                    </a>
                                @endif
                            </div>
                            
                            @if($horarios->isEmpty())
                                <p class="text-gray-500">No hay asignaturas programadas para este grupo.</p>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Código</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Asignatura</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Horario</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Hrs Lectivas</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Profesor(es) Asignado(s)</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($horarios as $horario)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $horario->asignatura->codigo_asignatura ?? 'N/A' }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $horario->asignatura->nombre_asignatura }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $horario->horario }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $horario->horas_lectivas }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                        @if($horario->profesores->isNotEmpty())
                                                            {{ $horario->profesores->map(function($p) { return $p->nombre . ' ' . $p->apellido; })->implode(', ') }}
                                                        @else
                                                            <span class="text-red-500">Sin profesor asignado</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @else
                            <p class="text-red-500">El grupo seleccionado no existe.</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
