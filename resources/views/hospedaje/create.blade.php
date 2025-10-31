@extends('layouts.app')

@section('title', 'Registro de Hospedaje')

@section('content')
<div class="container mt-5 mb-5">
    <div class="card shadow-lg border-0">
        <div class="card-header text-white" style="background-color: #4254ba;">
            <h4 class="mb-0">Registro de Hospedaje</h4>
        </div>
        <div class="card-body p-5">

            <!-- Encabezado del wizard -->
            <div class="d-flex justify-content-between align-items-center mb-4" id="wizardSteps">
                <div class="step active" data-step="1">Datos del Solicitante</div>
                <div class="step" data-step="2">Datos del Acompañante</div>
                <div class="step" data-step="3">Documentos</div>
            </div>

            <form id="hospedajeForm" action="{{ route('hospedaje.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Paso 1 -->
                <div class="wizard-step active" id="step1">
                    <h5 class="text-primary mb-4 fw-bold">Datos del Solicitante</h5>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-end">Fecha de Hospedaje:</label>
                        <div class="col-sm-9">
                            <input type="date" name="Fecha_Hospedaje" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-end">Documento Solicitante:</label>
                        <div class="col-sm-9">
                            <input type="text" name="Documento_Solicitante" class="form-control" required pattern="[0-9]+">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-end">Nombre Solicitante:</label>
                        <div class="col-sm-9">
                            <input type="text" name="Nombre_Solicitante" class="form-control" required pattern="[A-Za-z\s]+">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-end">Apellido Solicitante:</label>
                        <div class="col-sm-9">
                            <input type="text" name="Apellido_Solicitante" class="form-control" required pattern="[A-Za-z\s]+">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label text-end">¿Hay acompañante?</label>
                        <div class="col-sm-9">
                            <select name="tiene_acompanante" id="tiene_acompanante" class="form-select" required>
                                <option value="">Seleccione...</option>
                                <option value="no">No</option>
                                <option value="si">Sí</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn text-white px-4" style="background-color:#77cefb;" id="nextBtn1">
                            Siguiente &gt;
                        </button>
                    </div>
                </div>

                <!-- Paso 2 -->
                <div class="wizard-step" id="step2">
                    <h5 class="text-primary mb-4 fw-bold">Datos del Acompañante</h5>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-end">Nombre Acompañante:</label>
                        <div class="col-sm-9">
                            <input type="text" name="Nombre_Acompanante" class="form-control" pattern="[A-Za-z\s]+">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-end">Apellido Acompañante:</label>
                        <div class="col-sm-9">
                            <input type="text" name="Apellido_Acompanante" class="form-control" pattern="[A-Za-z\s]+">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label text-end">Documento Acompañante:</label>
                        <div class="col-sm-9">
                            <input type="text" name="Documento_Acompanante" class="form-control" pattern="[0-9]+">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn text-white px-4" style="background-color:#4254ba;" id="backBtn2">
                            &lt; Atrás
                        </button>
                        <button type="button" class="btn text-white px-4" style="background-color:#77cefb;" id="nextBtn2">
                            Siguiente &gt;
                        </button>
                    </div>
                </div>

                <!-- Paso 3 -->
                <div class="wizard-step" id="step3">
                    <h5 class="text-primary mb-4 fw-bold">Documentos</h5>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-end">Carta de Solicitud:</label>
                        <div class="col-sm-9">
                            <input type="file" name="Carta_Solicitud" class="form-control" accept=".pdf" required>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label text-end">Foto de Evidencia:</label>
                        <div class="col-sm-9">
                            <input type="file" name="Foto_Evidencia" class="form-control" accept=".jpg,.png" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn text-white px-4" style="background-color:#4254ba;" id="backBtn3">
                            &lt; Atrás
                        </button>
                        <button type="submit" class="btn text-white px-4" style="background-color:#77cefb;">
                            Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const steps = document.querySelectorAll(".step");
    const sections = document.querySelectorAll(".wizard-step");
    const acomp = document.getElementById("tiene_acompanante");

    function showStep(step) {
        sections.forEach((s, i) => {
            s.classList.toggle("active", i + 1 === step);
            steps[i].classList.toggle("active", i + 1 === step);
        });
    }

    function validarPaso1() {
        const fecha = document.querySelector("[name='Fecha_Hospedaje']").value;
        const doc = document.querySelector("[name='Documento_Solicitante']").value;
        const nombre = document.querySelector("[name='Nombre_Solicitante']").value;
        const apellido = document.querySelector("[name='Apellido_Solicitante']").value;
        const tiene = acomp.value;

        if (!fecha || !doc || !nombre || !apellido || !tiene) {
            Swal.fire("Campos incompletos", "Debe llenar todos los campos del solicitante.", "warning");
            return false;
        }
        return true;
    }

    document.getElementById("nextBtn1").addEventListener("click", function() {
        if (validarPaso1()) {
            if (acomp.value === "si") {
                showStep(2);
            } else {
                showStep(3);
            }
        }
    });

    document.getElementById("backBtn2").addEventListener("click", () => showStep(1));
    document.getElementById("nextBtn2").addEventListener("click", () => {
        const nomA = document.querySelector("[name='Nombre_Acompanante']").value;
        const apeA = document.querySelector("[name='Apellido_Acompanante']").value;
        const docA = document.querySelector("[name='Documento_Acompanante']").value;

        if (!nomA || !apeA || !docA) {
            Swal.fire("Campos incompletos", "Debe llenar todos los datos del acompañante.", "warning");
        } else {
            showStep(3);
        }
    });

    document.getElementById("backBtn3").addEventListener("click", () => {
        if (acomp.value === "si") showStep(2);
        else showStep(1);
    });
});
</script>

<!-- SweetAlert al guardar -->
@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#4254ba',
        confirmButtonText: 'Aceptar'
    }).then(() => {
        window.location.href = "{{ route('hospedaje.index') }}";
    });
</script>
@endif

<style>
.step {
    flex: 1;
    text-align: center;
    padding: 10px;
    color: #aaa;
    font-weight: 600;
    border-bottom: 3px solid #ccc;
    cursor: pointer;
    transition: all 0.3s ease;
}
.step.active {
    color: #4254ba;
    border-color: #77cefb;
}
.wizard-step {
    display: none;
}
.wizard-step.active {
    display: block;
}
.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #ccc;
}
.form-control:focus, .form-select:focus {
    border-color: #77cefb;
    box-shadow: none;
}
</style>
@endsection

