@extends('layouts.app')

@section('page_header')
<h1>Editar Caja Chica</h1>
@stop

@section('page_content')
<div class="card p-3 w-[400px] mx-auto">

    <form action="{{ route('cajachicas.update', $caja->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="gestion_id">Gestión</label>
            <select name="gestion_id" id="gestion_id" class="form-control">
                @foreach($gestiones as $gestion)
                    <option value="{{ $gestion->id }}" {{ $gestion->id == $caja->gestion_id ? 'selected' : '' }}>
                        {{ $gestion->nombre }}
                    </option>
                @endforeach
            </select>
            @error('gestion_id')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex justify-between">
            <a href="{{ route('cajachicas.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </div>
    </form>

</div>

{{-- 🔽 MEDIA QUERIES PARA RESPONSIVIDAD (sin tocar nada del diseño original) 🔽 --}}
<style>
    /* --- Ajustes generales --- */
    .card {
        box-sizing: border-box;
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    h1 {
        text-align: center;
        font-weight: 600;
    }

    /* --- Pantallas medianas (tablets) --- */
    @media (max-width: 992px) {
        .card {
            width: 90%;
            padding: 15px;
            margin: 0 auto;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-control {
            width: 100%;
            font-size: 0.95rem;
        }

        .btn {
            width: 48%;
            font-size: 0.95rem;
        }

        .flex {
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
        }
    }

    /* --- Pantallas pequeñas (móviles) --- */
    @media (max-width: 600px) {
        .card {
            width: 95%;
            padding: 12px;
        }

        h1 {
            font-size: 1.3rem;
            text-align: center;
        }

        .form-control {
            width: 100%;
            font-size: 0.9rem;
            padding: 8px;
        }

        label {
            margin-bottom: 5px;
            font-size: 0.9rem;
        }

        .btn {
            width: 100%;
            font-size: 0.9rem;
            margin-top: 10px;
        }

        .flex {
            flex-direction: column;
            align-items: stretch;
            gap: 8px;
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
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: '{{ session("success") }}',
        confirmButtonColor: '#3085d6'
    });
</script>
@endif
@stop
