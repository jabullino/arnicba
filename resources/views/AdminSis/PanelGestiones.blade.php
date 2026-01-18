@extends('layouts.app')

@section('content')
<div id="principal">
    <div class="card">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="card-header bg-sky-900 text-white text-center font-bold">
            FORMULARIO PARA LAS GESTIONES
        </div>

        <div class="card-body">
            <div id='envoltorio' class='grid-container'>

                <!-- Primer Semestre -->
                <div id='primerSemestre'>
                    <form action="{{ route('PanelGestiones.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tipo" value="primer">
                        <button type="submit" class="semestre-btn bg-sky-900">Crear Nueva Gestion</button>
                    </form>
                </div>

                <!-- Segundo Semestre -->
                <div id='segundoSemestre'>
                    <form action="{{ route('PanelGestiones.store') }}" method="POST" class="flex-form">
                        @csrf
                        <input type="hidden" name="tipo" value="segundo">
                        <div id='boton'>
                            <button id='btnSegundoSemestre' type="submit"
                                class="semestre-btn bg-green-500"
                                disabled>Registrar Incremento Salarial</button>
                        </div>
                        <div id='cajas' class='form-group'>
                            <label for="salarioMinimo">Salario Minimo</label>
                            <input type="text" name='salarioMinimo' id='salarioMinimo' class="form-control">
                            <label for="haberBasico" class='mt-2'>Haber Básico (%)</label>
                            <input type="text" name='haberBasico' id='haberBasico' class="form-control" disabled>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
.grid-container {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
    justify-items: center;
    width: 450px;
    margin: 0 auto;
}

.semestre-btn {
    width: 192px;
    height: 192px;
    border-radius: 50%;
    color: white;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.3s;
    background-color: rgb(26, 28, 49)
}

.semestre-btn:hover {
    opacity: 0.9;
}

.flex-form {
    display: flex;
    align-items: center;
    gap: 1rem;
}

#cajas {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-control {
    width: 192px;
    padding: 0.25rem;
}

/* --- Media Queries --- */
@media (max-width: 768px) {
    .grid-container {
        grid-template-columns: 1fr;
        gap: 1rem;
        width: 90%;
    }

    .flex-form {
        flex-direction: column;
        align-items: center;
    }

    #cajas {
        width: 100%;
    }

    .form-control {
        width: 100%;
    }

    .semestre-btn {
        width: 150px;
        height: 150px;
    }
}

@media (max-width: 480px) {
    .semestre-btn {
        width: 120px;
        height: 120px;
        font-size: 0.9rem;
    }

    #cajas label {
        font-size: 0.9rem;
    }

    #cajas input {
        font-size: 0.9rem;
    }
}
</style>
@endsection

@section('js')
<script>
const salarioMinimo = document.getElementById("salarioMinimo");
const haberBasico = document.getElementById("haberBasico");
const btnSegundoSemestre = document.getElementById("btnSegundoSemestre");

// Validar solo números y punto
salarioMinimo.addEventListener("keypress", function(e) {
    const char = String.fromCharCode(e.which);
    if (!/[0-9.]/.test(char)) e.preventDefault();
});

haberBasico.addEventListener("keypress", function(e) {
    const char = String.fromCharCode(e.which);
    if (!/[0-9.]/.test(char)) e.preventDefault();
});

// Habilitar campos progresivamente
salarioMinimo.addEventListener("blur", function() {
    const valor = salarioMinimo.value.trim();
    if (valor !== "" && !isNaN(valor)) {
        haberBasico.removeAttribute("disabled");
    } else {
        haberBasico.setAttribute("disabled", true);
        haberBasico.value = "";
        btnSegundoSemestre.disabled = true;
    }
});

haberBasico.addEventListener("blur", function() {
    const smVal = salarioMinimo.value.trim();
    const hbVal = haberBasico.value.trim();
    if (smVal !== "" && !isNaN(smVal) && hbVal !== "" && !isNaN(hbVal)) {
        btnSegundoSemestre.disabled = false;
    } else {
        btnSegundoSemestre.disabled = true;
    }
});
</script>
@endsection
