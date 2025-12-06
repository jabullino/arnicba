@extends('layouts.app')
@section('content')
    <div><!---div principal ---->
        <div class='card w-[350px] mx-auto'><!---div card --->
            <div class='card-header text-center text-white bold w-[350px] mt-4 bg-slate-700'>
                PANEL DE CONSULTAS CONTABILIDAD
            </div>

            <div class='card-body bg-slate-700'>
                <form action="{{ route('VerificaOpcionConsulta') }}" method='POST'>
                    @csrf
                    <div class='bg-gray-500 h-[400px] w-[325px] mx-auto'>
                        <div class='mb-2 h-3'></div>
                        <div class='block w-64 h-14 mx-[40px] mb-8'>
                            <div class='text-white bold-md text-lg w-[250px] text-center bg-slate-700'>
                                FECHA DE INICIO
                            </div>
                            <div class='text-white bold-md text-lg w-[250px] text-center bg-slate-700'>
                                <input type="date" id="fecinicio" name="fecinicio"
                                    class='rounded w-[250px] text-sky-900 text-lg form-control'>
                            </div>
                            @if ($errors->has('fecinicio'))
                            <div style="color: red;" class='bg-red-500 text-white text-lg w-[250px]'>
                                {{ $errors->first('fecinicio') }}
                            </div>
                            @endif
                            <div class='text-white bold-md text-lg w-[250px] text-center bg-slate-700 mt-2'>
                                FECHA FIN
                            </div>
                            <div>
                                <input type="date" id="fecfin" name="fecfin"
                                    class='rounded w-[250px] text-sky-900 text-lg form-control'>
                            </div>
                            @if ($errors->has('fecfin'))
                                <div style="color: red;" class='bg-red-500 text-white text-lg w-[250px]'>
                                    {{ $errors->first('fecfin') }}
                                </div>
                            @endif
                        </div>

                        <div class='mt-40 mx-[38px] bg-white text-lg'>
                            <input type="radio" name="consultas" id="consultasgeneral" value="generalcuenta"> &nbsp;
                            General por cuenta y subcuenta
                            <br>
                            <input type="radio" name="consultas" id="consultaparticular" value="particularcuenta"> &nbsp;
                            Por Cuenta
                            <br>
                            <input type="radio" name="consultas" id="consultacuentaysucbcuenta"
                                value="consultacuentaysucbcuenta"> &nbsp; Por Cuenta y Subcuenta
                        </div>
                        @if ($errors->has('consultas'))
                            <div style="color: red;margin-left:40px" class='bg-red-500 text-white text-lg w-[250px]'>
                                {{ $errors->first('consultas') }}
                            </div>
                        @endif

                        <div class='mb-2 mx-[38px] form-group' style='display:none' id='cajacuenta'>
                            <label class='text-white bold' for="cuenta">Cuenta</label>
                            <select name="cuenta" id="cuenta" class='form-control text-sm'>
                                <option value="">Seleccionar una cuenta</option>
                                @foreach ($cuentas as $cue)
                                    <option value="{{ $cue->id }}">{{ $cue->nombre }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('cuenta'))
                                <div style="color: red;" class='bg-red-500 text-white text-lg w-[250px]'>
                                    {{ $errors->first('cuenta') }}
                                </div>
                            @endif
                        </div>

                        <div class='mb-2 mx-[38px] form-group' style='display:none' id='cajasubcuenta'>
                            <label for="subcuenta" class='text-white bold'>Subcuenta</label>
                            <select id="subcuenta" name="subcuenta" class='form-control' disabled>
                                <option value="">Seleccionar subcuenta</option>
                            </select>

                            @if ($errors->has('subcuenta'))
                                <div style="color: red;" class='bg-red-500 text-white text-lg w-[250px]'>
                                    {{ $errors->first('subcuenta') }}
                                </div>
                            @endif
                        </div>

                        <div class='w-full mt-2'>
                            <button type='submit'
                                class='bg-sky-900 text-white bold w-[250px] form-control mx-[38px]'>Consultar</button>
                        </div>
                </form>
            </div><!---fin div card-body----->
        </div><!--- fin div card ---->
    </div><!---fin div principal --->

    <style>
        /* Media Queries */
        @media (max-width: 1024px) {
            .card {
                width: 90% !important;
            }

            .mx-[40px], .mx-[38px] {
                margin-left: auto !important;
                margin-right: auto !important;
            }
        }

        @media (max-width: 768px) {
            .w-[250px], .w-64 {
                width: 90% !important;
            }

            button {
                width: 100% !important;
            }
        }

        @media (max-width: 480px) {
            h1, label, .text-lg {
                font-size: 0.875rem !important;
            }

            .bg-white {
                font-size: 0.75rem !important;
            }
        }
    </style>
@endsection

@section('js')
    <script>
        document.getElementById('consultaparticular').addEventListener('click', function() {
            document.getElementById("cajacuenta").style.display = "block";
        });

        document.getElementById('consultasgeneral').addEventListener('click', function() {
            document.getElementById("cajacuenta").style.display = "none";
            document.getElementById("cajasubcuenta").style.display = "none";
        });

        document.getElementById('consultacuentaysucbcuenta').addEventListener('click', function() {
            document.getElementById("cajacuenta").style.display = "block";
            document.getElementById("cajasubcuenta").style.display = "block";
        });

        document.getElementById('cuenta').addEventListener('change', function() {
            const cuentaId = this.value;
            const subcuentaSelect = document.getElementById('subcuenta');
            document.getElementById('subcuenta').disabled = false;
            subcuentaSelect.innerHTML = '<option value="">Seleccione una subcuenta</option>';
           
            if (cuentaId) {
                fetch(`/subcuentas/${cuentaId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(subcuenta => {
                            const option = document.createElement('option');
                            option.value = subcuenta.id;
                            option.textContent = subcuenta.nombre;
                            subcuentaSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error al cargar las subcuentas:', error));
            }
        });
    </script>
@endsection
