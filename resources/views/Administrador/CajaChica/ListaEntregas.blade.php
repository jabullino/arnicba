@extends('layouts.app')

@section('page_header')
    <h1>Cajas Chicas</h1>
@stop

@section('page_content')
    <div class="card p-3 w-[350px] mx-auto">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Gestión</th>
                    <th class='text-center'>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cajachicas as $caja)
                    <tr>
                        <td>{{ $caja->id }}</td>
                        <td>{{ $caja->gestion->nombre }}</td>
                            <a href="javascript:void(0)" class="btn btn-sm btn-info btnVer" data-id="{{ $caja->id }}">
                                Ver
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">
                            No hay cajas chicas para la gestión activa
                            ({{ $gestionActiva->nombre ?? 'Sin gestión' }})
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>

    {{-- Estilos responsivos con media queries --}}
    <style>
        /* General */
        .card {
            transition: all 0.3s ease;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
        }

        .table th, .table td {
            padding: 8px;
            text-align: left;
        }

        /* Estilo para pantallas pequeñas */
        @media (max-width: 768px) {
            .card {
                width: 90% !important;
                padding: 1rem;
            }

            table thead {
                display: none;
            }

            table, table tbody, table tr, table td {
                display: block;
                width: 100%;
            }

            table tr {
                margin-bottom: 1rem;
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 10px;
                background: #fff;
            }

            table td {
                text-align: right;
                padding: 10px 15px;
                position: relative;
            }

            table td::before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                width: 50%;
                padding-left: 5px;
                font-weight: bold;
                text-align: left;
                color: #333;
            }

            /* Botones más grandes en móvil */
            .btn {
                display: inline-block;
                width: 100%;
                margin-bottom: 6px;
                text-align: center;
                font-size: 0.9rem;
            }

            .btn-sm {
                padding: 8px;
            }
        }

        /* Tablets (entre 769px y 1024px) */
        @media (min-width: 769px) and (max-width: 1024px) {
            .card {
                width: 80% !important;
            }

            .table th, .table td {
                font-size: 0.9rem;
                padding: 6px;
            }

            .btn-sm {
                padding: 6px 8px;
            }
        }

        /* Pantallas grandes */
        @media (min-width: 1025px) {
            .card {
                width: 400px !important;
            }
        }
    </style>
@stop

@section('js')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Confirmación SweetAlert al eliminar
        document.querySelectorAll('.formEliminar').forEach(form => {
            form.addEventListener('submit', function(e) {
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
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3085d6'
            });
        @endif

        // Alertas de error
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: '¡Error!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#d33'
            });
        @endif

        // Alertas de validación
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: '¡Error de validación!',
                html: '{!! implode('<br>', $errors->all()) !!}',
                confirmButtonColor: '#d33'
            });
        @endif
    </script>
    <script>
        // Ver detalles de Caja Chica
        document.querySelectorAll('.btnVer').forEach(btn => {
            btn.addEventListener('click', function() {
                let id = this.getAttribute('data-id');
                fetch(`/AdminSis/cajachicas/${id}`) // <- URL corregida
                    .then(response => {
                        if (!response.ok) throw new Error('No se pudo cargar');
                        return response.json();
                    })
                    .then(data => {
                        Swal.fire({
                            title: `Caja Chica #${data.id}`,
                            html: `
                        <p><strong>Gestión:</strong> ${data.gestion.nombre}</p>
                        <p><strong>Creado:</strong> ${new Date(data.created_at).toLocaleDateString()}</p>
                        <p><strong>Actualizado:</strong> ${new Date(data.updated_at).toLocaleDateString()}</p>
                    `,
                            icon: 'info',
                            confirmButtonColor: '#3085d6'
                        });
                    })
                    .catch(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudieron cargar los detalles.',
                            confirmButtonColor: '#d33'
                        });
                    });
            });
        });
    </script>
@stop
