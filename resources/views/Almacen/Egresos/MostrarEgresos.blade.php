@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-11">

            <div class="card shadow-sm">
                <div class="card-header text-white bg-teal-900 text-center text-md-start">
                    <h5 class="mb-0">
                        LISTADO DE EGRESOS
                    </h5>
                </div>

                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-light text-center text-md-start">
                                <tr>
                                    <th>ID</th>
                                    <th>Fecha</th>
                                    <th>Destinatario</th>
                                    <th style="min-width:220px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($egresos as $egreso)
                                    <tr class="text-center text-md-start">
                                        <td>{{ $egreso->id }}</td>
                                        <td>{{ $egreso->fecha }}</td>
                                        <td>{{ $egreso->destinatario->nombre }}</td>
                                        <td>
                                            <div class="d-grid gap-2 d-md-flex">

                                                <a href="{{ route('Egresos.edit', $egreso->id) }}"
                                                   class="btn btn-warning btn-sm flex-fill">
                                                    Editar
                                                </a>

                                                <form action="{{ route('Egresos.destroy', $egreso->id) }}"
                                                      method="POST"
                                                      class="flex-fill">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-sm w-100"
                                                            onclick="return confirm('¿Eliminar este egreso?')">
                                                        Eliminar
                                                    </button>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection