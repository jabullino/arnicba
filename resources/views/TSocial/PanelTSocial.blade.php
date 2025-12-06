@extends('layouts.app')
@section('content')

<div class="card mx-auto w-[350px] bg-neutral-500">
    <div class="card-header text-center bg-gray-500">
       PANEL DEL AREA SOCIAL    
    </div> 
    <div class="card-body">
        <div class='grid grid-cols-2'>
            <div id="filiacion" class='mr-4' >
                <form action="{{route('residentes.create')}}" method='GET'>
                <button class='bg-sky-900 h-64 w-64 rounded-full'>
                      FILIACIÓN
                </button>
                </form>
            </div>
            <div id="municipios">
                <form action="{{route('Municipios.create')}}" method='GET'>
                <button class='h-64 w-64 rounded-full bg-red-700'>
                      MUNICIPIOS
                </button>
                </form>
            </div>
        </div>
    </div>   
</div> 

@stop<!--fin section content -->