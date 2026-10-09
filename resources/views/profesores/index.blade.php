<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profesores') }}
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
                <x-primary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-profesor')">
                    Nuevo Profesor
                </x-primary-button>
                @endif
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Código</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Correo Electrónico</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Teléfono</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Adscripción</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($profesores as $profesor)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $profesor->cod_profesor }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $profesor->nombre }} {{ $profesor->apellido }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $profesor->correo_electronico }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $profesor->telefono }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $profesor->adscripcion === 'CEPHCIS' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $profesor->adscripcion }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium whitespace-nowrap">
                                    <div class="flex flex-nowrap gap-2 justify-end">
                                        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'view-profesor-{{ $profesor->id_profesor }}')" class="inline-flex items-center px-2 py-1 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">Ver</button>

                                    <!-- View Modal -->
                                    <x-modal name="view-profesor-{{ $profesor->id_profesor }}" focusable>
                                        <div class="p-6">
                                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 text-left">
                                                Detalles del Profesor
                                            </h2>
                                            <div class="mt-4 text-left">
                                                <x-input-label value="Código del Profesor" />
                                                <x-text-input type="text" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700" :value="$profesor->cod_profesor" disabled />
                                            </div>
                                            <div class="mt-4 text-left">
                                                <x-input-label value="Nombre" />
                                                <x-text-input type="text" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700" :value="$profesor->nombre" disabled />
                                            </div>
                                            <div class="mt-4 text-left">
                                                <x-input-label value="Apellido" />
                                                <x-text-input type="text" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700" :value="$profesor->apellido" disabled />
                                            </div>
                                            <div class="mt-4 text-left">
                                                <x-input-label value="Correo Electrónico" />
                                                <x-text-input type="email" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700" :value="$profesor->correo_electronico" disabled />
                                            </div>
                                            <div class="mt-4 text-left">
                                                <x-input-label value="Teléfono" />
                                                <x-text-input type="text" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700" :value="$profesor->telefono" disabled />
                                            </div>
                                            <div class="mt-4 text-left">
                                                <x-input-label value="Adscripción" />
                                                <x-text-input type="text" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700" :value="$profesor->adscripcion" disabled />
                                            </div>
                                            <div class="mt-6 flex justify-end">
                                                <x-secondary-button x-on:click="$dispatch('close')">Cerrar</x-secondary-button>
                                            </div>
                                        </div>
                                    </x-modal>

                                    @if(Auth::user()->role === 'administrador')
                                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'constancia-profesor-{{ $profesor->id_profesor }}')" class="inline-flex items-center px-2 py-1 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">Constancia</button>

                                    <!-- Constancia Modal -->
                                    <x-modal name="constancia-profesor-{{ $profesor->id_profesor }}" focusable>
                                        <form method="GET" action="{{ route('profesores.constancia', $profesor) }}" target="_blank" class="p-6 text-left">
                                            <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 border-b pb-3 border-gray-150 dark:border-gray-700">
                                                Generar Constancia de Asignación Docente
                                            </h2>
                                            
                                            <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                                                Seleccione la asignatura asignada para la cual desea generar la constancia de el/la profesor(a) <strong>{{ $profesor->nombre }} {{ $profesor->apellido }}</strong>:
                                            </p>

                                            <div class="mt-4">
                                                <x-input-label for="id_horario_{{ $profesor->id_profesor }}" value="Asignatura y Grupo" />
                                                <select id="id_horario_{{ $profesor->id_profesor }}" name="id_horario" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                                    @forelse($profesor->horariosAsignaturas as $horario)
                                                        <option value="{{ $horario->id_horario_asignatura }}">
                                                            {{ $horario->asignatura->nombre_asignatura }} ({{ $horario->asignatura->codigo_asignatura }}) - Grupo: {{ $horario->grupo->nombre_grupo }} [{{ $horario->grupo->semestre->desc_semestre }}]
                                                        </option>
                                                    @empty
                                                        <option value="" disabled>El profesor no tiene asignaturas asignadas</option>
                                                    @endforelse
                                                </select>
                                            </div>

                                            <div class="mt-6 flex justify-end">
                                                <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                                                @if($profesor->horariosAsignaturas->isNotEmpty())
                                                    <x-primary-button class="ml-3 bg-green-600 hover:bg-green-500 active:bg-green-700">
                                                        Generar PDF
                                                    </x-primary-button>
                                                @endif
                                            </div>
                                        </form>
                                    </x-modal>
                                    
                                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'edit-profesor-{{ $profesor->id_profesor }}')" class="inline-flex items-center px-2 py-1 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">Editar</button>
                                    
                                    <form action="{{ route('profesores.destroy', $profesor) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-2 py-1 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150" onclick="return confirm('¿Seguro que deseas eliminar este profesor?')">Eliminar</button>
                                    </form>

                                    <!-- Edit Modal -->
                                    <x-modal name="edit-profesor-{{ $profesor->id_profesor }}" focusable>
                                        <form method="POST" action="{{ route('profesores.update', $profesor) }}" class="p-6">
                                            @csrf
                                            @method('PUT')
                                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 text-left">
                                                Editar Profesor
                                            </h2>
                                            <div class="mt-4 text-left">
                                                <x-input-label for="cod_profesor_{{ $profesor->id_profesor }}" value="Código del Profesor" />
                                                <x-text-input id="cod_profesor_{{ $profesor->id_profesor }}" name="cod_profesor" type="text" class="mt-1 block w-full" :value="$profesor->cod_profesor" required maxlength="30" />
                                            </div>
                                            <div class="mt-4 text-left">
                                                <x-input-label for="nombre_{{ $profesor->id_profesor }}" value="Nombre" />
                                                <x-text-input id="nombre_{{ $profesor->id_profesor }}" name="nombre" type="text" class="mt-1 block w-full" :value="$profesor->nombre" required />
                                            </div>
                                            <div class="mt-4 text-left">
                                                <x-input-label for="apellido_{{ $profesor->id_profesor }}" value="Apellido" />
                                                <x-text-input id="apellido_{{ $profesor->id_profesor }}" name="apellido" type="text" class="mt-1 block w-full" :value="$profesor->apellido" required />
                                            </div>
                                            <div class="mt-4 text-left">
                                                <x-input-label for="correo_electronico_{{ $profesor->id_profesor }}" value="Correo Electrónico" />
                                                <x-text-input id="correo_electronico_{{ $profesor->id_profesor }}" name="correo_electronico" type="email" class="mt-1 block w-full" :value="$profesor->correo_electronico" required />
                                            </div>
                                            <div class="mt-4 text-left">
                                                <x-input-label for="telefono_{{ $profesor->id_profesor }}" value="Teléfono" />
                                                <x-text-input id="telefono_{{ $profesor->id_profesor }}" name="telefono" type="text" class="mt-1 block w-full" :value="$profesor->telefono" />
                                            </div>
                                            <div class="mt-4 text-left">
                                                <x-input-label for="adscripcion_{{ $profesor->id_profesor }}" value="Adscripción" />
                                                <select id="adscripcion_{{ $profesor->id_profesor }}" name="adscripcion" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                                    <option value="CEPHCIS" {{ $profesor->adscripcion === 'CEPHCIS' ? 'selected' : '' }}>CEPHCIS</option>
                                                    <option value="Externo" {{ $profesor->adscripcion === 'Externo' ? 'selected' : '' }}>Externo</option>
                                                </select>
                                            </div>
                                            <div class="mt-6 flex justify-end">
                                                <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                                                <x-primary-button class="ml-3">Guardar</x-primary-button>
                                            </div>
                                        </form>
                                    </x-modal>
                                    @endif
                                    </div>
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
    <x-modal name="create-profesor" focusable>
        <form method="POST" action="{{ route('profesores.store') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Nuevo Profesor
            </h2>
            <div class="mt-4">
                <x-input-label for="cod_profesor" value="Código del Profesor" />
                <x-text-input id="cod_profesor" name="cod_profesor" type="text" class="mt-1 block w-full" required maxlength="30" />
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
                <x-input-label for="telefono" value="Teléfono" />
                <x-text-input id="telefono" name="telefono" type="text" class="mt-1 block w-full" />
            </div>
            <div class="mt-4">
                <x-input-label for="adscripcion" value="Adscripción" />
                <select id="adscripcion" name="adscripcion" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                    <option value="" disabled selected>Seleccione una opción</option>
                    <option value="CEPHCIS">CEPHCIS</option>
                    <option value="Externo">Externo</option>
                </select>
            </div>
            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                <x-primary-button class="ml-3">Crear</x-primary-button>
            </div>
        </form>
    </x-modal>
    @endif
</x-app-layout>
