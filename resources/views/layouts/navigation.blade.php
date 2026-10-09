<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-[95%] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-14 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('posgrados.index')" :active="request()->routeIs('posgrados.*')">
                        Posgrados
                    </x-nav-link>
                    <x-nav-link :href="route('alumnos.index')" :active="request()->routeIs('alumnos.*')">
                        Alumnos
                    </x-nav-link>
                    <x-nav-link :href="route('profesores.index')" :active="request()->routeIs('profesores.*')">
                        Profesores
                    </x-nav-link>
                    <x-nav-link :href="route('asignaturas.index')" :active="request()->routeIs('asignaturas.*')">
                        Asignaturas
                    </x-nav-link>
                    <x-nav-link :href="route('calificaciones.index')" :active="request()->routeIs('calificaciones.*')">
                        Calificaciones
                    </x-nav-link>

                    <!-- Consultas Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                    <div>Consultas</div>

                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('consultas.alumnos')">
                                    Alumnos por Grupo
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('consultas.alumnos_asignatura')">
                                    Alumnos por Asignatura
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('consultas.profesores')">
                                    Profesores por Asignatura
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('consultas.asignaturas')">
                                    Asignaturas por Grupo
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('consultas.asignaturas_alumno')">
                                    Asignaturas por Alumno
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('consultas.kardex')">
                                    Kardex por Alumno
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('consultas.asignaturas_profesor')">
                                    Asignaturas por Profesor
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                    @if(Auth::user()->role === 'administrador')
                    <x-nav-link :href="route('usuarios.index')" :active="request()->routeIs('usuarios.*')">
                        Usuarios
                    </x-nav-link>
                    <x-nav-link :href="route('bitacora.index')" :active="request()->routeIs('bitacora.*')">
                        Bitácora
                    </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <img class="h-8 w-8 rounded-full object-cover mr-2" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=1c293d&background=e2e8f0" alt="{{ Auth::user()->name }}" />
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Perfil') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Cerrar Sesión') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('posgrados.index')" :active="request()->routeIs('posgrados.*')">
                Posgrados
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('alumnos.index')" :active="request()->routeIs('alumnos.*')">
                Alumnos
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('profesores.index')" :active="request()->routeIs('profesores.*')">
                Profesores
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('asignaturas.index')" :active="request()->routeIs('asignaturas.*')">
                Asignaturas
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('calificaciones.index')" :active="request()->routeIs('calificaciones.*')">
                Calificaciones
            </x-responsive-nav-link>
            
            <div class="pt-2 pb-1 border-t border-gray-200 dark:border-gray-600">
                <div class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Consultas</div>
                <x-responsive-nav-link :href="route('consultas.alumnos')" :active="request()->routeIs('consultas.alumnos')">
                    - Alumnos por Grupo
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('consultas.alumnos_asignatura')" :active="request()->routeIs('consultas.alumnos_asignatura')">
                    - Alumnos por Asignatura
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('consultas.profesores')" :active="request()->routeIs('consultas.profesores')">
                    - Profesores por Asignatura
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('consultas.asignaturas')" :active="request()->routeIs('consultas.asignaturas')">
                    - Asignaturas por Grupo
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('consultas.asignaturas_alumno')" :active="request()->routeIs('consultas.asignaturas_alumno')">
                    - Asignaturas por Alumno
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('consultas.kardex')" :active="request()->routeIs('consultas.kardex')">
                    - Kardex por Alumno
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('consultas.asignaturas_profesor')" :active="request()->routeIs('consultas.asignaturas_profesor')">
                    - Asignaturas por Profesor
                </x-responsive-nav-link>
            </div>
            @if(Auth::user()->role === 'administrador')
            <x-responsive-nav-link :href="route('usuarios.index')" :active="request()->routeIs('usuarios.*')">
                Usuarios
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('bitacora.index')" :active="request()->routeIs('bitacora.*')">
                Bitácora
            </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4 flex items-center">
                <div class="shrink-0 mr-3">
                    <img class="h-10 w-10 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=1c293d&background=e2e8f0" alt="{{ Auth::user()->name }}" />
                </div>
                <div>
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Perfil') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Cerrar Sesión') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
