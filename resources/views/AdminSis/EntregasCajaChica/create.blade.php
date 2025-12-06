@extends('layouts.app')

@section('page_header')
    <h1>Agregar Nueva Entrega</h1>
@stop

@section('page_content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('entregascajachicas.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="fecha_entrega">Fecha de Entrega</label>
                <input type="date" name="fecha_entrega" id="fecha_entrega" class="form-control" required>
            </div>

            <div class="form-group mt-2">
                <label for="monto">Monto</label>
                <input type="text" name="monto" id="monto" class="form-control" required>
            </div>

            <div class="form-group mt-2">
                <label for="mes">Mes</label>
                <select name="mes" id="mes" class="form-control" required>
                    <option value="">-- Selecciona un mes --</option>
                    @foreach($meses as $key => $nombre)
                        <option value="{{ $key }}">{{ $nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mt-3">
                <label for="gestion_id">Gestión</label>
                <select name="gestion_id" id="gestion_id" class="form-control" required>
                    <option value="">-- Escoja una gestión --</option>
                    @foreach($gestiones as $gest)
                        <option value="{{ $gest->id }}">{{ $gest->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-success mt-3 w-full">Guardar</button>
        </form>
    </div>
</div>

{{-- ====== ESTILOS RESPONSIVE SIN BOOTSTRAP ====== --}}
<style>
/* ====== ESTILOS BASE ====== */
.card {
    max-width: 600px;
    margin: 20px auto;
    border-radius: 10px;
    box-shadow: 0 0 6px rgba(0,0,0,0.1);
    padding: 15px;
}

.card-body {
    padding: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 10px;
}

label {
    font-weight: 600;
    margin-bottom: 5px;
}

input[type="text"],
input[type="date"],
select {
    width: 100%;
    padding: 8px;
    font-size: 1rem;
    border-radius: 5px;
    border: 1px solid #ccc;
}

button {
    display: block;
    width: 100%;
    padding: 10px;
    font-weight: bold;
    border-radius: 6px;
}

/* ====== PANTALLAS MEDIANAS (TABLETS) ====== */
@media (max-width: 992px) {
    .card {
        width: 90%;
    }

    input[type="text"],
    input[type="date"],
    select {
        font-size: 0.95rem;
        padding: 7px;
    }

    button {
        font-size: 1rem;
    }
}

/* ====== PANTALLAS PEQUEÑAS (CELULARES) ====== */
@media (max-width: 768px) {
    .card {
        width: 95%;
        padding: 10px;
    }

    .card-body {
        padding: 10px;
    }

    h1 {
        text-align: center;
        font-size: 1.3rem;
    }

    label {
        font-size: 0.95rem;
    }

    input[type="text"],
    input[type="date"],
    select {
        font-size: 0.9rem;
        padding: 6px;
    }

    button {
        font-size: 0.95rem;
        padding: 8px;
    }
}

/* ====== PANTALLAS MUY PEQUEÑAS (menos de 480px) ====== */
@media (max-width: 480px) {
    .card {
        margin: 10px;
        padding: 8px;
    }

    .card-body {
        padding: 8px;
    }

    input[type="text"],
    input[type="date"],
    select {
        font-size: 0.85rem;
    }

    button {
        font-size: 0.9rem;
        padding: 7px;
    }

    h1 {
        font-size: 1.1rem;
        text-align: center;
    }
}
</style>
@endsection

@section('js')
@parent
<script>
    // Solo permite números y punto decimal en el campo monto
    document.getElementById('monto').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9.]/g,'');
    });

    // Cuando se cambia la gestión, traemos cajas por AJAX
    document.getElementById('gestion_id').addEventListener('change', function() {
        const gestionId = this.value;
        const cajaSelect = document.getElementById('cajachica_id');
        if (!cajaSelect) return; // Evita errores si el elemento no existe
        cajaSelect.innerHTML = '<option value="">Cargando...</option>';

        if (gestionId) {
            fetch(`/AdminSis/cajachicas/por-gestion/${gestionId}`)
                .then(response => response.json())
                .then(data => {
                    cajaSelect.innerHTML = '<option value="">-- Selecciona una caja --</option>';
                    data.forEach(caja => {
                        const option = document.createElement('option');
                        option.value = caja.id;
                        option.text = caja.nombre;
                        cajaSelect.appendChild(option);
                    });
                })
                .catch(err => {
                    cajaSelect.innerHTML = '<option value="">-- Error al cargar cajas --</option>';
                    console.error(err);
                });
        } else {
            cajaSelect.innerHTML = '<option value="">-- Selecciona una caja --</option>';
        }
    });
</script>
@endsection
