<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Consulta: Profesores por Asignatura') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8">
            
            <!-- Selector de Asignatura -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="GET" action="{{ route('consultas.profesores') }}" class="flex items-end space-x-4">
                        <div class="flex-grow">
                            <x-input-label for="horario_id" value="Selecciona una Asignatura/Grupo impartido" />
                            <select id="horario_id" name="horario_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">-- Seleccionar --</option>
                                @foreach($horarios as $horario)
                                    <option value="{{ $horario->id_horario_asignatura }}" {{ request('horario_id') == $horario->id_horario_asignatura ? 'selected' : '' }}>
                                        {{ $horario->asignatura->nombre_asignatura }} 
                                        [Grupo: {{ $horario->grupo->nombre_grupo }}] 
                                        ({{ $horario->grupo->semestre->posgrado->nombre_posgrado }})
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
            @if(request()->has('horario_id') && request('horario_id') != '')
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        @if($horarioSeleccionado)
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                                    Profesores asignados a: {{ $horarioSeleccionado->asignatura->nombre_asignatura }} (Grupo {{ $horarioSeleccionado->grupo->nombre_grupo }})
                                </h3>
                                @if(!$profesores->isEmpty())
                                    <a href="{{ route('consultas.profesores.export', ['horario_id' => $horarioSeleccionado->id_horario_asignatura]) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Exportar a Excel
                                    </a>
                                @endif
                            </div>
                            
                            @if($profesores->isEmpty())
                                <p class="text-gray-500">No hay profesores asignados a esta asignatura en este grupo.</p>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre Completo</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Correo Electrónico</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Teléfono</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha Asignación</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($profesores as $profesor)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $profesor->nombre }} {{ $profesor->apellido }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $profesor->correo_electronico }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $profesor->telefono ?? 'N/A' }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                        @if(isset($profesor->pivot->fecha_asignacion))
                                                            {{ \Carbon\Carbon::parse($profesor->pivot->fecha_asignacion)->format('d/m/Y') }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @else
                            <p class="text-red-500">La asignatura o grupo seleccionado no existe.</p>
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
