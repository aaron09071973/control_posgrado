<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Asignación Docente: ') }} {{ $horario->asignatura->nombre_asignatura }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('grupos.show', $horario->grupo) }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">Volver al Grupo</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100 overflow-x-auto">
                    <div class="mb-4">
                        <h3 class="text-lg font-bold">Detalles de la Programación</h3>
                        <p class="text-sm text-gray-500 mt-2"><strong>Grupo:</strong> {{ $horario->grupo->nombre_grupo }} ({{ $horario->grupo->semestre->posgrado->nombre_posgrado }})</p>
                        <p class="text-sm text-gray-500"><strong>Horario:</strong> {{ $horario->horario }}</p>
                        <p class="text-sm text-gray-500"><strong>Horas Lectivas:</strong> {{ $horario->horas_lectivas }}</p>
                        @if($horario->sesion_zoom)
                            <p class="text-sm text-gray-500"><strong>Zoom:</strong> <a href="{{ $horario->sesion_zoom }}" target="_blank" class="text-blue-500 underline">Enlace de sesión</a></p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Columna Profesores -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 overflow-x-auto">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">Profesores Asignados</h3>
                        @if(Auth::user()->role === 'administrador')
                        <x-primary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'assign-profesor')">
                            Asignar Docente
                        </x-primary-button>
                        @endif
                    </div>

                    @if($horario->profesores->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400 text-sm">No hay docentes asignados a este horario.</p>
                    @else
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($horario->profesores as $profesor)
                            <li class="py-3 flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $profesor->nombre }} {{ $profesor->apellido }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $profesor->correo_electronico }}</p>
                                </div>
                                @if(Auth::user()->role === 'administrador')
                                <form action="{{ route('asignaciones.desasignarProfesor', $profesor->pivot->id_asignacion_profesor) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 transition ease-in-out duration-150" onclick="return confirm('¿Remover profesor de la asignatura?')">Remover</button>
                                </form>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

        </div>
    </div>

    @if(Auth::user()->role === 'administrador')
    <!-- Asignar Docente Modal -->
    <x-modal name="assign-profesor" focusable>
        <form method="POST" action="{{ route('asignaciones.asignarProfesor') }}" class="p-6">
            @csrf
            <input type="hidden" name="id_horario_asignatura" value="{{ $horario->id_horario_asignatura }}">
            
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                Asignar Docente
            </h2>
            @if($profesores->isEmpty())
                <p class="text-sm text-red-500 mb-4">No hay profesores disponibles para asignar (todos están asignados o no hay profesores registrados).</p>
            @else
                <div class="mt-4">
                    <x-input-label for="id_profesor" value="Seleccionar Profesor" />
                    <select id="id_profesor" name="id_profesor" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                        <option value="">Seleccione...</option>
                        @foreach($profesores as $profesor)
                            <option value="{{ $profesor->id_profesor }}">{{ $profesor->nombre }} {{ $profesor->apellido }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                    <x-primary-button class="ml-3">Asignar</x-primary-button>
                </div>
            @endif
        </form>
    </x-modal>
    @endif
</x-app-layout>
