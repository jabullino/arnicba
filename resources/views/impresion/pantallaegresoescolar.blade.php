@extends('layouts.app')

@section('content')

<div class="card w-[350px] mx-auto">
    <div class="card-header bg-teal-900 text-white text-center w-[350px]">
        IMPRESION  KARDEX EGRESOS
    </div>
    <div class="card-body mx-center bg-teal-900 w-[350px]">
    <h2 class='mb-2'>Producto: {{ $producto }}</h2>

    <p class='bg-white text-black'>
       <div class='mx-auto'> Producto {{ $indice + 1 }} de {{ $total }}</div>
    </p>

    <br>

    <a href="{{ $urlImprimir }}" target="_blank" class='ml-36'>
        <button style="padding:10px 20px; font-size:16px;" class='bg-amber-700 text-white'>
            Imprimir tarjeta
        </button>
    </a>

    <br><br>

    @if($indice + 1 < $total)
        <a href="{{ route('escolar.impresion.egreso.flujo', ['id'=>$id,'indice'=>$indice+1]) }}" class='ml-24'>
            <button style="padding:10px 20px; font-size:16px;" class='bg-amber-700 text-white'>
                Insertar siguiente tarjeta
            </button>
        </a>
    @else
        <h3>Impresión finalizada</h3>
    @endif
    </div>
</div>
@endsection