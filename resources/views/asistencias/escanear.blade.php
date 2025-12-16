@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-100 flex flex-col items-center justify-center p-6">

    {{-- CARD PRINCIPAL --}}
    <div class="bg-white shadow-xl rounded-2xl p-8 w-full max-w-lg text-center">

        <h2 class="text-2xl font-bold text-orange-600 mb-4">
            Escanear QR del Estudiante 📸
        </h2>

        <p class="text-gray-600 mb-6">
            Acerca el QR a la cámara para registrar asistencia automáticamente.
        </p>
        @if(session('error'))
        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg text-center">
            {{ session('error') }}
        </div>
        @endif

        {{-- CONTENEDOR DE LA CÁMARA --}}
        <div class="bg-black rounded-xl overflow-hidden shadow-md mx-auto mb-6 w-[400px] h-full">
            <div id="reader" style="width: 100%;"></div>
        </div>



        {{-- FORM oculto --}}
        <form id="formQR" method="POST" action="{{ route('asistencia.registrar') }}">
            @csrf
            <input type="hidden" name="codigo_qr" id="codigo_qr">
        </form>

    </div>
</div>

{{-- LIBRERÍA QR --}}
<script src="https://unpkg.com/html5-qrcode"></script>

<script src="https://unpkg.com/html5-qrcode"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        let yaEscaneado = false;
        let html5QrCode = new Html5Qrcode("reader");

        function onScanSuccess(decodedText) {

            if (yaEscaneado) return; // evita duplicados
            yaEscaneado = true;

            const beep = new Audio("https://actions.google.com/sounds/v1/alarms/beep_short.ogg");
            beep.play();

            document.getElementById('codigo_qr').value = decodedText.trim();

            // DETENER CÁMARA
            html5QrCode.stop().then(() => {
                document.getElementById('formQR').submit();
            }).catch(err => {
                console.error(err);
                document.getElementById('formQR').submit();
            });
        }

        Html5Qrcode.getCameras().then(cameras => {
            if (cameras.length > 0) {
                html5QrCode.start(
                    cameras[0].id, {
                        fps: 10, // baja FPS
                        qrbox: {
                            width: 230,
                            height: 230
                        }
                    },
                    onScanSuccess
                );
            } else {
                alert("No se encontró una cámara");
            }
        }).catch(err => {
            console.error(err);
            alert("Error accediendo a la cámara");
        });

    });
</script>


@endsection