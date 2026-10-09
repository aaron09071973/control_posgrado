<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Semestre: ') }} {{ $semestre->numero_semestre }} ({{ $semestre->posgrado->nombre_posgrado }})
            </h2>
            <a href="{{ route('posgrados.show', $semestre->posgrado) }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">Volver al Posgrado</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100 overflow-x-auto">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">Grupos del Semestre</h3>
                        @if(Auth::user()->role === 'administrador')
                        <x-primary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-grupo')">
                            Crear Grupo
                        </x-primary-button>
                        @endif
                    </div>

                    @if($semestre->grupos->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400">No hay grupos registrados para este semestre.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            @foreach($semestre->grupos as $grupo)
                                <div class="border dark:border-gray-700 rounded-lg p-4 flex justify-between flex-col">
                                    <div>
                                        <h4 class="text-2xl font-bold text-center text-indigo-600 dark:text-indigo-400">Grupo "{{ $grupo->nombre_grupo }}"</h4>
                                    </div>
                                    <div class="mt-4 flex justify-between items-center border-t dark:border-gray-700 pt-4">
                                        <a href="{{ route('grupos.show', $grupo) }}" class="inline-block px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 transition ease-in-out duration-150 w-full text-center">Administrar Grupo</a>
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
    <!-- Create Grupo Modal -->
    <x-modal name="create-grupo" focusable>
        <form method="POST" action="{{ route('grupos.store') }}" class="p-6">
            @csrf
            <input type="hidden" name="id_semestre" value="{{ $semestre->id_semestre }}">
            
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Crear Grupo
            </h2>
            <div class="mt-4">
                <x-input-label for="nombre_grupo" value="Nombre del Grupo (Ej: A, B, Virtual)" />
                <x-text-input id="nombre_grupo" name="nombre_grupo" type="text" class="mt-1 block w-full" required />
            </div>
            <div class="mt-4">
                <x-input-label for="periodo_intersemestral" value="Periodo Intersemestral (Ej: 25 de noviembre al 06 de diciembre)" />
                <x-text-input id="periodo_intersemestral" name="periodo_intersemestral" type="text" maxlength="100" class="mt-1 block w-full" />
            </div>
            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                <x-primary-button class="ml-3">Crear Grupo</x-primary-button>
            </div>
        </form>
    </x-modal>
    @endif
</x-app-layout>
