@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    <div class="row justify-content-center">
        <div class="col-12 col-xxl-10">

            <div class="card card-outline card-secondary shadow-sm">
                <div class="card-body">

                    <h3 class="mb-4 fw-bold">
                        Listado de Historiales
                    </h3>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">

                            <thead>
                                <tr>
                                    <th>Residente</th>
                                    <th>Título</th>
                                    <th>Fecha Registro</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($historiales as $historial)
                                    <tr>
                                        <td>
                                            {{ $historial->residente->nombre ?? '' }}
                                            {{ $historial->residente->apellido ?? '' }}
                                        </td>

                                        <td>
                                            {{ $historial->titulo }}
                                        </td>

                                        <td>
                                            {{ $historial->created_at->format('d-m-Y') }}
                                        </td>

                                        <td class="text-center">
                                            <a href="{{ route('historiales.edit', $historial->id) }}"
                                               class="btn btn-sm btn-primary">
                                                <i class="fa fa-edit"></i> Editar
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            No hay historiales registrados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>

</div>
@endsection