<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Bitácora del Sistema') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                        Historial de movimientos realizados por los usuarios en el sistema.
                    </p>

                    <form method="GET" action="{{ route('bitacora.index') }}" class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg flex flex-row flex-wrap gap-4 items-end">
                        <div class="w-1/3 min-w-[200px]">
                            <x-input-label for="id_usuario" :value="__('Usuario')" />
                            <select id="id_usuario" name="id_usuario" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Todos los usuarios</option>
                                @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}" {{ request('id_usuario') == $usuario->id ? 'selected' : '' }}>
                                        {{ $usuario->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-1/5 min-w-[140px]">
                            <x-input-label for="fecha_inicio" :value="__('Desde')" />
                            <x-text-input id="fecha_inicio" name="fecha_inicio" type="date" class="mt-1 block w-full" :value="request('fecha_inicio')" />
                        </div>

                        <div class="w-1/5 min-w-[140px]">
                            <x-input-label for="fecha_fin" :value="__('Hasta')" />
                            <x-text-input id="fecha_fin" name="fecha_fin" type="date" class="mt-1 block w-full" :value="request('fecha_fin')" />
                        </div>

                        <div class="flex gap-2">
                            <x-primary-button type="submit">
                                {{ __('Filtrar') }}
                            </x-primary-button>
                            @if(request()->hasAny(['id_usuario', 'fecha_inicio', 'fecha_fin']))
                                <a href="{{ route('bitacora.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Limpiar
                                </a>
                            @endif
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha / Hora</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Usuario</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acción</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Módulo / Registro ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Detalles</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($bitacoras as $bitacora)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $bitacora->created_at->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $bitacora->user ? $bitacora->user->name : 'Usuario Eliminado/Sistema' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($bitacora->accion === 'CREAR')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Creación</span>
                                        @elseif($bitacora->accion === 'ACTUALIZAR')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Actualización</span>
                                        @elseif($bitacora->accion === 'ELIMINAR')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Eliminación</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ $bitacora->accion }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        <strong>{{ $bitacora->tabla_afectada }}</strong> (ID: {{ $bitacora->registro_id ?? 'N/A' }})
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate" title="{{ json_encode($bitacora->detalles) }}">
                                        @if($bitacora->detalles)
                                            <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'view-details-{{ $bitacora->id_bitacora }}')" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Ver JSON</button>

                                            <!-- Modal -->
                                            <x-modal name="view-details-{{ $bitacora->id_bitacora }}" focusable>
                                                <div class="p-6">
                                                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                                        Detalles del Movimiento
                                                    </h2>
                                                    <div class="bg-gray-100 dark:bg-gray-900 p-4 rounded text-xs overflow-auto max-h-96">
                                                        <pre><code>{{ json_encode($bitacora->detalles, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                                                    </div>
                                                    <div class="mt-6 flex justify-end">
                                                        <x-secondary-button x-on:click="$dispatch('close')">Cerrar</x-secondary-button>
                                                    </div>
                                                </div>
                                            </x-modal>
                                        @else
                                            Sin detalles
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                        No hay movimientos registrados en la bitácora.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $bitacoras->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
