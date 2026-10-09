<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Grupo: ') }} {{ $grupo->nombre_grupo }} (Semestre {{ $grupo->semestre->numero_semestre }}, {{ $grupo->semestre->posgrado->nombre_posgrado }})
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('semestres.show', $grupo->semestre) }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">Volver al Semestre</a>
                @if(Auth::user()->role === 'administrador')
                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'edit-grupo')" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 text-sm font-medium">
                    Editar Grupo
                </button>
                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-delete-grupo')" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 text-sm font-medium">
                    Eliminar Grupo
                </button>

                <!-- Warning Confirmation Modal -->
                <x-modal name="confirm-delete-grupo" focusable>
                    <form method="POST" action="{{ route('grupos.destroy', $grupo) }}" class="p-6 text-left">
                        @csrf
                        @method('DELETE')
                        
                        <h2 class="text-lg font-bold text-red-600 dark:text-red-400 border-b pb-3 border-gray-150 dark:border-gray-700 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            ¿Confirmar eliminación del grupo?
                        </h2>
                        
                        <div class="mt-4 text-sm text-gray-600 dark:text-gray-400 space-y-3">
                            <p>
                                Estás a punto de eliminar el grupo <strong>"{{ $grupo->nombre_grupo }}"</strong> de forma permanente.
                            </p>
                            
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 p-4 rounded-md">
                                <p class="font-semibold text-yellow-800 dark:text-yellow-400 mb-2">Resumen de registros asociados en este grupo:</p>
                                <ul class="list-disc list-inside space-y-1 text-gray-700 dark:text-gray-300">
                                    <li>Alumnos inscritos: <strong>{{ $alumnosCount }}</strong></li>
                                    <li>Asignaturas programadas: <strong>{{ $asignaturasCount }}</strong></li>
                                    <li>Calificaciones capturadas: <strong>{{ $calificacionesCount }}</strong></li>
                                </ul>
                            </div>
                            
                            <p class="font-semibold text-red-600 dark:text-red-400">
                                ⚠️ ¡ADVERTENCIA! Al confirmar la eliminación:
                            </p>
                            <ul class="list-disc list-inside text-xs space-y-1 text-red-700 dark:text-red-400">
                                <li>Se desvincularán automáticamente todos los alumnos de este grupo.</li>
                                <li>Se eliminarán permanentemente todas las asignaturas programadas (horarios) en este grupo.</li>
                                <li>Se eliminarán permanentemente todas las calificaciones registradas para las asignaturas de este grupo.</li>
                                <li>Esta acción no se puede deshacer.</li>
                            </ul>
                        </div>
                        
                        <div class="mt-6 flex justify-end">
                            <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                            <x-danger-button class="ml-3">
                                Eliminar Todo y Regresar
                            </x-danger-button>
                        </div>
                    </form>
                </x-modal>

                <!-- Edit Grupo Modal -->
                <x-modal name="edit-grupo" focusable>
                    <form method="POST" action="{{ route('grupos.update', $grupo) }}" class="p-6 text-left">
                        @csrf
                        @method('PUT')
                        
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b pb-3 border-gray-150 dark:border-gray-700">
                            Editar Grupo
                        </h2>
                        
                        <div class="mt-4">
                            <x-input-label for="edit_nombre_grupo" value="Nombre del Grupo" />
                            <x-text-input id="edit_nombre_grupo" name="nombre_grupo" type="text" class="mt-1 block w-full" :value="$grupo->nombre_grupo" required />
                        </div>
                        
                        <div class="mt-4">
                            <x-input-label for="edit_periodo_intersemestral" value="Periodo Intersemestral" />
                            <x-text-input id="edit_periodo_intersemestral" name="periodo_intersemestral" type="text" maxlength="100" class="mt-1 block w-full" :value="$grupo->periodo_intersemestral" />
                        </div>
                        
                        <div class="mt-6 flex justify-end">
                            <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                            <x-primary-button class="ml-3">Guardar Cambios</x-primary-button>
                        </div>
                    </form>
                </x-modal>
                @endif
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

            <!-- Información del Grupo -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-2">Información Académica</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <strong>Periodo Intersemestral:</strong> 
                        {{ $grupo->periodo_intersemestral ?: 'No asignado' }}
                    </p>
                </div>
            </div>

            <!-- Grid de 2 Columnas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Columna Alumnos -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 overflow-x-auto">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium">Alumnos Inscritos</h3>
                            @if(Auth::user()->role === 'administrador')
                            <x-primary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'assign-alumno')">
                                Inscribir Alumno
                            </x-primary-button>
                            @endif
                        </div>

                        @if($grupo->alumnos->isEmpty())
                            <p class="text-gray-500 dark:text-gray-400 text-sm">No hay alumnos inscritos en este grupo.</p>
                        @else
                            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($grupo->alumnos as $alumno)
                                <li class="py-3 flex justify-between items-center">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $alumno->nombre }} {{ $alumno->apellido }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $alumno->correo_electronico }}</p>
                                    </div>
                                    
                                    <div class="flex items-center space-x-4">
                                        @if($alumno->is_finalizado)
                                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                Finalizado
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                                En Curso
                                            </span>
                                        @endif

                                         @if(Auth::user()->role === 'administrador')
                                            @if($alumno->has_calificaciones_in_group || $alumno->is_finalizado)
                                                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-remove-alumno-{{ $alumno->id_alumno }}')" class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 transition ease-in-out duration-150 shadow-sm">
                                                    Remover
                                                </button>

                                                <!-- Confirm Remove Student Modal -->
                                                <x-modal name="confirm-remove-alumno-{{ $alumno->id_alumno }}" focusable>
                                                    <form method="POST" action="{{ route('asignaciones.desasignarAlumno', $alumno->pivot->id_alumno_grupo) }}" class="p-6 text-left">
                                                        @csrf
                                                        @method('DELETE')
                                                        
                                                        <h2 class="text-lg font-bold text-red-600 dark:text-red-400 border-b pb-3 border-gray-150 dark:border-gray-700 flex items-center">
                                                            <svg class="w-6 h-6 mr-2 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                            </svg>
                                                            ¿Confirmar remoción de alumno del grupo?
                                                        </h2>
                                                        
                                                        <div class="mt-4 text-sm text-gray-600 dark:text-gray-400 space-y-3">
                                                            <p>
                                                                El alumno <strong>"{{ $alumno->nombre }} {{ $alumno->apellido }}"</strong> ya tiene calificaciones registradas en este grupo.
                                                            </p>
                                                            <p class="font-semibold text-red-600 dark:text-red-400">
                                                                ⚠️ ¡ADVERTENCIA! Al confirmar la remoción:
                                                            </p>
                                                            <ul class="list-disc list-inside text-xs space-y-1 text-red-700 dark:text-red-400">
                                                                <li>Se desvinculará al alumno de este grupo.</li>
                                                                <li>Se eliminarán permanentemente todas las calificaciones que tenga registradas en las asignaturas de este grupo.</li>
                                                                <li>Esta acción no se puede deshacer.</li>
                                                            </ul>
                                                        </div>
                                                        
                                                        <div class="mt-6 flex justify-end">
                                                            <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                                                            <x-danger-button class="ml-3">
                                                                Remover y Eliminar Calificaciones
                                                            </x-danger-button>
                                                        </div>
                                                    </form>
                                                </x-modal>
                                            @else
                                                <form action="{{ route('asignaciones.desasignarAlumno', $alumno->pivot->id_alumno_grupo) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 transition ease-in-out duration-150 shadow-sm" onclick="return confirm('¿Remover alumno del grupo?')">Remover</button>
                                                </form>
                                            @endif
                                         @endif
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <!-- Columna Asignaturas/Horarios -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 overflow-x-auto">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium">Horarios / Asignaturas</h3>
                            @if(Auth::user()->role === 'administrador')
                            <x-primary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-horario')">
                                Programar Asignatura
                            </x-primary-button>
                            @endif
                        </div>

                        @if($grupo->horariosAsignaturas->isEmpty())
                            <p class="text-gray-500 dark:text-gray-400 text-sm">No hay asignaturas programadas.</p>
                        @else
                            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($grupo->horariosAsignaturas as $horario)
                                    @php
                                        $alumnoIdsInGroup = $grupo->alumnos->pluck('id_alumno')->toArray();
                                        $hasCalificaciones = \App\Models\Calificacion::where('id_asignatura', $horario->id_asignatura)
                                            ->whereIn('id_alumno', $alumnoIdsInGroup)
                                            ->exists();
                                    @endphp
                                <li class="py-3">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $horario->asignatura->nombre_asignatura }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Horario: {{ $horario->horario }} ({{ $horario->horas_lectivas }}h)</p>
                                            @if($horario->profesores->isNotEmpty())
                                                <p class="text-xs text-gray-600 dark:text-gray-300 mt-1">
                                                    <strong>Profesor(es):</strong> 
                                                    {{ $horario->profesores->map(function($p) { return $p->nombre . ' ' . $p->apellido; })->implode(', ') }}
                                                </p>
                                            @else
                                                <p class="text-xs text-red-500 mt-1">Sin profesor asignado</p>
                                            @endif
                                        </div>
                                        <div class="flex space-x-3">
                                            <a href="{{ route('horarios.show', $horario) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 transition ease-in-out duration-150">Gestionar Docentes</a>
                                            @if(Auth::user()->role === 'administrador')
                                                @if($hasCalificaciones)
                                                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-delete-horario-{{ $horario->id_horario_asignatura }}')" class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 transition ease-in-out duration-150 shadow-sm">
                                                        Eliminar
                                                    </button>

                                                    <!-- Confirm Delete Horario Modal -->
                                                    <x-modal name="confirm-delete-horario-{{ $horario->id_horario_asignatura }}" focusable>
                                                        <form method="POST" action="{{ route('horarios.destroy', $horario) }}" class="p-6 text-left">
                                                            @csrf
                                                            @method('DELETE')
                                                            
                                                            <h2 class="text-lg font-bold text-red-600 dark:text-red-400 border-b pb-3 border-gray-150 dark:border-gray-700 flex items-center">
                                                                <svg class="w-6 h-6 mr-2 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                                </svg>
                                                                ¿Confirmar eliminación de asignatura del grupo?
                                                            </h2>
                                                            
                                                            <div class="mt-4 text-sm text-gray-600 dark:text-gray-400 space-y-3">
                                                                <p>
                                                                    La asignatura <strong>"{{ $horario->asignatura->nombre_asignatura }}"</strong> tiene calificaciones registradas para los alumnos de este grupo.
                                                                </p>
                                                                <p class="font-semibold text-red-600 dark:text-red-400">
                                                                    ⚠️ ¡ADVERTENCIA! Al confirmar la eliminación:
                                                                </p>
                                                                <ul class="list-disc list-inside text-xs space-y-1 text-red-700 dark:text-red-400">
                                                                    <li>Se removerá la asignatura de este grupo.</li>
                                                                    <li>Se eliminarán permanentemente todas las calificaciones registradas en esta asignatura para los alumnos de este grupo.</li>
                                                                    <li>Se eliminarán las asignaciones de docentes correspondientes.</li>
                                                                    <li>Esta acción no se puede deshacer.</li>
                                                                </ul>
                                                            </div>
                                                            
                                                            <div class="mt-6 flex justify-end">
                                                                <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                                                                <x-danger-button class="ml-3">
                                                                    Eliminar Asignatura y Calificaciones
                                                                </x-danger-button>
                                                            </div>
                                                        </form>
                                                    </x-modal>
                                                @else
                                                    <form action="{{ route('horarios.destroy', $horario) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 transition ease-in-out duration-150" onclick="return confirm('¿Eliminar asignatura del grupo?')">Eliminar</button>
                                                    </form>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    @if($horario->sesion_zoom)
                                        <p class="text-xs text-blue-500 mt-1"><a href="{{ $horario->sesion_zoom }}" target="_blank">Enlace Zoom</a></p>
                                    @endif
                                </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    @if(Auth::user()->role === 'administrador')
    <!-- Inscribir Alumno Modal -->
    <x-modal name="assign-alumno" focusable>
        <form method="POST" action="{{ route('asignaciones.asignarAlumno') }}" class="p-6">
            @csrf
            <input type="hidden" name="id_grupo" value="{{ $grupo->id_grupo }}">
            
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                Inscribir Alumno
            </h2>
            @if($alumnos->isEmpty())
                <p class="text-sm text-red-500 mb-4">No hay alumnos disponibles para inscribir (todos están en el grupo o no hay alumnos registrados).</p>
            @else
                <div class="mt-4">
                    <x-input-label for="id_alumno" value="Seleccionar Alumno" />
                    <select id="id_alumno" name="id_alumno" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                        <option value="">Seleccione...</option>
                        @foreach($alumnos as $alumno)
                            <option value="{{ $alumno->id_alumno }}">{{ $alumno->nombre }} {{ $alumno->apellido }} ({{ $alumno->generacion }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                    <x-primary-button class="ml-3">Inscribir</x-primary-button>
                </div>
            @endif
        </form>
    </x-modal>

    <!-- Programar Asignatura Modal -->
    <x-modal name="create-horario" focusable>
        <form method="POST" action="{{ route('horarios.store') }}" class="p-6">
            @csrf
            <input type="hidden" name="id_grupo" value="{{ $grupo->id_grupo }}">
            
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                Programar Asignatura
            </h2>
            @if($asignaturas->isEmpty())
                <p class="text-sm text-red-500 mb-4">No hay asignaturas en el catálogo. Por favor crea asignaturas primero.</p>
            @else
                <div class="mt-4">
                    <x-input-label for="id_asignatura" value="Asignatura" />
                    <select id="id_asignatura" name="id_asignatura" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required onchange="updateHorarioInfo(this)">
                        <option value="">Seleccione...</option>
                        @foreach($asignaturas as $asignatura)
                            <option value="{{ $asignatura->id_asignatura }}" data-horario="{{ $asignatura->Horario }}" data-hrs="{{ $asignatura->hrslectivas }}">{{ $asignatura->nombre_asignatura }} ({{ $asignatura->codigo_asignatura }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-4">
                    <x-input-label for="horario" value="Horario (Ej: Sábados 08:00 - 12:00)" />
                    <x-text-input id="horario" name="horario" type="text" class="mt-1 block w-full" required />
                </div>
                <div class="mt-4">
                    <x-input-label for="horas_lectivas" value="Horas Lectivas" />
                    <x-text-input id="horas_lectivas" name="horas_lectivas" type="number" min="1" class="mt-1 block w-full" required />
                </div>
                <div class="mt-4">
                    <x-input-label for="sesion_zoom" value="Enlace de Zoom (Opcional)" />
                    <x-text-input id="sesion_zoom" name="sesion_zoom" type="text" class="mt-1 block w-full" />
                </div>
                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                    <x-primary-button class="ml-3">Guardar Horario</x-primary-button>
                </div>
                
                <script>
                    function updateHorarioInfo(select) {
                        const selectedOption = select.options[select.selectedIndex];
                        const horarioInput = document.getElementById('horario');
                        const horasInput = document.getElementById('horas_lectivas');
                        
                        if (selectedOption && selectedOption.value !== "") {
                            horarioInput.value = selectedOption.getAttribute('data-horario') || '';
                            horasInput.value = selectedOption.getAttribute('data-hrs') || '';
                        } else {
                            horarioInput.value = '';
                            horasInput.value = '';
                        }
                    }
                </script>
            @endif
        </form>
    </x-modal>
    @endif
</x-app-layout>
