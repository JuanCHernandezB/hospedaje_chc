<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestión de Hospedajes')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Iconos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Estilo personalizado -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        body {
            background: linear-gradient(to right, #f6e6cb, #f4d9b2);
            font-family: 'Poppins', sans-serif;
            color: #3b3b3b;
            min-height: 100vh;
        }
        .navbar {
            background-color: #8d5524 !important;
        }
        .navbar-brand, .nav-link {
            color: #fff !important;
            font-weight: 500;
        }
        .card {
            background-color: #fffdf8;
            border-radius: 16px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
        }
        .btn-primary {
            background-color: #d2691e;
            border-color: #d2691e;
        }
        .btn-primary:hover {
            background-color: #b55416;
            border-color: #b55416;
        }
        footer {
            background-color: #8d5524;
            color: #fff;
            text-align: center;
            padding: 10px;
            margin-top: 40px;
            border-radius: 12px 12px 0 0;
        }
        .fade-overlay {
            backdrop-filter: blur(4px);
            background-color: rgba(255, 248, 240, 0.8);
        }
    </style>

    @stack('styles')
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('hospedaje.index') }}">
                <i class="bi bi-house-door-fill"></i> Panel De Hospedaje CHC
            </a>
        </div>
    </nav>

    <!-- CONTENIDO -->
    <div class="container py-4">
        @yield('content')
    </div>

    <!-- FOOTER -->
    <footer>
        <small>© {{ date('Y') }} Gestión de Hospedajes CHC | FoSu 2025 </small>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 notificaciones globales -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: "{{ session('error') }}",
                    showConfirmButton: true
                });
            @endif

            @if($errors->any())
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos incompletos',
                    html: `<ul style="text-align: left;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>`,
                    confirmButtonText: 'Entendido'
                });
            @endif
        });
    </script>

    <!-- Script de control de inputs de acompañante (opcional) -->
    <script>
        (function(){
            const selectId = 'tieneAcompanante';
            const inputsIds = ['nombreA','apellidoA','documentoA'];

            function getElems() {
                return {
                    select: document.getElementById(selectId),
                    inputs: inputsIds.map(id => document.getElementById(id)).filter(Boolean)
                };
            }

            function toggleInputs() {
                const { select, inputs } = getElems();
                if(!select) return;
                const enable = select.value.toLowerCase() === 'si';
                inputs.forEach(i => { i.disabled = !enable; if(!enable) i.value = ''; });
            }

            document.addEventListener('change', e => {
                const { select } = getElems();
                if(!select) return;
                if(e.target === select || e.target.closest(`#${selectId}`)) toggleInputs();
            }, true);

            if(document.readyState === 'complete' || document.readyState === 'interactive') {
                setTimeout(toggleInputs, 10);
            } else {
                document.addEventListener('DOMContentLoaded', () => setTimeout(toggleInputs, 10));
            }

            window.__testAcompanante = function(val){
                const s = document.getElementById(selectId);
                if(!s) return console.warn('select no encontrado');
                s.value = val;
                s.dispatchEvent(new Event('change', { bubbles: true }));
                console.log('testAcompanante ->', val);
            };
        })();
    </script>

    <!-- Aquí se inyectarán los scripts de cada vista -->
    @stack('scripts')

</body>
</html>
