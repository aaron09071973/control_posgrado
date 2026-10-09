<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Captura de Calificaciones') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ selectedAsignatura: '', searchStudent: '' }">
        <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm" role="alert">
                    <span class="block sm:inline font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Filtros y Botón Nuevo -->
            <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div class="md:col-span-2">
                    <x-input-label for="search_student" value="Buscar Alumno (Nombre o Cuenta)" />
                    <div class="relative mt-1">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </div>
                        <x-text-input id="search_student" x-model="searchStudent" type="text" class="ps-10 block w-full" placeholder="Ej: Juan Pérez o 1234567" />
                    </div>
                </div>

                <div>
                    <x-input-label for="filter_asignatura" value="Filtrar por Asignatura" />
                    <select id="filter_asignatura" x-model="selectedAsignatura" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        <option value="">Todas las asignaturas</option>
                        @foreach($asignaturas as $asignatura)
                            <option value="{{ $asignatura->id_asignatura }}">{{ $asignatura->nombre_asignatura }} ({{ $asignatura->codigo_asignatura }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end">
                    @if(Auth::user()->role === 'administrador')
                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-calificacion')" class="inline-flex items-center justify-center w-full md:w-auto px-3 py-1.5 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none transition ease-in-out duration-150 hover:scale-102 transform shadow-sm">
                        Capturar Calificación
                    </button>
                    @endif
                </div>
            </div>

            <!-- Tabla de Calificaciones -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-xl border border-gray-150 dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-100 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cuenta</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Alumno</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Asignatura</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider font-bold">Calificación</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($calificaciones as $calificacion)
                            <tr x-show="(selectedAsignatura === '' || selectedAsignatura === '{{ $calificacion->id_asignatura }}') && ('{{ strtolower($calificacion->alumno->nombre . ' ' . $calificacion->alumno->apellido . ' ' . $calificacion->alumno->Cta_alumno) }}'.includes(searchStudent.toLowerCase()))"
                                class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-mono">{{ $calificacion->alumno->Cta_alumno }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $calificacion->alumno->nombre }} {{ $calificacion->alumno->apellido }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    <div class="font-medium text-gray-900 dark:text-gray-200">{{ $calificacion->asignatura->nombre_asignatura }}</div>
                                    <div class="text-xs text-gray-400 dark:text-gray-500">{{ $calificacion->asignatura->codigo_asignatura }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold">
                                    @php
                                        $gradeVal = floatval($calificacion->calificacion);
                                        $badgeColor = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                                        if (is_numeric($calificacion->calificacion)) {
                                            if ($gradeVal >= 8.0) {
                                                $badgeColor = 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
                                            } elseif ($gradeVal >= 6.0) {
                                                $badgeColor = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400';
                                            } else {
                                                $badgeColor = 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
                                            }
                                        }
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ $badgeColor }}">
                                        {{ $calificacion->calificacion ?? 'S/C' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'view-calificacion-{{ $calificacion->id_calificacion }}')" class="inline-flex items-center px-3 py-1.5 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2 shadow-sm">Ver</button>

                                    <!-- View Modal -->
                                    <x-modal name="view-calificacion-{{ $calificacion->id_calificacion }}" focusable>
                                        <div class="p-6">
                                            <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 text-left border-b pb-3 border-gray-150 dark:border-gray-700">
                                                Detalles de Calificación
                                            </h2>
                                            <div class="mt-4 text-left grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <x-input-label value="Cuenta Alumno" />
                                                    <x-text-input type="text" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700" :value="$calificacion->alumno->Cta_alumno" disabled />
                                                </div>
                                                <div>
                                                    <x-input-label value="Nombre Alumno" />
                                                    <x-text-input type="text" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700" value="{{ $calificacion->alumno->nombre }} {{ $calificacion->alumno->apellido }}" disabled />
                                                </div>
                                                <div class="md:col-span-2">
                                                    <x-input-label value="Asignatura" />
                                                    <x-text-input type="text" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700" value="[{{ $calificacion->asignatura->codigo_asignatura }}] {{ $calificacion->asignatura->nombre_asignatura }}" disabled />
                                                </div>
                                                <div class="md:col-span-2">
                                                    <x-input-label value="Calificación" />
                                                    <x-text-input type="text" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 font-bold" :value="$calificacion->calificacion" disabled />
                                                </div>
                                            </div>
                                            <div class="mt-6 flex justify-end">
                                                <x-secondary-button x-on:click="$dispatch('close')">Cerrar</x-secondary-button>
                                            </div>
                                        </div>
                                    </x-modal>

                                    @if(Auth::user()->role === 'administrador')
                                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'edit-calificacion-{{ $calificacion->id_calificacion }}')" class="inline-flex items-center px-3 py-1.5 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2 shadow-sm">Editar</button>
                                    
                                    <form action="{{ route('calificaciones.destroy', $calificacion) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm" onclick="return confirm('¿Seguro que deseas eliminar esta calificación?')">Eliminar</button>
                                    </form>

                                    <!-- Edit Modal -->
                                    <x-modal name="edit-calificacion-{{ $calificacion->id_calificacion }}" focusable :closeable="false" :show="session('open_edit_modal_' . $calificacion->id_calificacion) ? true : false">
                                        <form method="POST" action="{{ route('calificaciones.update', $calificacion) }}" class="p-6"
                                              x-data="{ 
                                                  selectedAlumno: '{{ $calificacion->id_alumno }}',
                                                  selectedAsignatura: '{{ $calificacion->id_asignatura }}',
                                                  alumnoAsignaturasMap: @js($alumnoAsignaturasMap),
                                                  currentAsignatura: @js($calificacion->asignatura),
                                                  getAsignaturas() {
                                                      let list = this.selectedAlumno ? (this.alumnoAsignaturasMap[this.selectedAlumno] || []) : [];
                                                      if (this.currentAsignatura && this.selectedAlumno == '{{ $calificacion->id_alumno }}') {
                                                          if (!list.some(a => a.id_asignatura == this.currentAsignatura.id_asignatura)) {
                                                              list = [this.currentAsignatura, ...list];
                                                          }
                                                      }
                                                      return list;
                                                  }
                                              }">
                                            @csrf
                                            @method('PUT')
                                            <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 text-left border-b pb-3 border-gray-150 dark:border-gray-700">
                                                Editar Calificación
                                            </h2>
                                            
                                            <div class="mt-4 text-left">
                                                <x-input-label for="edit_id_alumno_{{ $calificacion->id_calificacion }}" value="Alumno" />
                                                <select id="edit_id_alumno_{{ $calificacion->id_calificacion }}" name="id_alumno" x-model="selectedAlumno" x-on:change="selectedAsignatura = ''" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                                    @foreach($alumnos as $alumno)
                                                        <option value="{{ $alumno->id_alumno }}">
                                                            [{{ $alumno->Cta_alumno }}] {{ $alumno->nombre }} {{ $alumno->apellido }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <x-input-error :messages="$errors->get('id_alumno')" class="mt-2" />
                                            </div>

                                            <div class="mt-4 text-left">
                                                <x-input-label for="edit_id_asignatura_{{ $calificacion->id_calificacion }}" value="Asignatura" />
                                                <select id="edit_id_asignatura_{{ $calificacion->id_calificacion }}" name="id_asignatura" x-model="selectedAsignatura" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                                    <option value="">Seleccione una asignatura...</option>
                                                    <template x-for="asignatura in getAsignaturas()" :key="asignatura.id_asignatura">
                                                        <option :value="asignatura.id_asignatura" :selected="asignatura.id_asignatura == selectedAsignatura" x-text="'[' + asignatura.codigo_asignatura + '] ' + asignatura.nombre_asignatura"></option>
                                                    </template>
                                                </select>
                                                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400" x-show="selectedAlumno && getAsignaturas().length === 0">
                                                    ⚠️ Este alumno no tiene asignaturas asignadas en ningún grupo.
                                                </div>
                                                <x-input-error :messages="$errors->get('id_asignatura')" class="mt-2" />
                                            </div>

                                            <div class="mt-4 text-left">
                                                <x-input-label for="edit_calificacion_{{ $calificacion->id_calificacion }}" value="Calificación" />
                                                <x-text-input id="edit_calificacion_{{ $calificacion->id_calificacion }}" name="calificacion" type="text" class="mt-1 block w-full" :value="$calificacion->calificacion" placeholder="Ej: 9.5, NP o Aprobado" required />
                                                <x-input-error :messages="$errors->get('calificacion')" class="mt-2" />
                                            </div>

                                            <div class="mt-6 flex justify-end">
                                                <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                                                <x-primary-button class="ml-3">Guardar Cambios</x-primary-button>
                                            </div>
                                        </form>
                                    </x-modal>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr id="empty-state">
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <svg class="w-12 h-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span class="text-base font-medium">No hay calificaciones registradas</span>
                                        <p class="text-xs text-gray-400 dark:text-gray-500">Usa el botón superior para capturar la primera calificación del sistema.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if(Auth::user()->role === 'administrador')
    <!-- Create Modal -->
    <x-modal name="create-calificacion" focusable :closeable="false" :show="session('open_create_modal') ? true : false">
        <form method="POST" action="{{ route('calificaciones.store') }}" class="p-6"
              x-data="{ 
                  selectedAlumno: '', 
                  selectedAsignatura: '',
                  alumnoAsignaturasMap: @js($alumnoAsignaturasMap),
                  getAsignaturas() {
                      return this.selectedAlumno ? (this.alumnoAsignaturasMap[this.selectedAlumno] || []) : [];
                  }
              }">
            @csrf
            <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 border-b pb-3 border-gray-150 dark:border-gray-700">
                Capturar Calificación
            </h2>
            
            <div class="mt-4">
                <x-input-label for="id_alumno" value="Alumno" />
                <select id="id_alumno" name="id_alumno" x-model="selectedAlumno" x-on:change="selectedAsignatura = ''" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                    <option value="">Seleccione un alumno...</option>
                    @foreach($alumnos as $alumno)
                        <option value="{{ $alumno->id_alumno }}">
                            [{{ $alumno->Cta_alumno }}] {{ $alumno->nombre }} {{ $alumno->apellido }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('id_alumno')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="id_asignatura" value="Asignatura" />
                <select id="id_asignatura" name="id_asignatura" x-model="selectedAsignatura" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                    <option value="">Seleccione una asignatura...</option>
                    <template x-for="asignatura in getAsignaturas()" :key="asignatura.id_asignatura">
                        <option :value="asignatura.id_asignatura" x-text="'[' + asignatura.codigo_asignatura + '] ' + asignatura.nombre_asignatura"></option>
                    </template>
                </select>
                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400" x-show="selectedAlumno && getAsignaturas().length === 0">
                    ⚠️ Este alumno no tiene asignaturas asignadas en ningún grupo.
                </div>
                <x-input-error :messages="$errors->get('id_asignatura')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="calificacion" value="Calificación" />
                <x-text-input id="calificacion" name="calificacion" type="text" class="mt-1 block w-full" placeholder="Ej: 10, 8.5, Aprobado o NP" required />
                <x-input-error :messages="$errors->get('calificacion')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                <x-primary-button class="ml-3">Registrar Calificación</x-primary-button>
            </div>
        </form>
    </x-modal>
    @endif
</x-app-layout>
