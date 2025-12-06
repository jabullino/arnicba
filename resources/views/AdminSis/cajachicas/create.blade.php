@extends('layouts.app')

@section('page_header')
    <h1>Crear Caja Chica</h1>
@stop

@section('page_content')
<div class="card p-3">
    <form action="{{ route('cajachicas.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="gestion_id">Gestión</label>
            <select name="gestion_id" id="gestion_id" class="form-control" required>
                <option value="">Seleccionar gestión</option>
                @foreach($gestionesActivas as $gestion)
                    <option value="{{ $gestion->id }}">{{ $gestion->nombre }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Guardar</button>
        <a href="{{ url('adminsis/cajachicas') }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>
</div>

{{-- 🔽 BLOQUE DE MEDIA QUERIES PARA RESPONSIVIDAD 🔽 --}}
<style>
    /* --- Ajustes generales --- */
    .card {
        max-width: 500px;
        margin: 0 auto;
        box-sizing: border-box;
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .form-group label {
        font-weight: 600;
    }

    /* --- Tablet (pantallas medianas) --- */
    @media (max-width: 992px) {
        .card {
            width: 90%;
            padding: 15px;
        }

        h1 {
            font-size: 1.5rem;
            text-align: center;
        }

        .btn {
            width: 100%;
        }

        .form-control {
            width: 100%;
            font-size: 0.95rem;
        }
    }

    /* --- Móviles (pantallas pequeñas) --- */
    @media (max-width: 600px) {
        .card {
            width: 95%;
            padding: 12px;
        }

        h1 {
            font-size: 1.3rem;
            text-align: center;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            font-size: 0.9rem;
        }

        .form-control {
            font-size: 0.9rem;
            padding: 8px;
        }

        .btn {
            width: 100%;
            font-size: 0.9rem;
            margin-top: 10px;
        }

        a.btn {
            text-align: center;
        }
    }

    /* --- Extra pequeñas (menos de 400px) --- */
    @media (max-width: 400px) {
        .card {
            width: 100%;
            padding: 10px;
        }

        h1 {
            font-size: 1.1rem;
        }

        .btn, .form-control {
            font-size: 0.85rem;
        }
    }
</style>
@stop

@section('js')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Confirmación SweetAlert al eliminar
    document.querySelectorAll('.formEliminar').forEach(form => {
        form.addEventListener('submit', function(e){
            e.preventDefault(); // evita el submit directo
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción eliminará la Caja Chica",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit(); // envía el formulario si confirma
                }
            });
        });
    });

    // Alertas de éxito
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session("success") }}',
            confirmButtonColor: '#3085d6'
        });
    @endif

    // Alertas de error
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: '{{ session("error") }}',
            confirmButtonColor: '#d33'
        });
    @endif

    // Alertas de validación (opcional)
    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: '¡Error de validación!',
            html: '{!! implode("<br>", $errors->all()) !!}',
            confirmButtonColor: '#d33'
        });
    @endif
</script>
@stop
