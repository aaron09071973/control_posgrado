<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestión de Usuarios (Alta)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
            
            <!-- Mostrar errores de validación si existen -->
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-4 flex justify-end">
                <x-primary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-user')">
                    Nuevo Usuario
                </x-primary-button>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Correo Electrónico</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Rol de Sistema</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha Alta</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($usuarios as $usuario)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $usuario->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $usuario->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $usuario->role === 'administrador' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($usuario->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $usuario->created_at ? $usuario->created_at->format('d/m/Y') : 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'view-user-{{ $usuario->id }}')" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">Ver</button>

                                    <!-- View User Modal -->
                                    <x-modal name="view-user-{{ $usuario->id }}" focusable>
                                        <div class="p-6">
                                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 text-left">
                                                Detalles de Usuario
                                            </h2>
                                            
                                            <div class="mt-4 text-left">
                                                <x-input-label value="Nombre Completo" />
                                                <x-text-input type="text" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700" :value="$usuario->name" disabled />
                                            </div>

                                            <div class="mt-4 text-left">
                                                <x-input-label value="Correo Electrónico" />
                                                <x-text-input type="email" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700" :value="$usuario->email" disabled />
                                            </div>

                                            <div class="mt-4 text-left">
                                                <x-input-label value="Rol de Sistema" />
                                                <x-text-input type="text" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700" :value="ucfirst($usuario->role)" disabled />
                                            </div>

                                            <div class="mt-4 text-left">
                                                <x-input-label value="Fecha Alta" />
                                                <x-text-input type="text" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700" :value="$usuario->created_at ? $usuario->created_at->format('d/m/Y H:i:s') : 'N/A'" disabled />
                                            </div>

                                            <div class="mt-6 flex justify-end">
                                                <x-secondary-button x-on:click="$dispatch('close')">Cerrar</x-secondary-button>
                                            </div>
                                        </div>
                                    </x-modal>

                                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'edit-user-{{ $usuario->id }}')" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">Editar</button>

                                    @if($usuario->email !== 'anavarro@cephcis.unam.mx' && $usuario->id !== auth()->id())
                                    <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150" onclick="return confirm('¿Seguro que deseas eliminar a este usuario del sistema?')">Eliminar</button>
                                    </form>
                                    @else
                                        <span class="text-gray-400 text-xs">(Baja Bloqueada)</span>
                                    @endif

                                    <!-- Edit User Modal -->
                                    <x-modal name="edit-user-{{ $usuario->id }}" focusable>
                                        <form method="POST" action="{{ route('usuarios.update', $usuario) }}" class="p-6">
                                            @csrf
                                            @method('PUT')
                                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 text-left">
                                                Editar Usuario
                                            </h2>
                                            
                                            <div class="mt-4 text-left">
                                                <x-input-label for="name_{{ $usuario->id }}" value="Nombre Completo" />
                                                <x-text-input id="name_{{ $usuario->id }}" name="name" type="text" class="mt-1 block w-full" :value="$usuario->name" required />
                                            </div>

                                            <div class="mt-4 text-left">
                                                <x-input-label for="email_{{ $usuario->id }}" value="Correo Electrónico" />
                                                <x-text-input id="email_{{ $usuario->id }}" name="email" type="email" class="mt-1 block w-full" :value="$usuario->email" required />
                                            </div>

                                            <div class="mt-4 text-left">
                                                <x-input-label for="password_{{ $usuario->id }}" value="Nueva Contraseña (Opcional)" />
                                                <x-text-input id="password_{{ $usuario->id }}" name="password" type="password" class="mt-1 block w-full" />
                                                <p class="text-xs text-gray-500 mt-1">Deja este campo vacío si no deseas cambiar la contraseña.</p>
                                            </div>

                                            <div class="mt-4 text-left">
                                                <x-input-label for="password_confirmation_{{ $usuario->id }}" value="Confirmar Nueva Contraseña" />
                                                <x-text-input id="password_confirmation_{{ $usuario->id }}" name="password_confirmation" type="password" class="mt-1 block w-full" />
                                            </div>

                                            <div class="mt-4 text-left">
                                                <x-input-label for="Tipo_{{ $usuario->id }}" value="Tipo de Usuario" />
                                                <select id="Tipo_{{ $usuario->id }}" name="Tipo" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                                    <option value="2" {{ $usuario->Tipo == 2 ? 'selected' : '' }}>Consulta</option>
                                                    <option value="1" {{ $usuario->Tipo == 1 ? 'selected' : '' }}>Administrador</option>
                                                </select>
                                            </div>

                                            <div class="mt-6 flex justify-end">
                                                <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                                                <x-primary-button class="ml-3">Guardar Cambios</x-primary-button>
                                            </div>
                                        </form>
                                    </x-modal>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Create User Modal -->
    <x-modal name="create-user" focusable>
        <form method="POST" action="{{ route('usuarios.store') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Alta de Nuevo Usuario
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Selecciona el tipo de rol para este nuevo usuario.
            </p>

            <div class="mt-4 text-left">
                <x-input-label for="name" value="Nombre Completo" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required />
            </div>

            <div class="mt-4 text-left">
                <x-input-label for="email" value="Correo Electrónico" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" required />
            </div>

            <div class="mt-4 text-left">
                <x-input-label for="password" value="Contraseña" />
                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required />
            </div>

            <div class="mt-4 text-left">
                <x-input-label for="Tipo" value="Tipo de Usuario" />
                <select id="Tipo" name="Tipo" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                    <option value="2">Consulta</option>
                    <option value="1">Administrador</option>
                </select>
            </div>

            <div class="mt-4 text-left">
                <x-input-label for="password_confirmation" value="Confirmar Contraseña" />
                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" required />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                <x-primary-button class="ml-3">Crear Usuario</x-primary-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
