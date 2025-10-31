@extends('layouts.app')

@section('content')
<div class="container my-5">
    {{-- Título --}}
    <div class="text-center mb-5">
        <h2 class="fw-bold text-secondary" 
            style="font-size:2.3rem; letter-spacing:0.5px;">
            Listado de Hospedajes
        </h2>
        <div style="width:80px; height:3px; background:#a1b5d8; margin:10px auto 0; border-radius:2px;"></div>
    </div>

    {{-- Búsqueda + Botón nuevo --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <form id="searchForm" class="flex-grow-1">
            <input type="text" id="searchInput"
                   class="form-control form-control-lg shadow-sm"
                   placeholder="Buscar por nombre o documento..."
                   autocomplete="off"
                   style="background-color:#f4f7fb; border:1px solid #e3e9f3; border-radius:14px; padding:14px 18px;">
        </form>

        <a href="{{ route('hospedaje.create') }}"
           class="btn fw-semibold text-white shadow-sm px-4 py-2"
           style="background: linear-gradient(135deg, #3b82f6, #60a5fa); border-radius:12px;">
           Nuevo Hospedaje
        </a>
    </div>

    {{-- Frame principal --}}
    <div class="p-4" style="background-color:#e9eef7; border-radius:18px;">
        <div id="resultsContainer" class="row g-4">
            @foreach($hospedajes as $hospedaje)
                <div class="col-md-6 col-lg-4 fade-in">
                    <div class="card border-0 shadow h-100" style="border-radius:16px; background:#f8fbff;">
                        <div class="card-body d-flex flex-column px-4 py-4">
                            <h5 class="fw-bold mb-2 text-secondary"> Hospedaje #{{ $loop->iteration }}</h5>

                            <p class="mb-1"><strong>Fecha:</strong> 
                                {{ \Carbon\Carbon::parse($hospedaje->Fecha_Hospedaje)->format('d/m/Y') }}
                            </p>
                            <p class="mb-1"><strong>Solicitante:</strong> 
                                {{ $hospedaje->Nombre_Solicitante }} {{ $hospedaje->Apellido_Solicitante }}
                            </p>
                            <p class="mb-1"><strong>Documento:</strong> 
                                {{ $hospedaje->Documento_Solicitante }}
                            </p>
                            <p class="mb-3">
                                <strong>Acompañante:</strong>
                                @if($hospedaje->Nombre_Acompanante)
                                    {{ $hospedaje->Nombre_Acompanante }} {{ $hospedaje->Apellido_Acompanante }}
                                    (Doc: {{ $hospedaje->Documento_Acompanante }})
                                @else
                                    <span class="text-muted fst-italic">Sin acompañante</span>
                                @endif
                            </p>

                            {{-- Botones de documentos --}}
                            <div class="mb-3 d-flex flex-column gap-2">
                                @if($hospedaje->Carta_Solicitud)
                                     <a href="{{ asset('storage/cartas/' . basename($hospedaje->Carta_Solicitud)) }}"
                                            target="_blank" class="btn btn-outline-primary btn-sm w-100">
                                         Ver Carta
                                    </a>
                                @endif
                                @if($hospedaje->Foto_Evidencia)
                                    <a href="{{ asset('storage/fotos/' . basename($hospedaje->Foto_Evidencia)) }}" 
                                        target="_blank" class="btn btn-outline-secondary btn-sm w-100">
                                        Ver Foto
                                    </a>
                                @endif
                            </div>

                            {{-- Botones de acción --}}
                            <div class="mt-auto d-flex justify-content-between gap-2">
                                <a href="{{ route('hospedaje.edit', $hospedaje) }}" 
                                   class="btn btn-warning btn-sm px-3 w-50">
                                   Editar
                                </a>
                                <button type="button" 
                                        class="btn btn-danger btn-sm px-3 w-50 delete-btn">
                                    Eliminar
                                </button>

                                <form action="{{ route('hospedaje.destroy', $hospedaje) }}" 
                                      method="POST" class="d-none delete-form">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @if($hospedajes->isEmpty())
                <div class="col-12 text-center text-muted mt-3">No se encontraron registros.</div>
            @endif
        </div>

        {{-- Paginación --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $hospedajes->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<style>
.fade-in { animation: fadeIn 0.6s ease-in-out; }
@keyframes fadeIn { from {opacity:0; transform:translateY(8px);} to {opacity:1; transform:translateY(0);} }

.card {
    transition: all 0.3s ease;
}
.card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
}
.btn-outline-primary:hover, 
.btn-outline-info:hover {
    background: linear-gradient(135deg, #3b82f6, #60a5fa);
    color: #fff !important;
    border: none;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Búsqueda dinámica
    const input = document.getElementById('searchInput');
    const container = document.getElementById('resultsContainer');

    input.addEventListener('keyup', function () {
        const query = input.value.trim();
        fetch(`{{ route('hospedaje.buscar') }}?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                container.innerHTML = '';
                if (data.length === 0) {
                    container.innerHTML = `<div class='col-12 text-center text-muted mt-3'>No se encontraron resultados.</div>`;
                } else {
                    data.forEach(h => {
                        const acomp = h.Nombre_Acompanante 
                            ? `${h.Nombre_Acompanante} ${h.Apellido_Acompanante} (Doc: ${h.Documento_Acompanante})`
                            : '<span class="text-muted fst-italic">Sin acompañante</span>';

                        container.innerHTML += `
                        <div class="col-md-6 col-lg-4 fade-in">
                            <div class="card border-0 shadow h-100" style="border-radius:16px; background:#f8fbff;">
                                <div class="card-body d-flex flex-column px-4 py-4">
                                    <h5 class="fw-bold mb-3 text-secondary">Hospedaje #${h.id_Solicitante}</h5>
                                    <p class="mb-1"><strong>Fecha:</strong> ${h.Fecha_Hospedaje}</p>
                                    <p class="mb-1"><strong>Solicitante:</strong> ${h.Nombre_Solicitante} ${h.Apellido_Solicitante}</p>
                                    <p class="mb-1"><strong>Documento:</strong> ${h.Documento_Solicitante}</p>
                                    <p class="mb-3"><strong>Acompañante:</strong> ${acomp}</p>

                                    <div class="mb-3 d-flex flex-column gap-2">
                                        ${h.Carta_Solicitud ? `<a href="/storage/cartas/${h.Carta_Solicitud}" target="_blank" class="btn btn-outline-primary btn-sm w-100">Ver Carta</a>` : ''}
                                        ${h.Foto_Evidencia ? `<a href="/storage/fotos/${h.Foto_Evidencia}" target="_blank" class="btn btn-outline-info btn-sm w-100">Ver Foto</a>` : ''}
                                    </div>

                                    <div class="mt-auto d-flex justify-content-between gap-2">
                                        <a href="/hospedaje/${h.id_Solicitante}/edit" class="btn btn-warning btn-sm px-3 w-50">Editar</a>
                                        <button type="button" class="btn btn-danger btn-sm px-3 w-50 delete-btn">Eliminar</button>
                                        <form action="/hospedaje/${h.id_Solicitante}" method="POST" class="d-none delete-form">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                    });
                }
            });
    });

    // Confirmación de eliminación
    document.addEventListener('click', function(e){
        if(e.target.classList.contains('delete-btn')){
            const form = e.target.closest('.card-body').querySelector('.delete-form');
            Swal.fire({
                title: '¿Desea eliminar este registro?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if(result.isConfirmed){
                    form.submit();
                }
            });
        }
    });
});
</script>
@endsection
