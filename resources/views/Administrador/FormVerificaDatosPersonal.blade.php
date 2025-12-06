@extends('layouts.app')
@section('content')
    <div> <!---div principal---->
        <form action="{{ route('Registrapersonal') }}" method='Post' enctype="multipart/form-data">
            @csrf

            <input type="hidden" name='codigousuario' class="text" value='{{ $idusuario }}'>

            <div class='mb-2 form-group'>
                <label for="codigo">Codigo</label>
                <input type="text" name='codigo' value='{{ $codpersonal }}' readonly class='form-control'>
                @if ($errors->has('codigo'))
                    <div style="color: red;">
                        {{ $errors->first('codigo') }}
                    </div>
                @endif
            </div>

            <div class='mb-2 form-group'>
                <label for="nombre">Nombre</label>
                <input type="text" name='nombre' value='{{ $nombre }}' readonly class='form-control'>
                @if ($errors->has('nombre'))
                    <div style="color: red;">
                        {{ $errors->first('nombre') }}
                    </div>
                @endif
            </div>

            <div class='mb-2 form-group'>
                <label for="apellido">Apellido</label>
                <input type="text" name='apellido' value='{{ $apellido }}' readonly class='form-control'>
                @if ($errors->has('apellido'))
                    <div style="color: red;">
                        {{ $errors->first('apellido') }}
                    </div>
                @endif
            </div>

            <div class='mb-2 form-group'>
                <label for="cargo">Cargo</label>
                <input type="text" name='cargo' value='{{ $cargo }}' readonly class='form-control'>
                @if ($errors->has('cargo'))
                    <div style="color: red;">
                        {{ $errors->first('cargo') }}
                    </div>
                @endif
            </div>

            <div class='mb-2 form-group'>
                <label for="haberbasico">Haber Básico Bs.</label>
                <input type="text" name='haberbasico' value='{{ number_format($haberbasico, 2, '.', ',') }}' readonly class='form-control'>
                @if ($errors->has('haberbasico'))
                    <div style="color: red;">
                        {{ $errors->first('haberbasico') }}
                    </div>
                @endif
            </div>

            <div class='text-white bg-gray-700 w-[1035px] text-center rounded mt-2 mb-2'>
                DOCUMENTOS
            </div>
            <br>
            <div class='grid grid-cols-5 gap-4 w-[1000px]'>
                @foreach ($documentos as $doc)
                    <div class="flex items-center space-x-2 w-[300px]">
                        <input type="checkbox" name='documentos[]' value="{{ $doc->id }}" id="{{ $doc->id }}" style='display:inline-block'>
                        &nbsp;{{ $doc->nombre }}&nbsp;
                    </div>
                @endforeach
            </div>

            <div class='mb-2 form-group sm:grid grid-cols-1 md: col-span-2'>
                <button class='bg-sky-900 text-white bold form-control rounded sm:grid grid-cols-1 md: col-span-2'>Registrar</button>
            </div>
        </form>
    </div><!-----fin div principal---->

    <style>
        /* Media queries para responsive */
        @media (max-width: 1024px) {
            .w-[1035px], .w-[1000px], .grid-cols-5 {
                width: 90% !important;
                display: grid !important;
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)) !important;
                margin-left: auto !important;
                margin-right: auto !important;
            }
        }

        @media (max-width: 768px) {
            input.form-control, button.form-control {
                width: 100% !important;
            }
        }

        @media (max-width: 480px) {
            .text-center {
                font-size: 0.875rem !important;
            }
        }
    </style>
@endsection
