@extends('layouts.app')
@section('content')
<div id='principal' class='w-[450px]' style='margin:auto'>

    <form action="{{ route('generavacaciones') }}" method="POST">
        @csrf
        <div class='card'>
            <div class='card-header bg-sky-900 text-white text-center bold'>
                <div id='vacacionescreadas'>
                    @if (session('vacacionescreadas'))
                        <div class="alert alert-success bg-green-700 text-white bold text-center">
                            {{ session('vacacionescreadas') }}
                        </div>
                    @endif
                </div>
                FORMULARIO DE ASIGNACION DE VACACIONES
                @if ($errors->has('fecha'))
                    <div class='bg-red-500 text-white text-center bold' id='errorfecha'>
                        {{ $errors->first('fecha') }} 
                    </div>
                @endif
            </div>
            <div class='card-body'>
                <div class='form-group'>
                    <label for="fecha"></label>
                    <input type="date" class='form-control' name="fecha" id="fecha">
                </div>
                <div class='form-group'>
                    <button type="submit" class='form-control bg-sky-900 text-white bold text-center'>Crear
                        Vacaciones</button>
                </div>
            </div><!---card-body---->
        </div><!---fin div card---->
    </form>

</div><!---fin div principal---->

<style>
    /* ------------------ Responsive ------------------ */
    @media (max-width: 768px) {
        #principal {
            width: 90%;
            margin: 0 auto;
        }
        .card-header {
            font-size: 1rem;
            padding: 0.5rem;
        }
        .form-control {
            width: 100%;
            font-size: 0.9rem;
            padding: 0.4rem;
        }
        button.form-control {
            padding: 0.5rem;
        }
        .alert {
            font-size: 0.85rem;
            padding: 0.3rem 0.5rem;
        }
    }

    @media (max-width: 480px) {
        #principal {
            width: 95%;
        }
        .card-header {
            font-size: 0.9rem;
        }
        .form-control {
            font-size: 0.8rem;
        }
        button.form-control {
            font-size: 0.8rem;
            padding: 0.4rem;
        }
        .alert {
            font-size: 0.75rem;
            padding: 0.25rem 0.4rem;
        }
    }
</style>

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let alertBox = document.getElementById('errorfecha');
        if (alertBox) {
            setTimeout(() => {
                alertBox.style.transition = "opacity 1s ease";
                alertBox.style.opacity = "0";
                setTimeout(() => alertBox.remove(), 1000);
            }, 3000);
        }
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let alertBox = document.getElementById('vacacionescreadas');
        if (alertBox) {
            setTimeout(() => {
                alertBox.style.transition = "opacity 1s ease";
                alertBox.style.opacity = "0";
                setTimeout(() => alertBox.remove(), 1000);
            }, 5000);
        }
    });
</script>
@endsection
@endsection
