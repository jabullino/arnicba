@extends('layouts.app')
@section('content')

<div class="card mx-auto w-[350px] bg-neutral-500">
    <div class="card-header text-center bg-gray-500">
       PANEL DEL AREA DE ALMACEN    
    </div> 
    <div class="card-body">
        <div class='grid grid-cols-2'>
            <div id="PRODUCTOS" class='mr-4' >
                <form action="{{route('Producto.index')}}" method='GET'>
                <button class='bg-sky-900 h-64 w-64 rounded-full'>
                      PRODUCTOS
                </button>
                </form>
            </div>
            <div id="ingresos">
                <form action="" method='GET'>
                <button class='h-64 w-64 rounded-full bg-red-700'>
                      INGRESOS
                </button>
                </form>
            </div>
            <div id="egresos">
                <form action="" method='GET'>
                <button class='h-64 w-64 rounded-full bg-gray-700'>
                      EGRESOS
                </button>
                </form>
            </div>
            <div id="devoluciones">
                <form action="" method='GET'>
                <button class='h-64 w-64 rounded-full bg-neutral-700'>
                      DEVOLUCIONES
                </button>
                </form>
            </div>
        </div>
    </div>   
</div> 

@stop<!--fin section content -->