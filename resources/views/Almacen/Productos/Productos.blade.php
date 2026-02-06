@extends('layouts.app')
@section('content')

<div class="card shadow">
    <div class="card-header text-center bold">
           LISTADO DE PRODUCTOS
    </div>
    <div class="card-body">

         @extends('layouts.app')

@section('content')
<div class="container">

    <h3 class="mb-4 text-center bold">Lista de Productos</h3>

    <table class="table table-striped table-bordered ">
        <thead class="table-dark">
            <tr>
                <th class='text-center'>#</th>
                <th class='text-center'>Producto</th>
                <th class='text-center'>Código</th>
                <th class='text-center'>Saldo</th>
                <th class='text-center'>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($productos as $index => $producto)
                <tr>
                    <td>{{ $index + 1 }}</td>
                     @php
                          $datos=$prod->obtenerNombreProducto($producto->id,$producto->categoria_id);        
                     @endphp
                     <td> {{$datos['nombre']}}</td>
                     <td> {{$producto->codigo}}</td>
                     <td>${{ number_format($datos['saldo'], 2) }}</td>
                    
                    
                    <td>
                        <form action="" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-success btn-sm">
                                🛒 Agregar
                            </button>
                        </form>

                        <form action="" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-danger btn-sm">
                                ❌ Quitar
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="" class="btn btn-primary mt-3">
        Ver Carrito 🛍️
    </a>

</div>
@endsection


    </div>
</div>

@stop <!---fin seecion content -->

