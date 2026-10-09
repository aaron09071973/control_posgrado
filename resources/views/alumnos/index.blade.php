<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Alumnos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="mb-4 flex justify-end">
                @if(Auth::user()->role === 'administrador')
                <x-primary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-alumno')">
                    Nuevo Alumno
                </x-primary-button>
                @endif
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cuenta</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Correo Electrónico</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Generación</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($alumnos as $alumno)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-mono">{{ $alumno->Cta_alumno }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $alumno->nombre }} {{ $alumno->apellido }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $alumno->correo_electronico }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $alumno->generacion }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($alumno->is_finalizado)
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                            Finalizado
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                            En Curso
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'view-alumno-{{ $alumno->id_alumno }}')" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">Ver</button>

                                    <!-- View Modal -->
                                    <x-modal name="view-alumno-{{ $alumno->id_alumno }}" focusable>
                                        <div class="p-6">
                                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 text-left">
                                                Detalles del Alumno
                                            </h2>
                                            <div class="mt-4 text-left">
                                                <x-input-label value="Cuenta / Matrícula" />
                                                <x-text-input type="text" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700" :value="$alumno->Cta_alumno" disabled />
                                            </div>
                                            <div class="mt-4 text-left">
                                                <x-input-label value="Nombre" />
                                                <x-text-input type="text" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700" :value="$alumno->nombre" disabled />
                                            </div>
                                            <div class="mt-4 text-left">
                                                <x-input-label value="Apellido" />
                                                <x-text-input type="text" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700" :value="$alumno->apellido" disabled />
                                            </div>
                                            <div class="mt-4 text-left">
                                                <x-input-label value="Correo Electrónico" />
                                                <x-text-input type="email" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700" :value="$alumno->correo_electronico" disabled />
                                            </div>
                                            <div class="mt-4 text-left">
                                                <x-input-label value="Generación" />
                                                <x-text-input type="text" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700" :value="$alumno->generacion" disabled />
                                            </div>
                                            <div class="mt-6 flex justify-end">
                                                <x-secondary-button x-on:click="$dispatch('close')">Cerrar</x-secondary-button>
                                            </div>
                                        </div>
                                    </x-modal>

                                    @if(Auth::user()->role === 'administrador')
                                    <a href="{{ route('alumnos.constancia', $alumno) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">Constancia</a>
                                    
                                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'edit-alumno-{{ $alumno->id_alumno }}')" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">Editar</button>
                                    
                                    @if($alumno->has_calificaciones)
                                        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-delete-finalizado-{{ $alumno->id_alumno }}')" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2 shadow-sm">
                                            Eliminar
                                        </button>

                                        <!-- Finalizado Student Delete Confirmation Modal -->
                                        <x-modal name="confirm-delete-finalizado-{{ $alumno->id_alumno }}" focusable>
                                            <form method="POST" action="{{ route('alumnos.destroy', $alumno) }}" class="p-6 text-left">
                                                @csrf
                                                @method('DELETE')
                                                
                                                <h2 class="text-lg font-bold text-red-600 dark:text-red-400 border-b pb-3 border-gray-150 dark:border-gray-700 flex items-center">
                                                    <svg class="w-6 h-6 mr-2 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                    </svg>
                                                    ¿Confirmar eliminación de alumno?
                                                </h2>
                                                
                                                <div class="mt-4 text-sm text-gray-600 dark:text-gray-400 space-y-3">
                                                    <p>
                                                        El alumno <strong>"{{ $alumno->nombre }} {{ $alumno->apellido }}"</strong> ya tiene calificaciones registradas en el sistema.
                                                    </p>
                                                    <p class="font-semibold text-red-600 dark:text-red-400">
                                                        ⚠️ ¡ADVERTENCIA! Al confirmar la eliminación:
                                                    </p>
                                                    <ul class="list-disc list-inside text-xs space-y-1 text-red-700 dark:text-red-400">
                                                        <li>Se eliminará de forma permanente el registro de este alumno.</li>
                                                        <li>Se eliminarán permanentemente todas las calificaciones que tenga asignadas este alumno.</li>
                                                        <li>Esta acción no se puede deshacer.</li>
                                                    </ul>
                                                </div>
                                                
                                                <div class="mt-6 flex justify-end">
                                                    <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                                                    <x-danger-button class="ml-3">
                                                        Eliminar Registro y Calificaciones
                                                    </x-danger-button>
                                                </div>
                                            </form>
                                        </x-modal>
                                    @else
                                        <form action="{{ route('alumnos.destroy', $alumno) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150" onclick="return confirm('¿Seguro que deseas eliminar este alumno?')">Eliminar</button>
                                        </form>
                                    @endif

                                    <!-- Edit Modal -->
                                    <x-modal name="edit-alumno-{{ $alumno->id_alumno }}" focusable>
                                        <form method="POST" action="{{ route('alumnos.update', $alumno) }}" class="p-6">
                                            @csrf
                                            @method('PUT')
                                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 text-left">
                                                Editar Alumno
                                            </h2>
                                            <div class="mt-4 text-left">
                                                <x-input-label for="Cta_alumno_{{ $alumno->id_alumno }}" value="Cuenta / Matrícula" />
                                                <x-text-input id="Cta_alumno_{{ $alumno->id_alumno }}" name="Cta_alumno" type="text" class="mt-1 block w-full" :value="$alumno->Cta_alumno" required />
                                            </div>
                                            <div class="mt-4 text-left">
                                                <x-input-label for="nombre_{{ $alumno->id_alumno }}" value="Nombre" />
                                                <x-text-input id="nombre_{{ $alumno->id_alumno }}" name="nombre" type="text" class="mt-1 block w-full" :value="$alumno->nombre" required />
                                            </div>
                                            <div class="mt-4 text-left">
                                                <x-input-label for="apellido_{{ $alumno->id_alumno }}" value="Apellido" />
                                                <x-text-input id="apellido_{{ $alumno->id_alumno }}" name="apellido" type="text" class="mt-1 block w-full" :value="$alumno->apellido" required />
                                            </div>
                                            <div class="mt-4 text-left">
                                                <x-input-label for="correo_electronico_{{ $alumno->id_alumno }}" value="Correo Electrónico" />
                                                <x-text-input id="correo_electronico_{{ $alumno->id_alumno }}" name="correo_electronico" type="email" class="mt-1 block w-full" :value="$alumno->correo_electronico" required />
                                            </div>
                                            <div class="mt-4 text-left">
                                                <x-input-label for="generacion_{{ $alumno->id_alumno }}" value="Generación" />
                                                <x-text-input id="generacion_{{ $alumno->id_alumno }}" name="generacion" type="text" class="mt-1 block w-full" :value="$alumno->generacion" required />
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
    <x-modal name="create-alumno" focusable>
        <form method="POST" action="{{ route('alumnos.store') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Nuevo Alumno
            </h2>
            <div class="mt-4">
                <x-input-label for="Cta_alumno" value="Cuenta / Matrícula" />
                <x-text-input id="Cta_alumno" name="Cta_alumno" type="text" class="mt-1 block w-full" required />
            </div>
            <div class="mt-4">
                <x-input-label for="nombre" value="Nombre" />
                <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full" required />
            </div>
            <div class="mt-4">
                <x-input-label for="apellido" value="Apellido" />
                <x-text-input id="apellido" name="apellido" type="text" class="mt-1 block w-full" required />
            </div>
            <div class="mt-4">
                <x-input-label for="correo_electronico" value="Correo Electrónico" />
                <x-text-input id="correo_electronico" name="correo_electronico" type="email" class="mt-1 block w-full" required />
            </div>
            <div class="mt-4">
                <x-input-label for="generacion" value="Generación" />
                <x-text-input id="generacion" name="generacion" type="text" class="mt-1 block w-full" required />
            </div>
            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                <x-primary-button class="ml-3">Crear</x-primary-button>
            </div>
        </form>
    </x-modal>
    @endif
</x-app-layout>
