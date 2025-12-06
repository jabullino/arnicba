@extends('layouts.app')
@section('content')
    <div style='display:inline-block; margin-left:350px; margin-top:48px' class='bg-sky-900'><!---div principal--->
        <div class='card w-[350px] mx-auto'>

            <div class='card-header bg-sky-900 text-white bold text-center'>
                PANEL ADMINISTRADOR 
                <br>
                @auth
                <div class="user-info">
                    <h1>BIENVENIDO </h1>
                    <br>
                    <h1>{{ auth()->user()->nombre }}&nbsp;{{ auth()->user()->apellido }}</h1>
                    <p>{{$cargoUsr}}</p>
                </div>
                @endauth
            </div>

            <div class="card-body bg-sky-900">
                <div class='bg-gray-700 sm:grid grid-cols-1 md:grid grid-cols-2 gap-4'>
                    <div class='mb-2 ml-16 mt-4'>
                        <form action="{{ route('PanelContabilidad') }}" method='get'>
                            @csrf
                            <button type='submit' class='btn btn-primary w-[115px]'>Contabilidad</button>
                        </form>
                    </div>

                    <div class='mb-2 mt-4'>
                        <form action="{{route('PersonalPendiente')}}" method='get'>
                            <button type='submit' class='btn btn-success w-[115px]'>Personal</button>
                        </form>
                    </div>

                    <div class='mb-2 ml-16'>
                        <form action="{{ route('solicitavacaciones') }}" method='get'>
                            <button type='submit' class='btn btn-info w-[115px]'>Vacaciones</button>
                        </form>
                    </div>

                    <div class='mb-2'>
                        <form action="" method=''>
                            <button type='submit' class='btn btn-danger w-[115px]'>Salir</button>
                        </form>
                    </div>
                </div>
            </div><!--- fin div card-body---->

        </div><!---fin class card---->
    </div><!---fin div principal--->

    <style>
        /* Media Queries para hacer responsive */
        @media (max-width: 1024px) {
            div[style*="margin-left:350px"] {
                margin-left: auto !important;
                margin-right: auto !important;
            }

            .card {
                width: 90% !important;
            }

            .ml-16 {
                margin-left: 0 !important;
            }
        }

        @media (max-width: 768px) {
            .grid-cols-2 {
                grid-template-columns: 1fr !important;
                gap: 12px !important;
            }

            button.btn {
                width: 100% !important;
                margin-bottom: 8px;
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 1rem !important;
            }

            p {
                font-size: 0.875rem !important;
            }
        }
    </style>
@endsection
