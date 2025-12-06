@extends('layouts.app')

@section('content')
<!-- SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Media queries para responsive */
        @media (max-width: 1024px) {
            #principal {
                padding: 0 1rem;
            }

            .card {
                width: 100% !important;
                max-width: 100% !important;
            }

            .grid {
                display: block !important;
            }

            .grid>div {
                width: 100% !important;
                margin-bottom: 0.5rem;
            }

            input,
            select,
            button {
                width: 100% !important;
                box-sizing: border-box;
            }

            .form-group.mb-24 {
                margin-bottom: 1.5rem !important;
            }
        }

        @media (max-width: 640px) {
            button {
                font-size: 0.875rem !important;
                padding: 0.5rem !important;
            }

            #titulo {
                font-size: 0.875rem !important;
                padding: 0.5rem !important;
            }

            .card-body p,
            label {
                font-size: 0.875rem !important;
            }
        }

        @media print {
            /* Ocultar solo lo que no se imprime */

            #personal,
            #labelpersonal,
            #firmas,
            #imprimirformulario,
            #autorizar {
                display: none !important;
            }

            /* Mostrar la copia */
            #copia {
                display: block !important;
                visibility: visible !important;
                position: relative !important;
            }

            /* Forzar que la copia mantenga el estilo de grid en dos columnas */
            #divini {
                display: grid !important;
                grid-template-columns: 1fr 1fr !important;
                gap: 4mm !important;
            }

            #divini .form-group {
                width: auto !important;
                margin-bottom: 0.5rem !important;
            }

            #inferior {
                display: grid !important;
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 1rem !important;
                text-align: center;
            }

            #inferior>.form-group {
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
            }

            /* Inputs y labels en negro */
            input,
            label,
            #titulo,
            #institucion,
            #labelgestion {
                color: black !important;
            }

            #inferior>.form-group input.form-control {
                width: 60% !important;
                max-width: 80% !important;
                margin-bottom: 0.25rem !important;
                text-align: center !important;
                color: black !important;
            }

            #inferior>.form-group div.text-center {
                width: 100% !important;
                color: black !important;
                margin: 0 !important;
            }
        }
    </style>

    <div id="principal" class="container mx-auto mt-6">
        <form action='{{ route('autorizaVacaciones') }}' method='post'>
            @csrf
            <div class="card mx-auto" style="max-width:600px">
                <div id='titulo' class="card-header bg-sky-900 text-white font-bold text-center p-2">
                    FORMULARIO DE SOLICITUD DE VACACIÓN</BR>
                    ORIGINAL
                    @if ($errors->any())
                        <div style="color:red; margin-top:10px;">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('diasAgotados'))
                        <div class="alert alert-success text-center bg-red-700 text-white text-center font-bold">
                            {{ session('diasAgotados') }}
                        </div>
                    @endif
                    @if (session('diasAutorizados'))
                        <div class="alert alert-success text-center bg-red-700 text-white text-center font-bold">
                            {{ session('diasAutorizados') }}
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success text-center bg-red-700 text-white text-center font-bold">
                            {{ session('diasAutorizados') }}
                        </div>
                    @endif
                    @if (session('diasInsuficientes'))
                        <div class="alert alert-success text-center bg-red-700 text-white text-center font-bold">
                            {{ session('diasInsuficientes') }}
                        </div>
                    @endif
                </div>
                @if (session('success'))
                    <script>
                        Swal.fire({
                            title: '¡Éxito!',
                            text: "{{ session('success') }}",
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        });
                    </script>
                @endif

                <div class="card-body p-4">
                    <div class="form-group mb-4">
                        <label id='labelpersonal' for="personal" class="block mb-1">Personal</label>
                        <select name="personal" id="personal" class="form-control w-full border p-2 rounded">
                            <option value="default">Escoja Personal</option>
                            @foreach ($personal as $persona)
                                <option value="{{ $persona->id }}">
                                    {{ $persona->nombre }} {{ $persona->apellido }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="elementos" class= "mb-2">
                        <div id="institucion" class="col-span-2 bg-sky-900 text-white text-center font-bold p-2 rounded">
                            ONG ROSA DE SARÓN
                        </div>

                        <div class="form-group mb-2 col-span-2 bg-sky-900 !text-white text-center font-bold p-2 rounded">
                            <label id='labelgestion' for="gestion" class="block mb-1 text-white">GESTION</label>
                            <input type="text" name="gestion" class="form-control w-full text-center font-bold"
                                value={{ $gestion }} readonly>
                        </div>

                        <div id="divini" class='grid grid-cols-2'><!--inicio divinii-->


                            <div id='nom' class="form-group mb-2">
                                <label for="nombre" class="block mb-1">Nombre</label>
                                <input type="text" name="nombre" class="form-control w-full border p-2 rounded"
                                    readonly>
                            </div>

                            <div id=ape class="form-group mb-2">
                                <label for="apellido" class="block mb-1">Apellido</label>
                                <input type="text" name="apellido" class="form-control w-full border p-2 rounded"
                                    readonly>
                            </div>

                            <div id='cod' class="form-group mb-2">
                                <label for="codigo" class="block mb-1">Código</label>
                                <input type="text" name="codigo" class="form-control w-full border p-2 rounded"
                                    readonly>
                            </div>

                            <div id ='car' class="form-group mb-2">
                                <label for="cargo" class="block mb-1">Cargo</label>
                                <input type="text" name="cargo" class="form-control w-full border p-2 rounded"
                                    readonly>
                            </div>

                            <div id='fec' class="form-group mb-2">
                                <label for="fecingreso" class="block mb-1">Fecha de Ingreso</label>
                                <input type="text" name="fecingreso" class="form-control w-full border p-2 rounded"
                                    readonly>
                            </div>

                            <div id='fecsol'class="form-group mb-2">
                                <label for="fecsolicitud" class="block mb-1">Fecha de Solicitud</label>
                                <input type="date" name="fecsolicitud" id='fecsolicitud'
                                    class="form-control w-full border p-2 rounded" min="{{ date('Y-m-d') }}">
                            </div>

                            <div id='fecini' class="form-group mb-2">
                                <label for="fecinicio" class="block mb-1">Fecha de Inicio Vacación</label>
                                <input type="date" name="fecinicio" id='fecinicio'
                                    class="form-control w-full border p-2 rounded">
                            </div>

                            <div id='fecfin'class="form-group mb-24">
                                <label for="fecfin" class="block mb-1">Fecha Final de Vacación</label>
                                <input type="date" name="fecfin" id='fecfin'
                                    class="form-control w-full border p-2 rounded">
                            </div>

                            <div id='tot' class="form-group mb-2">
                                <label for="totaldias" class="block mb-1">Total Días Solicitados</label>
                                <input type="text" name="totaldias" id='totaldias'
                                    class="form-control w-full border p-2 rounded text-black">
                            </div>

                            <div id='firmsol'class="form-group mb-2">
                                <label for="firmasolicitante" class="block mb-1 text-center">Firma del Solicitante</label>
                                <input type="text" name="firmasolicitante"
                                    class="form-control w-full border p-2 rounded text-center" readonly>
                            </div>

                        </div><!---fin diviini--->
                        <div id="inferior" class='grid grid-cols-2'>
                            <div class="form-group mb-2">
                                <input type="text" name="firmaAdministrador"
                                    class="form-control w-full border p-2 rounded" readonly>
                                @php
                                    $nombreCargo = $cargo->devuelveNombreCargoAdministrador();
                                    $nombreAdministrador = $user->devuelveNombreAdministrador();
                                @endphp
                                <div class='text-center font-bold'>
                                    {{ $nombreAdministrador->nombre }} {{ $nombreAdministrador->apellido }}
                                    <br>
                                    <span class="cargo">{{ $nombreCargo }}</span>
                                </div>
                            </div>

                            <div class="form-group mb-2">
                                <input type="text" name="firmadireccion"
                                    class="form-control w-full border p-2 rounded" readonly>
                                @php
                                    $nombreCargo = $cargo->devuelveNombreCargoDirector();
                                    $nombreAdministrador = $user->devuelveNombreDirector();
                                @endphp
                                <div class='text-center font-bold'>
                                    {{ $nombreAdministrador->nombre }} {{ $nombreAdministrador->apellido }}
                                    <br>
                                    <span class="cargo">{{ $nombreCargo }}</span>
                                </div>
                            </div>

                            <div id='firmas' class="form-group mb-2 col-span-2 grid grid-cols-2 col-span-2">
                                <div class='col-span-2'>
                                    <button type="submit" name="autorizar" id='autorizar'
                                        class="w-full bg-sky-900 text-white font-bold mt-6 rounded h-12 col-span-2">
                                        Autorizar
                                    </button>
                                </div>
                            </div>
                        </div><!--fin div inferior--->
                    </div>
                </div>
            </div>
        </form>
    </div>
    </div>

    <!-------------Aqui empieza la copia---------------->
    <div id="copia" style='display:none'>

        <div class="card mx-auto" style="max-width:600px">
            <div id='titulo' class="card-header bg-sky-900 text-white font-bold text-center p-2">
                FORMULARIO DE SOLICITUD DE VACACIÓN</BR>
                COPIA
                @if ($errors->any())
                    <div style="color:red; margin-top:10px;">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('diasAgotados'))
                    <div class="alert alert-success text-center bg-red-700 text-white text-center font-bold">
                        {{ session('diasAgotados') }}
                    </div>
                @endif
                @if (session('diasAutorizados'))
                    <div class="alert alert-success text-center bg-red-700 text-white text-center font-bold">
                        {{ session('diasAutorizados') }}
                    </div>
                @endif
                @if (session('diasInsuficientes'))
                    <div class="alert alert-success text-center bg-red-700 text-white text-center font-bold">
                        {{ session('diasInsuficientes') }}
                    </div>
                @endif
            </div>

            <div class="card-body p-4">
                <div class="form-group mb-4">
                    <label id='labelpersonal' for="personal" class="block mb-1">Personal</label>
                    <select name="personal" id="personal" class="form-control w-full border p-2 rounded">
                        <option value="default">Escoja Personal</option>
                        @foreach ($personal as $persona)
                            <option value="{{ $persona->id }}">
                                {{ $persona->nombre }} {{ $persona->apellido }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div id="elementos" class= "mb-2">
                    <div id="institucion" class="col-span-2 bg-sky-900 text-white text-center font-bold p-2 rounded">
                        ONG ROSA DE SARÓN
                    </div>

                    <div class="form-group mb-2 col-span-2 bg-sky-900 !text-white text-center font-bold p-2 rounded">
                        <label id='labelgestion' for="gestion" class="block mb-1 text-white">GESTION</label>
                        <input type="text" name="gestion" class="form-control w-full text-center font-bold"
                            value={{ $gestion }} readonly>
                    </div>

                    <div id="divini" class='grid grid-cols-2'><!--inicio divinii-->


                        <div id='nom' class="form-group mb-2">
                            <label for="nombre" class="block mb-1">Nombre</label>
                            <input type="text" name="nombre" class="form-control w-full border p-2 rounded" readonly>
                        </div>

                        <div id=ape class="form-group mb-2">
                            <label for="apellido" class="block mb-1">Apellido</label>
                            <input type="text" name="apellido" class="form-control w-full border p-2 rounded"
                                readonly>
                        </div>

                        <div id='cod' class="form-group mb-2">
                            <label for="codigo" class="block mb-1">Código</label>
                            <input type="text" name="codigo" class="form-control w-full border p-2 rounded" readonly>
                        </div>

                        <div id ='car' class="form-group mb-2">
                            <label for="cargo" class="block mb-1">Cargo</label>
                            <input type="text" name="cargo" class="form-control w-full border p-2 rounded" readonly>
                        </div>

                        <div id='fec' class="form-group mb-2">
                            <label for="fecingreso" class="block mb-1">Fecha de Ingreso</label>
                            <input type="text" name="fecingreso" class="form-control w-full border p-2 rounded"
                                readonly>
                        </div>

                        <div id='fecsol'class="form-group mb-2">
                            <label for="fecsolicitud" class="block mb-1">Fecha de Solicitud</label>
                            <input type="date" name="fecsolicitud" id='fecsolicitud'
                                class="form-control w-full border p-2 rounded" min="{{ date('Y-m-d') }}">
                        </div>

                        <div id='fecini' class="form-group mb-2">
                            <label for="fecinicio" class="block mb-1">Fecha de Inicio Vacación</label>
                            <input type="date" name="fecinicio" id='fecinicio'
                                class="form-control w-full border p-2 rounded">
                        </div>

                        <div id='fecfin'class="form-group mb-24">
                            <label for="fecfin" class="block mb-1">Fecha Final de Vacación</label>
                            <input type="date" name="fecfin" id='fecfin'
                                class="form-control w-full border p-2 rounded">
                        </div>

                        <div id='tot' class="form-group mb-2">
                            <label for="totaldias" class="block mb-1">Total Días Solicitados</label>
                            <input type="text" name="totaldias" id='totaldias'
                                class="form-control w-full border p-2 rounded text-black">
                        </div>

                        <div id='firmsol'class="form-group mb-2">
                            <label for="firmasolicitante" class="block mb-1 text-center">Firma del Solicitante</label>
                            <input type="text" name="firmasolicitante" class="form-control w-full border p-2 rounded text-center"
                                readonly>
                        </div>

                    </div><!---fin diviini--->
                    <div id="inferior" class='grid grid-cols-2'>
                        <div class="form-group mb-2">
                            <input type="text" name="firmaAdministrador"
                                class="form-control w-full border p-2 rounded" readonly>
                            @php
                                $nombreCargo = $cargo->devuelveNombreCargoAdministrador();
                                $nombreAdministrador = $user->devuelveNombreAdministrador();
                            @endphp
                            <div class='text-center font-bold'>
                                {{ $nombreAdministrador->nombre }} {{ $nombreAdministrador->apellido }}
                                <br>
                                <span class="cargo">{{ $nombreCargo }}</span>
                            </div>
                        </div>

                        <div class="form-group mb-2">
                            <input type="text" name="firmadireccion" class="form-control w-full border p-2 rounded"
                                readonly>
                            @php
                                $nombreCargo = $cargo->devuelveNombreCargoDirector();
                                $nombreAdministrador = $user->devuelveNombreDirector();
                            @endphp
                            <div class='text-center font-bold'>
                                {{ $nombreAdministrador->nombre }} {{ $nombreAdministrador->apellido }}
                                <br>
                                <span class="cargo">{{ $nombreCargo }}</span>
                            </div>
                        </div>
                    </div><!--fin div inferior--->
                </div>
            </div>
        </div>


    </div><!-------fin div copia ------------------------>


    <!-----------Aquí termina la copia ----------------->
@endsection

@section('js')
    <script>
        document.getElementById('personal').addEventListener('change', function() {
            const userId = this.value;
            if (userId && userId !== 'default') {
                fetch(`{{ url('datosusuariovacacion') }}/${userId}`)
                    .then(response => response.json())
                    .then(data => {
                        document.querySelector("input[name='gestion']").value = data.gestion ?? '';
                        document.querySelector("input[name='nombre']").value = data.nombre ?? '';
                        document.querySelector("input[name='apellido']").value = data.apellido ?? '';
                        document.querySelector("input[name='fecingreso']").value = data.fecingreso ?? '';
                        document.querySelector("input[name='cargo']").value = data.cargo ?? '';
                        document.querySelector("input[name='codigo']").value = data.codigo ?? '';
                        document.querySelector("input[name='firmasolicitante']").value = (data.nombre ?? '') +
                            ' ' + (data.apellido ?? '');
                    }).catch(err => alert('No se pudo obtener los datos del usuario.'));
            } else {
                ["gestion", "nombre", "apellido", "fecingreso", "cargo", "codigo", "firmasolicitante"].forEach(
                    f => {
                        document.querySelector(`input[name='${f}']`).value = '';
                    });
            }
        });


        document.addEventListener('DOMContentLoaded', function() {
            const fecInicio = document.querySelector('input[name="fecinicio"]');
            const fecFin = document.querySelector('input[name="fecfin"]');
            const totalDias = document.querySelector('input[name="totaldias"]');

            if (!fecInicio || !fecFin || !totalDias) {
                console.warn('No se encontraron los inputs fecinicio / fecfin / totaldias');
                return;
            }

            // Evitar edición manual del total
            totalDias.setAttribute('readonly', 'readonly');

            // Inicial: bloquear fecfin si no hay inicio
            fecFin.disabled = !fecInicio.value;

            // Función para parsear yyyy-mm-dd en milisegundos UTC (evita problemas de zona horaria)
            function dateStringToUTCms(s) {
                const [y, m, d] = s.split('-').map(v => parseInt(v, 10));
                return Date.UTC(y, m - 1, d);
            }

            function calcularDias() {
                if (!fecInicio.value || !fecFin.value) {
                    totalDias.value = '';
                    return;
                }

                const inicioMs = dateStringToUTCms(fecInicio.value);
                const finMs = dateStringToUTCms(fecFin.value);

                if (finMs < inicioMs) {
                    alert('La fecha final no puede ser anterior a la fecha de inicio.');
                    fecFin.value = '';
                    totalDias.value = '';
                    return;
                }

                const msPorDia = 24 * 60 * 60 * 1000;
                // +1 para incluir ambos días (si prefieres excluir el día inicial, quita el +1)
                const diffDias = Math.floor((finMs - inicioMs) / msPorDia) + 1;

                totalDias.value = diffDias;
            }

            // Al cambiar inicio: activar fecfin, fijar min y recalcular si corresponde
            fecInicio.addEventListener('change', function() {
                if (!fecInicio.value) {
                    fecFin.disabled = true;
                    fecFin.value = '';
                    totalDias.value = '';
                    return;
                }
                fecFin.disabled = false;
                fecFin.min = fecInicio.value; // evita selección manual anterior con el datepicker
                if (fecFin.value && fecFin.value < fecInicio.value) {
                    fecFin.value = '';
                    totalDias.value = '';
                } else {
                    calcularDias();
                }

            });


            const btnAutorizar = document.getElementById('autorizar');
            const divCopia = document.getElementById('copia');

            if (btnAutorizar && divCopia) {
                btnAutorizar.addEventListener('click', function(event) {
                    // Evita enviar el formulario todavía

                    // Copiar valores del formulario original a la copia
                    const campos = [
                        'gestion', 'nombre', 'apellido', 'fecingreso',
                        'cargo', 'codigo', 'fecsolicitud', 'fecinicio',
                        'fecfin', 'totaldias', 'firmasolicitante',
                        'firmaAdministrador', 'firmadireccion'
                    ];

                    campos.forEach(name => {
                        const inputOriginal = document.querySelector(
                            `#principal input[name='${name}'], #principal select[name='${name}']`
                            );
                        const inputCopia = document.querySelector(
                            `#copia input[name='${name}'], #copia select[name='${name}']`);
                        if (inputOriginal && inputCopia) {
                            inputCopia.value = inputOriginal.value;
                        }
                    });

                    // Mostrar la copia
                    divCopia.style.display = 'block';

                    // Abrir vista de impresión
                    //  window.print();

                    // Opcional: ocultar la copia después de imprimir
                    // divCopia.style.display = 'none';
                });
            }

            // Evitar foco en fecfin si no existe inicio
            fecFin.addEventListener('focus', function() {
                if (!fecInicio.value) {
                    alert('Primero selecciona la Fecha de Inicio.');
                    fecInicio.focus();
                }
            });

            // Al cambiar fecha final recalcular
            fecFin.addEventListener('change', calcularDias);
        });
        // Botón imprimir
    </script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            confirmButtonText: 'Aceptar'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: '{{ session('error') }}',
            confirmButtonText: 'Aceptar'
        });
    @endif

    @if(session('warning'))
        Swal.fire({
            icon: 'warning',
            title: '¡Advertencia!',
            text: '{{ session('warning') }}',
            confirmButtonText: 'Aceptar'
        });
    @endif

    @if(session('info'))
        Swal.fire({
            icon: 'info',
            title: 'Información',
            text: '{{ session('info') }}',
            confirmButtonText: 'Aceptar'
        });
    @endif
});
</script>
@endsection
