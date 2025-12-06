@extends('layouts.app')
@section('content')

<div><!---div principal ---->

    <div class='card w-[450px] mx-auto'><!---div card --->
        <div class='card-header text-center bold w-[450px] mx-auto'> 
            FORMULARIO PARA EL ÁREA DE CONTABILIDAD
        </div>
        @csrf
        <div class='card-body'>
            <div class='bg-gray-700 sm:grid grid-cols-1 md:grid grid-cols-2 w-[320px] mx-auto mt-2'>
           
                <form action="{{route('Asientos.index')}}" method='get'> 
                    <button type='submit' class='btn btn-primary w-[150px] mb-2 ml-1'>Asientos de Diario</button>
                </form>

                <form action="{{route('PanelConsultasContabilidad')}}" method='get'>
                   <button type='submit' class='btn btn-secondary w-[150px]'>Consultas</button>
               </form>

               <form action="">
                   <button type='submit' class='btn btn-info w-[150px] mb-2 ml-1'>Cuentas Bancarias</button>
              </form>

              <form action="">
               <button type='submit' class='btn btn-success w-[150px]'>Reportes</button>
              </form>

              <form action="{{route('logout')}}" method='get'>
                <button type='submit' class='btn btn-danger w-[150px]'>Salir</button>
               </form>
              
            </div>
        </div>
    </div><!--- fin div card ---->

</div><!---fin div principal --->

<style>
    /* Media Queries para responsividad */
    @media (max-width: 1024px) {
        .card {
            width: 90% !important;
        }

        .w-[320px] {
            width: 95% !important;
        }

        .w-[150px] {
            width: 45% !important;
            margin-left: auto !important;
            margin-right: auto !important;
            display: block;
        }
    }

    @media (max-width: 768px) {
        .grid-cols-2 {
            grid-template-columns: 1fr !important;
        }

        .w-[150px] {
            width: 80% !important;
            margin-left: auto !important;
            margin-right: auto !important;
        }

        .mb-2 {
            margin-bottom: 0.5rem !important;
        }

        .ml-1 {
            margin-left: 0 !important;
        }
    }

    @media (max-width: 480px) {
        .card-header {
            font-size: 1rem !important;
        }

        button {
            font-size: 0.875rem !important;
            padding: 0.5rem !important;
        }
    }
</style>

@endsection
