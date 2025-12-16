@extends('layouts.app')

@section('title', 'Inicio - I.E.P. 2028 Peruano Británico')

@section('content')

<div class="relative w-full h-[700px] overflow-hidden"> 
    <div class="absolute inset-0 bg-gray-900">
        <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=2070&auto=format&fit=crop" 
             alt="Campus Educativo" 
             class="w-full h-full object-cover opacity-60">
        </div>
    
    <div class="absolute inset-0 bg-gradient-to-r from-blue-950 via-blue-900/80 to-transparent"></div>

    <div class="relative z-10 container mx-auto px-6 h-full flex flex-col justify-center items-start pt-10">
        
        <div class="inline-flex items-center gap-2 py-1 px-3 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white text-xs font-bold tracking-widest uppercase mb-6 shadow-lg">
            <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
            Institución Educativa Bicentenario
        </div>

        <h1 class="text-5xl md:text-7xl font-serif font-bold text-white leading-tight mb-6 drop-shadow-2xl">
            Excelencia <br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-200 to-amber-500">
                Peruano Británica
            </span>
        </h1>

        <p class="text-lg md:text-xl text-gray-200 max-w-2xl mb-10 font-light border-l-4 border-red-600 pl-6 leading-relaxed">
            Formamos el futuro de San Martín de Porres con tecnología de vanguardia, 
            sólidos valores éticos y un dominio superior del idioma inglés.
        </p>

        <div class="flex flex-wrap gap-4">
            <a href="#footer" class="px-8 py-4 bg-red-700 hover:bg-red-800 text-white font-semibold rounded-lg shadow-xl hover:shadow-2xl transition transform hover:-translate-y-1">
                Contáctanos
            </a>
            
            <a href="#gestion" class="px-8 py-4 bg-white/10 backdrop-blur-sm border border-white/30 text-white hover:bg-white hover:text-blue-900 font-semibold rounded-lg transition duration-300">
                Plataforma Académica
            </a>
        </div>
    </div>
</div>

<section id="gestion" class="relative py-20 bg-gray-50">
    <div class="container mx-auto px-6 -mt-32 relative z-20">
        
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-2xl shadow-xl hover:shadow-2xl transition duration-300 border-b-4 border-blue-900 flex flex-col items-center text-center">
                <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mb-6 text-blue-900 shadow-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Registro de Estudiantes</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Matrícula 100% digital. Simplificamos el proceso de inscripción para nuevos talentos.
                </p>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-xl hover:shadow-2xl transition duration-300 border-b-4 border-red-600 flex flex-col items-center text-center transform md:-translate-y-4"> 
                <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mb-6 text-red-700 shadow-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Asistencia en Tiempo Real</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Seguridad garantizada. Monitoreo exacto del ingreso y salida de cada estudiante.
                </p>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-xl hover:shadow-2xl transition duration-300 border-b-4 border-amber-500 flex flex-col items-center text-center">
                <div class="w-16 h-16 bg-amber-50 rounded-full flex items-center justify-center mb-6 text-amber-600 shadow-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Reportes Académicos</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Transparencia total. Generación instantánea de libretas y constancias en PDF.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="py-24 bg-white overflow-hidden">
    <div class="container mx-auto px-6 space-y-32"> <div class="flex flex-col md:flex-row items-center gap-16">
            <div class="md:w-1/2 relative group">
                <div class="absolute -top-6 -left-6 w-full h-full border-2 border-blue-100 rounded-2xl z-0 transition-transform duration-500 group-hover:translate-x-2 group-hover:translate-y-2"></div>
                
                <div class="relative z-10 rounded-2xl overflow-hidden shadow-2xl">
                    <img src="{{ asset('images/inaguracion.jpg') }}" 
                         alt="Misión Educativa" 
                         class="w-full h-auto object-cover transform transition duration-700 group-hover:scale-105">
                </div>
            </div>
            
            <div class="md:w-1/2">
                <div class="flex items-center gap-4 mb-4">
                    <span class="h-px w-12 bg-amber-500"></span>
                    <span class="text-amber-600 font-bold uppercase tracking-widest text-sm">Nuestro Propósito</span>
                </div>
                <h2 class="text-4xl font-serif font-bold text-blue-900 mb-6">Misión Institucional</h2>
                <p class="text-gray-600 text-lg leading-relaxed mb-8">
                    Brindar una educación integral de calidad, formando estudiantes responsables, críticos y comprometidos con la sociedad, cimentados en valores éticos y morales inquebrantables.
                </p>
                
                <div class="grid grid-cols-1 gap-4">
                    <div class="flex items-start p-4 bg-gray-50 rounded-lg hover:bg-blue-50 transition">
                        <div class="flex-shrink-0 mt-1">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-gray-900 font-bold">Educación Integral</h4>
                            <p class="text-gray-500 text-sm">Desarrollo académico, emocional y físico.</p>
                        </div>
                    </div>
                    <div class="flex items-start p-4 bg-gray-50 rounded-lg hover:bg-blue-50 transition">
                        <div class="flex-shrink-0 mt-1">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-gray-900 font-bold">Pensamiento Crítico</h4>
                            <p class="text-gray-500 text-sm">Formamos líderes que analizan y proponen soluciones.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row-reverse items-center gap-16">
            <div class="md:w-1/2 relative group">
                <div class="absolute -bottom-6 -right-6 w-full h-full border-2 border-amber-100 rounded-2xl z-0 transition-transform duration-500 group-hover:-translate-x-2 group-hover:-translate-y-2"></div>
                
                <div class="relative z-10 rounded-2xl overflow-hidden shadow-2xl">
                    <img src="{{ asset('images/alumnos.jpg') }}"  
                         alt="Visión Futura" 
                         class="w-full h-auto object-cover transform transition duration-700 group-hover:scale-105">
                </div>
            </div>
            
            <div class="md:w-1/2">
                <div class="flex items-center gap-4 mb-4">
                    <span class="h-px w-12 bg-red-600"></span>
                    <span class="text-red-600 font-bold uppercase tracking-widest text-sm">Hacia dónde vamos</span>
                </div>
                <h2 class="text-4xl font-serif font-bold text-blue-900 mb-6">Visión de Futuro</h2>
                <p class="text-gray-600 text-lg leading-relaxed mb-8">
                    Ser una institución educativa líder a nivel nacional, reconocida por su excelencia académica bilingüe y el uso de tecnologías innovadoras que preparan a nuestros alumnos para los retos globales del siglo XXI.
                </p>
                <div class="flex flex-wrap gap-3">
                    <span class="px-4 py-2 bg-blue-50 text-blue-800 rounded-full text-sm font-semibold">Liderazgo Educativo</span>
                    <span class="px-4 py-2 bg-amber-50 text-amber-800 rounded-full text-sm font-semibold">Innovación Tecnológica</span>
                    <span class="px-4 py-2 bg-red-50 text-red-800 rounded-full text-sm font-semibold">Excelencia Bilingüe</span>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection