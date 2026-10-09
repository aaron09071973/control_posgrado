<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Posgrados') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8">
            <!-- Messages -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="mb-4 flex justify-end">
                @if(Auth::user()->role === 'administrador')
                <x-primary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-posgrado')">
                    Nuevo Posgrado
                </x-primary-button>
                @endif
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Código</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($posgrados as $posgrado)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $posgrado->id_posgrado }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $posgrado->nombre_posgrado }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $posgrado->codigo_programa }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('posgrados.show', $posgrado) }}" class="inline-flex items-center px-4 py-2 bg-[#1b2847] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#1b2847]/80 active:bg-[#1b2847]/90 focus:outline-none focus:ring-2 focus:ring-[#1b2847] focus:ring-offset-2 transition ease-in-out duration-150 mr-2">Ver/Semestres</a>
                                    
                                    @if(Auth::user()->role === 'administrador')
                                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'edit-posgrado-{{ $posgrado->id_posgrado }}')" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">Editar</button>
                                    
                                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-delete-posgrado-{{ $posgrado->id_posgrado }}')" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">Eliminar</button>

                                    <!-- Confirm Delete Posgrado Modal -->
                                    <x-modal name="confirm-delete-posgrado-{{ $posgrado->id_posgrado }}" focusable>
                                        <form method="POST" action="{{ route('posgrados.destroy', $posgrado) }}" class="p-6 text-left">
                                            @csrf
                                            @method('DELETE')
                                            
                                            <h2 class="text-lg font-bold text-red-600 dark:text-red-400 border-b pb-3 border-gray-150 dark:border-gray-700 flex items-center">
                                                <svg class="w-6 h-6 mr-2 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                </svg>
                                                ¿Confirmar eliminación del posgrado?
                                            </h2>
                                            
                                            <div class="mt-4 text-sm text-gray-600 dark:text-gray-400 space-y-3 font-normal">
                                                <p>
                                                    Estás a punto de eliminar el posgrado <strong>{{ $posgrado->nombre_posgrado }}</strong> de forma permanente.
                                                </p>
                                                
                                                <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 p-4 rounded-md">
                                                    <p class="font-semibold text-yellow-800 dark:text-yellow-400 mb-2">Resumen de registros asociados en este posgrado:</p>
                                                    <ul class="list-disc list-inside space-y-1 text-gray-700 dark:text-gray-300">
                                                        <li>Semestres: <strong>{{ $posgrado->semestres_count }}</strong></li>
                                                        <li>Grupos: <strong>{{ $posgrado->grupos_count }}</strong></li>
                                                        <li>Alumnos inscritos: <strong>{{ $posgrado->alumnos_count }}</strong></li>
                                                        <li>Calificaciones capturadas: <strong>{{ $posgrado->calificaciones_count }}</strong></li>
                                                        <li>Profesores asignados: <strong>{{ $posgrado->profesores_count }}</strong></li>
                                                    </ul>
                                                </div>
                                                
                                                <p class="font-semibold text-red-600 dark:text-red-400">
                                                    ⚠️ ¡ADVERTENCIA! Al confirmar la eliminación:
                                                </p>
                                                <ul class="list-disc list-inside text-xs space-y-1 text-red-700 dark:text-red-400">
                                                    <li>Se eliminarán permanentemente todos los semestres y grupos de este posgrado.</li>
                                                    <li>Se desvincularán todos los alumnos y profesores de los grupos correspondientes.</li>
                                                    <li>Se eliminarán permanentemente todas las asignaturas programadas (horarios) de este posgrado.</li>
                                                    <li>Se eliminarán permanentemente todas las calificaciones de los alumnos en este posgrado.</li>
                                                    <li>Esta acción no se puede deshacer.</li>
                                                </ul>
                                            </div>
                                            
                                            <div class="mt-6 flex justify-end">
                                                <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                                                <x-danger-button class="ml-3">
                                                    Eliminar Posgrado y Datos Relacionados
                                                </x-danger-button>
                                            </div>
                                        </form>
                                    </x-modal>

                                    <!-- Edit Modal -->
                                    <x-modal name="edit-posgrado-{{ $posgrado->id_posgrado }}" focusable>
                                        <form method="POST" action="{{ route('posgrados.update', $posgrado) }}" class="p-6">
                                            @csrf
                                            @method('PUT')
                                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                                Editar Posgrado
                                            </h2>
                                            <div class="mt-4 text-left">
                                                <x-input-label for="nombre_posgrado" value="Nombre del Posgrado" />
                                                <x-text-input id="nombre_posgrado" name="nombre_posgrado" type="text" class="mt-1 block w-full" :value="$posgrado->nombre_posgrado" required />
                                            </div>
                                            <div class="mt-4 text-left">
                                                <x-input-label for="codigo_programa" value="Código" />
                                                <x-text-input id="codigo_programa" name="codigo_programa" type="text" class="mt-1 block w-full" :value="$posgrado->codigo_programa" required />
                                            </div>
                                            <div class="mt-6 flex justify-end">
                                                <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                                                <x-primary-button class="ml-3">Guardar</x-primary-button>
                                            </div>
                                        </form>
                                    </x-modal>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if(Auth::user()->role === 'administrador')
    <!-- Create Modal -->
    <x-modal name="create-posgrado" focusable>
        <form method="POST" action="{{ route('posgrados.store') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Nuevo Posgrado
            </h2>
            <div class="mt-4">
                <x-input-label for="nombre_posgrado" value="Nombre del Posgrado" />
                <x-text-input id="nombre_posgrado" name="nombre_posgrado" type="text" class="mt-1 block w-full" required />
            </div>
            <div class="mt-4">
                <x-input-label for="codigo_programa" value="Código" />
                <x-text-input id="codigo_programa" name="codigo_programa" type="text" class="mt-1 block w-full" required />
            </div>
            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                <x-primary-button class="ml-3">Crear</x-primary-button>
            </div>
        </form>
    </x-modal>
    @endif
</x-app-layout>
