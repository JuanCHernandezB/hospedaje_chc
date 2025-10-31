@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="card shadow-lg rounded-5" style="border-radius: 20px; background: #fdf8f0;">
        <div class="card-body p-5">

            <h2 class="text-center fw-bold mb-5" style="color:#4254ba;">Editar Solicitud de Hospedaje</h2>

            <form id="formEditHospedaje" method="POST" action="{{ route('hospedaje.update', $hospedaje) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Columna izquierda: Solicitante + Acompañante -->
                    <div class="col-lg-6">
                        {{-- Solicitante --}}
                        <div class="mb-4 p-4 shadow-sm rounded-4" style="background-color:#ffffff;">
                            <h4 class="fw-semibold text-primary mb-4">Datos del Solicitante</h4>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Fecha Hospedaje</label>
                                    <input type="date" name="Fecha_Hospedaje" class="form-control form-control-lg" value="{{ $hospedaje->Fecha_Hospedaje }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Documento</label>
                                    <input type="text" name="Documento_Solicitante" class="form-control form-control-lg" value="{{ $hospedaje->Documento_Solicitante }}" required pattern="[0-9]+">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nombre</label>
                                    <input type="text" name="Nombre_Solicitante" class="form-control form-control-lg" value="{{ $hospedaje->Nombre_Solicitante }}" required pattern="[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Apellido</label>
                                    <input type="text" name="Apellido_Solicitante" class="form-control form-control-lg" value="{{ $hospedaje->Apellido_Solicitante }}" required pattern="[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+">
                                </div>
                            </div>
                        </div>

                        {{-- Acompañante --}}
                        <div class="mb-4 p-4 shadow-sm rounded-4" style="background-color:#ffffff;">
                            <h4 class="fw-semibold text-primary mb-4">Datos del Acompañante</h4>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Nombre</label>
                                    <input type="text" name="Nombre_Acompanante" class="form-control form-control-lg" value="{{ $hospedaje->Nombre_Acompanante }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Apellido</label>
                                    <input type="text" name="Apellido_Acompanante" class="form-control form-control-lg" value="{{ $hospedaje->Apellido_Acompanante }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Documento</label>
                                    <input type="text" name="Documento_Acompanante" class="form-control form-control-lg" value="{{ $hospedaje->Documento_Acompanante }}" pattern="[0-9]+">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Columna derecha: Documentos -->
                    <div class="col-lg-6">
                        <div class="mb-4 p-4 shadow-sm rounded-4" style="background-color:#ffffff;">
                            <h4 class="fw-semibold text-primary mb-4">Documentos</h4>
                            <div class="row g-3">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-semibold">Carta de Solicitud</label>
                                    <input type="file" name="Carta_Solicitud" class="form-control form-control-lg">
                                    @if ($hospedaje->Carta_Solicitud)
                                        <small class="text-muted">Actual: {{ basename($hospedaje->Carta_Solicitud) }}</small>
                                    @endif
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-semibold">Foto de Evidencia</label>
                                    <input type="file" name="Foto_Evidencia" class="form-control form-control-lg">
                                    @if ($hospedaje->Foto_Evidencia)
                                        <small class="text-muted">Actual: {{ basename($hospedaje->Foto_Evidencia) }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Botón de actualización --}}
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success btn-lg px-5" style="border-radius: 12px; font-weight:600; letter-spacing:0.5px;">
                        Actualizar Solicitud
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formEditHospedaje');
    form.addEventListener('submit', function(e){
        e.preventDefault();
        Swal.fire({
            title: '¿Actualizar Solicitud?',
            text: 'Se guardarán los cambios realizados.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            confirmButtonText: 'Sí, actualizar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if(result.isConfirmed){
                form.submit();
            }
        });
    });
});
</script>
@endsection
