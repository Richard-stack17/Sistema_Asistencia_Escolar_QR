<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Sistema de Asistencia')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/images/logo_colegio.png') }}">

    {{-- VITE --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen font-sans">

    {{-- 
       DEFINICIÓN DE ESTILOS PARA LOS LINKS 
       Esto hace que el código de abajo sea mucho más limpio.
       $common: Estilos base (transiciones, posición de la línea).
       $active: Cómo se ve cuando estás en esa página (Línea completa, texto naranja).
       $inactive: Cómo se ve normalmente (Gris, línea invisible hasta pasar el mouse).
    --}}
    @php
        $common = "relative text-[0.95rem] font-medium transition-all duration-300 py-2 tracking-wide
                   after:absolute after:left-0 after:bottom-0 after:h-[2px] 
                   after:bg-orange-600 after:transition-all after:duration-300";

        $inactive = "text-gray-500 hover:text-orange-600 after:w-0 hover:after:w-full";
        
        $active   = "text-orange-700 font-semibold after:w-full cursor-default";
    @endphp

    {{-- NAVBAR --}}
    {{-- 'sticky top-0 z-50 backdrop-blur-md' crea el efecto de vidrio flotante --}}
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md shadow-sm border-b border-gray-100 transition-all">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">

            {{-- LOGO --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="relative group-hover:scale-105 transition-transform duration-300">
                    <img src="{{ asset('images/logo_colegio.png') }}"
                        alt="Logo"
                        class="w-10 h-12 object-contain drop-shadow-sm">
                </div>

                <div class="leading-none select-none">
                    <p class="font-bold text-gray-800 text-lg tracking-tight group-hover:text-black transition-colors">I.E.P. 2028</p>
                    <p class="text-[1.1rem] font-bold text-orange-600 tracking-wide">
                        Peruano Británico
                    </p>
                </div>
            </a>

            {{-- MENU DE NAVEGACIÓN --}}
            <nav class="hidden md:flex items-center gap-8">

                {{-- INICIO --}}
                {{-- request()->is('/') verifica si estás en la raiz --}}
                <a href="{{ url('/') }}"
                   class="{{ $common }} {{ request()->is('/') ? $active : $inactive }}">
                    Inicio
                </a>

                {{-- ESTUDIANTES --}}
                {{-- routeIs('estudiantes.*') mantiene activo el link incluso si estás en 'crear' o 'editar' estudiante --}}
                <a href="{{ route('estudiantes.index') }}"
                   class="{{ $common }} {{ request()->routeIs('estudiantes.*') ? $active : $inactive }}">
                    Estudiantes
                </a>

                {{-- ESCANEO QR (SOLO ROL 2 - AUXILIAR) --}}
                @if(session()->has('usuario_id') && in_array(session('id_rol'), [2]))
                    <a href="{{ route('asistencia.escanear') }}"
                       class="{{ $common }} {{ request()->routeIs('asistencia.escanear') ? $active : $inactive }}">
                        Escaneo QR
                    </a>
                @endif

                {{-- ASISTENCIAS (ROLES 2 y 3) --}}
                @if(session()->has('usuario_id') && in_array(session('id_rol'), [2,3]))
                    <a href="{{ route('asistencias.index') }}"
                       class="{{ $common }} {{ request()->routeIs('asistencias.*') ? $active : $inactive }}">
                        Asistencias
                    </a>
                @endif

                {{-- 🔐 SOLO DIRECTOR (ROL 3) --}}
                @if(session()->has('usuario_id') && session('id_rol') == 3)
                    <a href="{{ route('usuarios.index') }}"
                       class="{{ $common }} {{ request()->routeIs('usuarios.*') ? $active : $inactive }}">
                        Usuarios
                    </a>

                    <a href="{{ route('auditorias.index') }}"
                       class="{{ $common }} {{ request()->routeIs('auditorias.*') ? $active : $inactive }}">
                        Auditorías
                    </a>
                @endif

                {{-- JUSTIFICACIONES (LOGICA UNIFICADA VISUALMENTE) --}}
                @if(session()->has('usuario_id'))
                    
                    @if(session('id_rol') == 3)
                        <a href="{{ route('director.justificaciones') }}"
                           class="{{ $common }} {{ request()->routeIs('director.justificaciones') ? $active : $inactive }}">
                            Justificaciones
                        </a>
                    @endif
                @endif

            </nav>

            {{-- USUARIO ACTIVO --}}
            <div class="flex items-center gap-6">

                @if(session()->has('usuario_id'))
                    <div class="hidden lg:block text-right leading-tight">
                        <p class="text-sm font-bold text-gray-800">
                            {{ session('nombre_usuario') }}
                        </p>
                        <p class="text-[0.7rem] font-semibold text-orange-600 uppercase tracking-wider bg-orange-50 px-2 py-0.5 rounded-full inline-block mt-0.5">
                            {{ session('nombre_rol') }}
                        </p>
                    </div>

                    <div class="hidden lg:block h-8 w-[1px] bg-gray-200"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="group relative flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-orange-100 text-gray-600 hover:text-orange-600 transition-all duration-300 focus:outline-none cursor-pointer"
                            title="Cerrar Sesión">
                            
                            {{-- Icono de Salida (SVG) --}}
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                            </svg>
                        </button>
                    </form>

                @else
                    <a href="{{ route('login') }}"
                        class="bg-orange-600 text-white px-6 py-2.5 rounded-full text-sm font-semibold shadow-md shadow-orange-200 hover:bg-orange-700 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 cursor-pointer">
                        Iniciar sesión
                    </a>
                @endif
            </div>

        </div>
    </header>

    {{-- CONTENIDO --}}
    <main class="flex-grow w-full">
        {{-- Aquí se inyecta el contenido de las vistas --}}
        @yield('content')
    </main>

    {{-- FOOTER --}}
    @include('layouts.footer')

    @stack('scripts')
</body>

</html>