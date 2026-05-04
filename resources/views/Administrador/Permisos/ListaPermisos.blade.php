@extends('layouts.app')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
.container {
    max-width: 900px;
    margin: auto;
    color: #fff;
}

.card {
    background: #1e1e1e;
    border: 1px solid #444;
    padding: 15px;
    margin-bottom: 10px;
    border-radius: 8px;
}

.row {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: center;
}

.block {
    flex: 1;
    min-width: 130px;
}

.label {
    font-size: 12px;
    color: #aaa;
}

.valor {
    font-weight: bold;
}

.estado {
    padding: 5px 10px;
    border-radius: 5px;
    font-weight: bold;
    text-align: center;
}

.pendiente { background: orange; }

/* 🔥 BOTÓN AUTORIZADO */
.btn-autorizado {
    background: green;
    color: #fff;
}

/* BOTONES */
.btn {
    padding: 5px 10px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    font-weight: bold;
}

.btn-autorizar {
    background: #2196F3;
    color: #fff;
}
</style>

<div class="container">

<h2>Lista de Permisos</h2>

@foreach($permisos as $permiso)

<div class="card" id="permiso-{{ $permiso->id }}">

    <div class="row">

        <div class="block">
            <div class="label">N° Solicitud</div>
            <div class="valor">{{ $permiso->num_permiso }}</div>
        </div>

        <div class="block">
            <div class="label">Gestión</div>
            <div class="valor">{{ $permiso->gestion_nombre }}</div>
        </div>

        <div class="block">
            <div class="label">Solicitante</div>
            <div class="valor">
                {{ $permiso->user_nombre }} {{ $permiso->user_apellido }}
            </div>
        </div>

        <div class="block">
            <div class="label">Fecha Salida</div>
            <div class="valor">{{ $permiso->fecha_salida }}</div>
        </div>

        <div class="block">
            <div class="label">Destino</div>
            <div class="valor">{{ $permiso->destino }}</div>
        </div>

        <!-- 🔥 ESTADO / ACCIÓN -->
        <div class="block estado-area" style="flex:0 0 auto; display:flex; gap:5px; align-items:center;">

            @if($permiso->estado === 'autorizado')

                <!-- 🔥 BOTÓN QUE IMPRIME -->
                <a href="{{ route('permisos.imprimir', $permiso->id) }}" 
                    target="_blank"
                    class="btn btn-autorizado">
                     AUTORIZADO
                 </a>

            @else

                <div class="estado pendiente">PENDIENTE</div>

                @if(isset($modo) && $modo === 'admin')
                    <button 
                        class="btn btn-autorizar"
                        onclick="autorizar({{ $permiso->id }})">
                        Autorizar
                    </button>
                @endif

            @endif

        </div>

    </div>

</div>

@endforeach

</div>

{{-- 🔥 SCRIPT SOLO ADMIN --}}
@if(isset($modo) && $modo === 'admin')

<script>
function autorizar(id) {

    Swal.fire({
        title: '¿Autorizar permiso?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí'
    }).then((result) => {

        if (result.isConfirmed) {

            let formData = new FormData();
            formData.append('_method', 'PUT');

            fetch(`/AdminSis/PermisoSalida/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.text())
            .then(text => {

                let data;

                try {
                    data = JSON.parse(text);
                } catch (e) {
                    throw new Error('Respuesta no es JSON');
                }

                if (data.success) {

                    Swal.fire('Autorizado', '', 'success');

                    let card = document.getElementById(`permiso-${id}`);
                    let estadoArea = card.querySelector('.estado-area');

                    // 🔥 CAMBIO DINÁMICO A BOTÓN VERDE
                    estadoArea.innerHTML = `
                        <a href="/permisos/${id}/imprimir" 
                           target="_blank"
                           class="btn btn-autorizado">
                            AUTORIZADO
                        </a>
                    `;

                } else {
                    throw new Error('Error en respuesta');
                }

            })
            .catch(() => {
                Swal.fire('Error al autorizar', '', 'error');
            });

        }

    });
}
</script>

@endif

@endsection
