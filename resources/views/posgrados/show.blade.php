<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Posgrado: ') }} {{ $posgrado->nombre_posgrado }}
            </h2>
            <a href="{{ route('posgrados.index') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">Volver a Posgrados</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100 overflow-x-auto">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">Semestres del Posgrado</h3>
                        @if(Auth::user()->role === 'administrador')
                        <x-primary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-semestre')">
                            Agregar Semestre
                        </x-primary-button>
                        @endif
                    </div>

                    @if($posgrado->semestres->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400">No hay semestres registrados para este posgrado.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($posgrado->semestres as $semestre)
                                <div class="border dark:border-gray-700 rounded-lg p-4 flex justify-between flex-col">
                                    <div>
                                        <h4 class="text-xl font-bold text-indigo-600 dark:text-indigo-400">Semestre {{ $semestre->numero_semestre }}</h4>
                                        @if($semestre->desc_semestre)
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $semestre->desc_semestre }}</p>
                                        @endif
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Inicio: {{ $semestre->fecha_inicio->format('d/m/Y') }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Fin: {{ $semestre->fecha_termino->format('d/m/Y') }}</p>
                                    </div>
                                    <div class="mt-4 flex justify-between items-center border-t dark:border-gray-700 pt-4">
                                        <a href="{{ route('semestres.show', $semestre) }}" class="inline-flex items-center px-4 py-2 bg-[#1b2847] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#1b2847]/80 active:bg-[#1b2847]/90 focus:outline-none focus:ring-2 focus:ring-[#1b2847] focus:ring-offset-2 transition ease-in-out duration-150">Ver Grupos</a>
                                        @if(Auth::user()->role === 'administrador')
                                        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'edit-semestre-{{ $semestre->id_semestre }}')" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 text-sm">Editar</button>
                                        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-delete-semestre-{{ $semestre->id_semestre }}')" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 text-sm">Eliminar</button>

                                        <!-- Confirm Delete Semestre Modal -->
                                        <x-modal name="confirm-delete-semestre-{{ $semestre->id_semestre }}" focusable>
                                            <form method="POST" action="{{ route('semestres.destroy', $semestre) }}" class="p-6 text-left">
                                                @csrf
                                                @method('DELETE')
                                                
                                                <h2 class="text-lg font-bold text-red-600 dark:text-red-400 border-b pb-3 border-gray-150 dark:border-gray-700 flex items-center">
                                                    <svg class="w-6 h-6 mr-2 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                    </svg>
                                                    ¿Confirmar eliminación del semestre?
                                                </h2>
                                                
                                                <div class="mt-4 text-sm text-gray-600 dark:text-gray-400 space-y-3">
                                                    <p>
                                                        Estás a punto de eliminar el <strong>Semestre {{ $semestre->numero_semestre }}</strong> de forma permanente.
                                                    </p>
                                                    
                                                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 p-4 rounded-md">
                                                        <p class="font-semibold text-yellow-800 dark:text-yellow-400 mb-2">Resumen de registros asociados en este semestre:</p>
                                                        <ul class="list-disc list-inside space-y-1 text-gray-700 dark:text-gray-300">
                                                            <li>Grupos: <strong>{{ $semestre->grupos_count }}</strong></li>
                                                            <li>Alumnos inscritos: <strong>{{ $semestre->alumnos_count }}</strong></li>
                                                            <li>Asignaturas programadas: <strong>{{ $semestre->asignaturas_count }}</strong></li>
                                                            <li>Calificaciones capturadas: <strong>{{ $semestre->calificaciones_count }}</strong></li>
                                                            <li>Profesores asignados: <strong>{{ $semestre->profesores_count }}</strong></li>
                                                        </ul>
                                                    </div>
                                                    
                                                    <p class="font-semibold text-red-600 dark:text-red-400">
                                                        ⚠️ ¡ADVERTENCIA! Al confirmar la eliminación:
                                                    </p>
                                                    <ul class="list-disc list-inside text-xs space-y-1 text-red-700 dark:text-red-400">
                                                        <li>Se eliminarán permanentemente todos los grupos de este semestre.</li>
                                                        <li>Se desvincularán todos los alumnos y profesores de los grupos correspondientes.</li>
                                                        <li>Se eliminarán permanentemente todas las asignaturas programadas (horarios).</li>
                                                        <li>Se eliminarán permanentemente todas las calificaciones de los alumnos para las materias de este semestre.</li>
                                                        <li>Esta acción no se puede deshacer.</li>
                                                    </ul>
                                                </div>
                                                
                                                <div class="mt-6 flex justify-end">
                                                    <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                                                    <x-danger-button class="ml-3">
                                                        Eliminar Semestre y Datos Relacionados
                                                    </x-danger-button>
                                                </div>
                                            </form>
                                        </x-modal>

                                        <!-- Edit Semestre Modal -->
                                        <x-modal name="edit-semestre-{{ $semestre->id_semestre }}" focusable>
                                            <form method="POST" action="{{ route('semestres.update', $semestre) }}" class="p-6">
                                                @csrf
                                                @method('PUT')
                                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                                    Editar Semestre
                                                </h2>
                                                <div class="mt-4">
                                                    <x-input-label for="edit_numero_semestre_{{ $semestre->id_semestre }}" value="Número de Semestre" />
                                                    <x-text-input id="edit_numero_semestre_{{ $semestre->id_semestre }}" name="numero_semestre" type="text" class="mt-1 block w-full" :value="$semestre->numero_semestre" required />
                                                </div>
                                                <div class="mt-4">
                                                    <x-input-label for="edit_desc_semestre_{{ $semestre->id_semestre }}" value="Descripción (Opcional)" />
                                                    <x-text-input id="edit_desc_semestre_{{ $semestre->id_semestre }}" name="desc_semestre" type="text" class="mt-1 block w-full" :value="$semestre->desc_semestre" />
                                                </div>
                                                <div class="mt-4">
                                                    <x-input-label for="edit_fecha_inicio_{{ $semestre->id_semestre }}" value="Fecha de Inicio" />
                                                    <x-text-input id="edit_fecha_inicio_{{ $semestre->id_semestre }}" name="fecha_inicio" type="date" class="mt-1 block w-full" :value="$semestre->fecha_inicio->format('Y-m-d')" required />
                                                </div>
                                                <div class="mt-4">
                                                    <x-input-label for="edit_fecha_termino_{{ $semestre->id_semestre }}" value="Fecha de Término" />
                                                    <x-text-input id="edit_fecha_termino_{{ $semestre->id_semestre }}" name="fecha_termino" type="date" class="mt-1 block w-full" :value="$semestre->fecha_termino->format('Y-m-d')" required />
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
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(Auth::user()->role === 'administrador')
    <!-- Create Semestre Modal -->
    <x-modal name="create-semestre" focusable>
        <form method="POST" action="{{ route('semestres.store') }}" class="p-6">
            @csrf
            <input type="hidden" name="id_posgrado" value="{{ $posgrado->id_posgrado }}">
            
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Agregar Semestre
            </h2>
            <div class="mt-4">
                <x-input-label for="numero_semestre" value="Número de Semestre" />
                <x-text-input id="numero_semestre" name="numero_semestre" type="text" class="mt-1 block w-full" required />
            </div>
            <div class="mt-4">
                <x-input-label for="desc_semestre" value="Descripción (Opcional)" />
                <x-text-input id="desc_semestre" name="desc_semestre" type="text" class="mt-1 block w-full" placeholder="Ej: Semestre de Especialización" />
            </div>
            <div class="mt-4">
                <x-input-label for="fecha_inicio" value="Fecha de Inicio" />
                <x-text-input id="fecha_inicio" name="fecha_inicio" type="date" class="mt-1 block w-full" required />
            </div>
            <div class="mt-4">
                <x-input-label for="fecha_termino" value="Fecha de Término" />
                <x-text-input id="fecha_termino" name="fecha_termino" type="date" class="mt-1 block w-full" required />
            </div>
            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                <x-primary-button class="ml-3">Crear Semestre</x-primary-button>
            </div>
        </form>
    </x-modal>
    @endif
</x-app-layout>
