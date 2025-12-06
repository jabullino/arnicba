@extends('layouts.app')
@section('content')
    @csrf

    <div id='principal'>
        <form action="{{route('PagoSueldoIndividual.store')}}" method='post'>
            @csrf

            <div class='card'>
                <div id='errores' class='bg-red-700 text-white text-center bold'>
                    @if ($errors->has('personal'))
                        <div class='bg-red-700 text-white text-center bold'>
                            {{ $errors->first('personal') }}
                        </div>
                    @endif

                    @if ($errors->has('mespago'))
                        <div class='bg-red-700 text-white text-center bold'>
                            {{ $errors->first('mespago') }}
                        </div>
                    @endif
                   <div id='sueldopagado'>
                        @if (session('sueldospagado'))
                            <div class="alert alert-success  bg-red-700 text-white bold text-center">
                                {{ session('sueldopagado') }}

                            </div>
                        @endif
                    </div>
                    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

                </div>
                <div class='card-header bg-sky-900 text-white text-center text-bold text-center'>
                    FORMULARIO DE PAGO DE SUELDOS INDIVIDUAL
                     @if (session('sueldopagado'))
            <div class="alert alert-success text-center">
            {{ session('sueldopagado') }}
            </div>
        @endif
                </div>
                <div class='card-body'>
                    <div id='div-interno' class='grid grid-cols-2 gap-1 w-[1000px]'>
                        <div class='form-group mb-2 colspan-2'>
                            <label for="personal">Personal</label>
                            <select name="personal" id="personal" class='form-control cols-2 block'>
                                <option value="default">Escoja un Personal</option>
                                @foreach ($user as $usr)
                                    <option value="{{ $usr->id }}">{{ $usr->nombre }}&nbsp;{{ $usr->apellido }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="idUsuario" id="idUsuario" value='{{$usr->id}}'>
                        </div>
                        <div class='form-group mb-2'>
                            <label for="nombre" class='text-black'>Nombre</label>
                            <input type="text" class='form-control' name="nombre" id="nombre" readonly>
                        </div>
                        <div class='form-group mb-2'>
                            <label for="apellido" class='text-black'>Apellido</label>
                            <input type="text" class='form-control' name="apellido" id="apellido" readonly>
                        </div>


                        <div class='form-group mb-2'>
                            <label for="codigo" class='text-black'>Codigo</label>
                            <input type="text" class='form-control' name="codigo" id="codigo" readonly>
                        </div>
                        <div class='form-group mb-2 w-full grid-cols-2'>
                            <label for="mespago">Fecha de pago</label>
                            <input type="date" class='form-control grid-cols-2 ' name="mespago" id="mespago">
                        </div>
                        </br>
                        <div id='desgloseSueldo' class='mx-auto'>
                            <div class='bg-sky-900 text-white text-center font-bold'>
                                DETALLE DE SUELDO
                            </div>
                            <div class='form-group mb-2 col-12'>
                                <label for="haberbasico">Haber Básico</label>
                                <input type="text" class='form-control text-right' name="haberbasico" id="haberbasico"
                                    readonly>
                            </div>
                            <div class='form-group mb-2'>
                                <label for="bonoantiguedad">Bono de Antigüedad</label>
                                <input type="text" class='form-control text-right' name="bonoantiguedad"
                                    id="bonoantiguedad" readonly>
                            </div>
                            <div class='form-group mb-2'>
                                <label for="subtotal">Total Ganado</label>
                                <input type="text" class='form-control text-right' name="subtotal" id="subtotal"
                                    readonly>
                            </div>
                            <div class='form-group mb-2'>
                                <label for="gestora">Descuento Gestora</label>
                                <input type="text" class='form-control text-right' name="gestora" id="gestora"
                                    readonly>
                            </div>
                            <div class='form-group mb-2'>
                                <label for="total">Total a Pagar</label>
                                <input type="text" class='form-control text-right' name="total" id="total"
                                    readonly>
                            </div>

                        </div><!-----fin div desgloseSueldo----->
                        <div id='bonos-descuentos' class='grid grid-cols-1 grid-rows-2'>
                            <div class=''>
                                <div class='w-full bg-sky-900 text-white text-bold text-center col-span-3'>
                                    BONOS
                                </div>

                                <div class="flex items-center space-x-2 w-[300px]">
                                    <label for="montoBono">Monto Bono</label>
                                    <input type="text" class='bg-gray-300  text-right text-black' style='margin-left:55px;' name="montoBono"
                                        id="montoBono">
                                </div>


                            </div>
                            <div class=''>
                                <div class='w-full bg-sky-900 text-white text-bold text-center col-span-3'>
                                    DESCUENTOS
                                </div>
                                @foreach ($descuentos as $des)
                                    <div class="flex items-center space-x-2 w-[300px] mb-1">
                                       <input type="checkbox" name="escogidos[]" value="{{ $des->id }}">
                                        &nbsp;
                                        <label for="{{ $des->id }}">{{ $des->nombre }}</label>
                                        @if ($des->id == 1)
                                            <input type="text" name="{{ $des->id }}" class='bg-gray-300 text-right text-black'
                                                id="{{ $des->id }}" value='' style='margin-left:62px'>
                                        @elseif($des->id == 4)
                                            <input type="text" name="{{ $des->id }}" class='bg-gray-300 text-right text-black'
                                                id="{{ $des->id }}" value='' style='margin-left:75px'>
                                        @else
                                            <input type="text" name="{{ $des->id }}"
                                                class='bg-gray-300 ml-2 text-right text-black' id="{{ $des->id }}"
                                                value=''>
                                        @endif
                                    </div>
                                @endforeach

                            </div>
                        </div><!----fin bonos-descuentos---->

                    </div><!---- fin div interno ---->
                    <div>
                        <button type="submit"
                            class='bg-sky-900 text-center text-white font-bold rounded w-full'>Registrar</button>
                    </div>
                </div><!---- fin div card-body ------>

            </div><!-----fin div card ---->
        </form>
    </div><!----fin div principal ----->
@endsection

@section('js')
    <script>
        document.getElementById('personal').addEventListener('change', function() {
            const userId = this.value;
            document.getElementById('idUsuario').value = userId;
            if (!userId) {
                // Si no selecciona nada, limpio los campos
                document.getElementById('nombre').value = '';
                document.getElementById('apellido').value = '';
                document.getElementById('codigo').value = '';

                return;
            }

            fetch(`/datosusuariosueldoindividual/${userId}`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('nombre').value = data.nombre || '';
                    document.getElementById('apellido').value = data.apellido || '';
                    document.getElementById('codigo').value = data.codigo || '';


                })
                .catch(err => {
                    console.error(err);
                    alert('No se pudo obtener los datos del usuario');
                });
        });
    </script>

    <script>
        document.getElementById('mespago').addEventListener('change', function() {
            const mesPago = this.value;
            const userId = document.getElementById('idUsuario').value;

            if (!userId) {
                document.getElementById('haberbasico').value = '';
                document.getElementById('bonoantiguedad').value = '';
                document.getElementById('subtotal').value = '';
                document.getElementById('gestora').value = '';
                document.getElementById('total').value = '';
                return;
            }

            const url = `/datosusuariosueldonumeros/${encodeURIComponent(mesPago)}/${encodeURIComponent(userId)}`;

            fetch(url)
                .then(async res => {
                    const json = await res.json().catch(() => null);
                    if (!res.ok) {
                        console.error('Respuesta no OK', res.status, json);
                        throw json || {
                            error: 'Respuesta no OK',
                            status: res.status
                        };
                    }
                    return json;
                })
                .then(data => {
                    document.getElementById('haberbasico').value = data.haberbasico ?? '';
                    document.getElementById('bonoantiguedad').value = data.bonoantiguedad ?? '';
                    document.getElementById('subtotal').value = data.subtotal ?? '';
                    document.getElementById('gestora').value = data.gestora ?? '';
                    document.getElementById('total').value = data.total ?? '';
                })
                .catch(err => {
                    console.error('Error al obtener datos del sueldo:', err);
                    alert(err.error ||
                        'No se pudo obtener los datos — revisa la consola y storage/logs/laravel.log');
                });
        });
    </script>
    <script>
        document.getElementById("montoBono").addEventListener("keydown", function(e) {
            // Teclas permitidas de control: borrar, flechas, tab, home, end
            const controlKeys = [
                "Backspace", "Tab", "ArrowLeft", "ArrowRight", "Delete", "Home", "End"
            ];
            if (controlKeys.includes(e.key)) return;

            // Permitir un solo punto decimal
            if (e.key === "." && !this.value.includes(".")) return;

            // Permitir números 0-9
            if (e.key >= "0" && e.key <= "9") return;

            // Bloquear todo lo demás
            e.preventDefault();
        });

        document.getElementById("1").addEventListener("keydown", function(e) {
            // Teclas permitidas de control: borrar, flechas, tab, home, end
            const controlKeys = [
                "Backspace", "Tab", "ArrowLeft", "ArrowRight", "Delete", "Home", "End"
            ];
            if (controlKeys.includes(e.key)) return;

            // Permitir un solo punto decimal
            if (e.key === "." && !this.value.includes(".")) return;

            // Permitir números 0-9
            if (e.key >= "0" && e.key <= "9") return;

            // Bloquear todo lo demás
            e.preventDefault();
        });
        document.getElementById("2").addEventListener("keydown", function(e) {
            // Teclas permitidas de control: borrar, flechas, tab, home, end
            const controlKeys = [
                "Backspace", "Tab", "ArrowLeft", "ArrowRight", "Delete", "Home", "End"
            ];
            if (controlKeys.includes(e.key)) return;

            // Permitir un solo punto decimal
            if (e.key === "." && !this.value.includes(".")) return;

            // Permitir números 0-9
            if (e.key >= "0" && e.key <= "9") return;

            // Bloquear todo lo demás
            e.preventDefault();
        });
        document.getElementById("3").addEventListener("keydown", function(e) {
            // Teclas permitidas de control: borrar, flechas, tab, home, end
            const controlKeys = [
                "Backspace", "Tab", "ArrowLeft", "ArrowRight", "Delete", "Home", "End"
            ];
            if (controlKeys.includes(e.key)) return;

            // Permitir un solo punto decimal
            if (e.key === "." && !this.value.includes(".")) return;

            // Permitir números 0-9
            if (e.key >= "0" && e.key <= "9") return;

            // Bloquear todo lo demás
            e.preventDefault();
        });
        document.getElementById("4").addEventListener("keydown", function(e) {
            // Teclas permitidas de control: borrar, flechas, tab, home, end
            const controlKeys = [
                "Backspace", "Tab", "ArrowLeft", "ArrowRight", "Delete", "Home", "End"
            ];
            if (controlKeys.includes(e.key)) return;

            // Permitir un solo punto decimal
            if (e.key === "." && !this.value.includes(".")) return;

            // Permitir números 0-9
            if (e.key >= "0" && e.key <= "9") return;

            // Bloquear todo lo demás
            e.preventDefault();
        });

        
        function parseNumero(valor) {
            return parseFloat(valor.replace(/,/g, "")) || 0;
        }

         let totalgeneral = 0;
        document.getElementById('montoBono').addEventListener('blur', function() {

            if (totalgeneral === 0) {
                let haberbasico = parseNumero(document.getElementById('haberbasico').value);
                let bono = parseNumero(document.getElementById('montoBono').value);
                let bonoantiguedad = parseNumero(document.getElementById('bonoantiguedad').value);
                totalgeneral = haberbasico + bono;
                document.getElementById('haberbasico').value = totalgeneral;
                let nuevosubtotal = haberbasico + bono + bonoantiguedad;
                document.getElementById('subtotal').value = nuevosubtotal;
                descuentogestora = (nuevosubtotal) * 0.1271;
                document.getElementById('gestora').value = descuentogestora.toFixed(2);
                document.getElementById('total').value = (nuevosubtotal - descuentogestora).toFixed(2);
            } else {

                let bono = parseNumero(document.getElementById('montoBono').value);
                totalgeneral=totalgeneral+bono;
                document.getElementById('haberbasico').value = totalgeneral;
                haberbasico=totalgeneral;
                let bonoantiguedad = parseNumero(document.getElementById('bonoantiguedad').value);
                let nuevosubtotal = haberbasico + bonoantiguedad;
                document.getElementById('subtotal').value = nuevosubtotal;
                descuentogestora = (nuevosubtotal) * 0.1271;
                document.getElementById('gestora').value = descuentogestora.toFixed(2);
                document.getElementById('total').value = (nuevosubtotal - descuentogestora).toFixed(2);

            }

        });
        

        
        document.getElementById('1').addEventListener('blur', function() {
            if (totalgeneral === 0) {

                let descuento = parseNumero(document.getElementById('1').value);
                totalgeneral=parseNumero(document.getElementById('haberbasico').value);
                totalgeneral=totalgeneral-descuento;
                haberbasico=totalgeneral;
                document.getElementById('haberbasico').value=haberbasico;
                let bonoantiguedad = parseNumero(document.getElementById('bonoantiguedad').value);
                let nuevosubtotal = haberbasico + bonoantiguedad;
                document.getElementById('subtotal').value = nuevosubtotal;
                descuentogestora = (nuevosubtotal) * 0.1271;
                document.getElementById('gestora').value = descuentogestora.toFixed(2);
                document.getElementById('total').value = (nuevosubtotal - descuentogestora).toFixed(2);
                subtotalgeneral = parseNumero(subtotalgeneral) - descuento;
            } else {
                let descuento = parseNumero(document.getElementById('1').value);
                totalgeneral = totalgeneral - descuento;
                document.getElementById('haberbasico').value = totalgeneral.toFixed(2);
                haberbasico = totalgeneral;
                let bonoantiguedad = parseNumero(document.getElementById('bonoantiguedad').value);
                let nuevosubtotal = haberbasico + bonoantiguedad;
                document.getElementById('subtotal').value = nuevosubtotal;
                descuentogestora = (nuevosubtotal) * 0.1271;
                document.getElementById('gestora').value = descuentogestora.toFixed(2);
                document.getElementById('total').value = (nuevosubtotal - descuentogestora).toFixed(2);
                subtotalgeneral = parseNumero(subtotalgeneral) - descuento;
            }

        });
        document.getElementById('2').addEventListener('blur', function() {

             if (totalgeneral === 0) {
               let descuento = parseNumero(document.getElementById('2').value);
                totalgeneral=parseNumero(document.getElementById('haberbasico').value);
                totalgeneral=totalgeneral-descuento;
                haberbasico=totalgeneral;
                document.getElementById('haberbasico').value=haberbasico;
                let bonoantiguedad = parseNumero(document.getElementById('bonoantiguedad').value);
                let nuevosubtotal = haberbasico + bonoantiguedad;
                document.getElementById('subtotal').value = nuevosubtotal;
                descuentogestora = (nuevosubtotal) * 0.1271;
                document.getElementById('gestora').value = descuentogestora.toFixed(2);
                document.getElementById('total').value = (nuevosubtotal - descuentogestora).toFixed(2);
                subtotalgeneral = parseNumero(subtotalgeneral) - descuento;
            } else {
                let descuento = parseNumero(document.getElementById('2').value);
                totalgeneral = totalgeneral - descuento;
                document.getElementById('haberbasico').value = totalgeneral.toFixed(2);
                haberbasico = totalgeneral;
                let bonoantiguedad = parseNumero(document.getElementById('bonoantiguedad').value);
                let nuevosubtotal = haberbasico + bonoantiguedad;
                document.getElementById('subtotal').value = nuevosubtotal;
                descuentogestora = (nuevosubtotal) * 0.1271;
                document.getElementById('gestora').value = descuentogestora.toFixed(2);
                document.getElementById('total').value = (nuevosubtotal - descuentogestora).toFixed(2);
                subtotalgeneral = parseNumero(subtotalgeneral) - descuento;
            }

        });
        document.getElementById('3').addEventListener('blur', function() {

             if (totalgeneral === 0) {
                let descuento = parseNumero(document.getElementById('3').value);
                totalgeneral=parseNumero(document.getElementById('haberbasico').value);
                totalgeneral=totalgeneral-descuento;
                haberbasico=totalgeneral;
                document.getElementById('haberbasico').value=haberbasico;
                let bonoantiguedad = parseNumero(document.getElementById('bonoantiguedad').value);
                let nuevosubtotal = haberbasico + bonoantiguedad;
                document.getElementById('subtotal').value = nuevosubtotal;
                descuentogestora = (nuevosubtotal) * 0.1271;
                document.getElementById('gestora').value = descuentogestora.toFixed(2);
                document.getElementById('total').value = (nuevosubtotal - descuentogestora).toFixed(2);
                subtotalgeneral = parseNumero(subtotalgeneral) - descuento;
            } else {
                let descuento = parseNumero(document.getElementById('3').value);
                totalgeneral = totalgeneral - descuento;
                document.getElementById('haberbasico').value = totalgeneral.toFixed(2);
                haberbasico = totalgeneral;
                let bonoantiguedad = parseNumero(document.getElementById('bonoantiguedad').value);
                let nuevosubtotal = haberbasico + bonoantiguedad;
                document.getElementById('subtotal').value = nuevosubtotal;
                descuentogestora = (nuevosubtotal) * 0.1271;
                document.getElementById('gestora').value = descuentogestora.toFixed(2);
                document.getElementById('total').value = (nuevosubtotal - descuentogestora).toFixed(2);
                subtotalgeneral = parseNumero(subtotalgeneral) - descuento;
            }

        });
        document.getElementById('4').addEventListener('blur', function() {

            if (totalgeneral === 0) {
                let descuento = parseNumero(document.getElementById('4').value);
                totalgeneral=parseNumero(document.getElementById('haberbasico').value);
                totalgeneral=totalgeneral-descuento;
                haberbasico=totalgeneral;
                document.getElementById('haberbasico').value=haberbasico;
                let bonoantiguedad = parseNumero(document.getElementById('bonoantiguedad').value);
                let nuevosubtotal = haberbasico + bonoantiguedad;
                document.getElementById('subtotal').value = nuevosubtotal;
                descuentogestora = (nuevosubtotal) * 0.1271;
                document.getElementById('gestora').value = descuentogestora.toFixed(2);
                document.getElementById('total').value = (nuevosubtotal - descuentogestora).toFixed(2);
                subtotalgeneral = parseNumero(subtotalgeneral) - descuento;
            } else {
                let descuento = parseNumero(document.getElementById('4').value);
                totalgeneral = totalgeneral - descuento;
                document.getElementById('haberbasico').value = totalgeneral.toFixed(2);
                haberbasico = totalgeneral;
                let bonoantiguedad = parseNumero(document.getElementById('bonoantiguedad').value);
                let nuevosubtotal = haberbasico + bonoantiguedad;
                document.getElementById('subtotal').value = nuevosubtotal;
                descuentogestora = (nuevosubtotal) * 0.1271;
                document.getElementById('gestora').value = descuentogestora.toFixed(2);
                document.getElementById('total').value = (nuevosubtotal - descuentogestora).toFixed(2);
                subtotalgeneral = parseNumero(subtotalgeneral) - descuento;
            }

        });

        function parseNumero(valor) {
            return parseFloat(valor.replace(/,/g, "")) || 0;
        }
    </script>
@endsection