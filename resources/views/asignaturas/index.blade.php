<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Asignaturas') }}
        </h2>
    </x-slot>

    <style>
        /* Force scrollbars to be always visible and styled for the table container */
        .table-container {
            overflow-x: scroll !important;
            scrollbar-width: auto !important;
            scrollbar-color: #475569 #e2e8f0 !important;
        }
        .table-container::-webkit-scrollbar {
            height: 12px !important;
            width: 12px !important;
            display: block !important;
        }
        .table-container::-webkit-scrollbar-track {
            background: #e2e8f0 !important;
            border-radius: 6px !important;
            box-shadow: inset 0 0 4px rgba(0, 0, 0, 0.1) !important;
        }
        .table-container::-webkit-scrollbar-thumb {
            background: #475569 !important;
            border-radius: 6px !important;
            border: 3px solid #e2e8f0 !important;
        }
        .table-container::-webkit-scrollbar-thumb:hover {
            background: #334155 !important;
        }
        
        /* Dark mode styled scrollbar */
        .dark .table-container {
            scrollbar-color: #9ca3af #111827 !important;
        }
        .dark .table-container::-webkit-scrollbar-track {
            background: #111827 !important;
            box-shadow: inset 0 0 4px rgba(0, 0, 0, 0.3) !important;
        }
        .dark .table-container::-webkit-scrollbar-thumb {
            background: #9ca3af !important;
            border: 3px solid #111827 !important;
        }
        .dark .table-container::-webkit-scrollbar-thumb:hover {
            background: #d1d5db !important;
        }
    </style>

    <div class="py-12">
        <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="mb-4 flex justify-end">
                @if(Auth::user()->role === 'administrador')
                <x-primary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-asignatura')">
                    Nueva Asignatura
                </x-primary-button>
                @endif
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 overflow-x-scroll table-container">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" style="min-width: 1100px !important;">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Código</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre de Asignatura</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Horario</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Hrs Lectivas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($asignaturas as $asignatura)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $asignatura->codigo_asignatura }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $asignatura->nombre_asignatura }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $asignatura->Horario }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $asignatura->hrslectivas }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $asignatura->tipo_asignatura }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'view-asignatura-{{ $asignatura->id_asignatura }}')" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">Ver</button>

                                    <!-- View Modal -->
                                    <x-modal name="view-asignatura-{{ $asignatura->id_asignatura }}" focusable>
                                        <div class="p-6">
                                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 text-left">
                                                Detalles de la Asignatura
                                            </h2>
                                            <div class="mt-4 text-left">
                                                <x-input-label value="Nombre de la Asignatura" />
                                                <x-text-input type="text" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700" :value="$asignatura->nombre_asignatura" disabled />
                                            </div>
                                            <div class="mt-4 text-left">
                                                <x-input-label value="Código" />
                                                <x-text-input type="text" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700" :value="$asignatura->codigo_asignatura" disabled />
                                            </div>
                                            <div class="mt-4 text-left">
                                                <x-input-label value="Horario" />
                                                <x-text-input type="text" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700" :value="$asignatura->Horario" disabled />
                                            </div>
                                            <div class="mt-4 text-left">
                                                <x-input-label value="Horas Lectivas" />
                                                <x-text-input type="number" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700" :value="$asignatura->hrslectivas" disabled />
                                            </div>
                                            <div class="mt-4 text-left">
                                                <x-input-label value="Tipo Asignatura" />
                                                <x-text-input type="text" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700" :value="$asignatura->tipo_asignatura" disabled />
                                            </div>
                                            <div class="mt-6 flex justify-end">
                                                <x-secondary-button x-on:click="$dispatch('close')">Cerrar</x-secondary-button>
                                            </div>
                                        </div>
                                    </x-modal>

                                    @if(Auth::user()->role === 'administrador')
                                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'edit-asignatura-{{ $asignatura->id_asignatura }}')" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">Editar</button>
                                    
                                    <form action="{{ route('asignaturas.destroy', $asignatura) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150" onclick="return confirm('¿Seguro que deseas eliminar esta asignatura?')">Eliminar</button>
                                    </form>

                                    <!-- Edit Modal -->
                                    <x-modal name="edit-asignatura-{{ $asignatura->id_asignatura }}" focusable>
                                        <form method="POST" action="{{ route('asignaturas.update', $asignatura) }}" class="p-6">
                                            @csrf
                                            @method('PUT')
                                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 text-left">
                                                Editar Asignatura
                                            </h2>
                                            <div class="mt-4 text-left">
                                                <x-input-label for="nombre_asignatura_{{ $asignatura->id_asignatura }}" value="Nombre de la Asignatura" />
                                                <x-text-input id="nombre_asignatura_{{ $asignatura->id_asignatura }}" name="nombre_asignatura" type="text" class="mt-1 block w-full" :value="$asignatura->nombre_asignatura" required />
                                            </div>
                                            <div class="mt-4 text-left">
                                                <x-input-label for="codigo_asignatura_{{ $asignatura->id_asignatura }}" value="Código" />
                                                <x-text-input id="codigo_asignatura_{{ $asignatura->id_asignatura }}" name="codigo_asignatura" type="text" class="mt-1 block w-full" :value="$asignatura->codigo_asignatura" required />
                                            </div>
                                            <div class="mt-4 text-left">
                                                <x-input-label for="Horario_{{ $asignatura->id_asignatura }}" value="Horario (Ej: Lunes y Miércoles 16:00 - 18:00)" />
                                                <x-text-input id="Horario_{{ $asignatura->id_asignatura }}" name="Horario" type="text" class="mt-1 block w-full" :value="$asignatura->Horario" required />
                                            </div>
                                            <div class="mt-4 text-left">
                                                <x-input-label for="hrslectivas_{{ $asignatura->id_asignatura }}" value="Horas Lectivas" />
                                                <x-text-input id="hrslectivas_{{ $asignatura->id_asignatura }}" name="hrslectivas" type="number" min="1" class="mt-1 block w-full" :value="$asignatura->hrslectivas" required />
                                            </div>
                                            <div class="mt-4 text-left">
                                                <x-input-label for="tipo_asignatura_{{ $asignatura->id_asignatura }}" value="Tipo Asignatura" />
                                                <select id="tipo_asignatura_{{ $asignatura->id_asignatura }}" name="tipo_asignatura" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                                    <option value="">Seleccione un tipo...</option>
                                                    <option value="Obligatoria" {{ $asignatura->tipo_asignatura == 'Obligatoria' ? 'selected' : '' }}>Obligatoria</option>
                                                    <option value="Optativa" {{ $asignatura->tipo_asignatura == 'Optativa' ? 'selected' : '' }}>Optativa</option>
                                                </select>
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
    <x-modal name="create-asignatura" focusable>
        <form method="POST" action="{{ route('asignaturas.store') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Nueva Asignatura
            </h2>
            <div class="mt-4">
                <x-input-label for="nombre_asignatura" value="Nombre de la Asignatura" />
                <x-text-input id="nombre_asignatura" name="nombre_asignatura" type="text" class="mt-1 block w-full" required />
            </div>
            <div class="mt-4">
                <x-input-label for="codigo_asignatura" value="Código" />
                <x-text-input id="codigo_asignatura" name="codigo_asignatura" type="text" class="mt-1 block w-full" required />
            </div>
            <div class="mt-4">
                <x-input-label for="Horario" value="Horario (Ej: Lunes y Miércoles 16:00 - 18:00)" />
                <x-text-input id="Horario" name="Horario" type="text" class="mt-1 block w-full" required />
            </div>
            <div class="mt-4">
                <x-input-label for="hrslectivas" value="Horas Lectivas" />
                <x-text-input id="hrslectivas" name="hrslectivas" type="number" min="1" class="mt-1 block w-full" required />
            </div>
            <div class="mt-4">
                <x-input-label for="tipo_asignatura" value="Tipo Asignatura" />
                <select id="tipo_asignatura" name="tipo_asignatura" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="">Seleccione un tipo...</option>
                    <option value="Obligatoria">Obligatoria</option>
                    <option value="Optativa">Optativa</option>
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
