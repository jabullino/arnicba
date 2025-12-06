@extends('layouts.app')
@section('content')
<form action="{{ route('movimientos.importar') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class='card w-[480px] center mx-auto text-white bold'>
        <div class='card-header text-center bg-sky-900'>
            FORMULARIO PARA EL CARGADO DE ARCHIVOS CSV
        </div>
        <div class='form-group bg-white-900 p-4'>
            <div class='mb-1'>
                <label for='banco' class='text-sky-900 bold'>Banco</label>
                <select id="banco" name="banco" class='form-control'>
                    <option value="default">Seleccionar Banco</option>
                    @foreach ($banco as $ban)
                        <option value="{{ $ban->id }}">{{ $ban->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class='mt-1'>
                <label for='cuenta' class='text-sky-900 bold'>Cuenta</label>
                <select id="cuenta" name="cuenta" class='form-control text-black'></select>
            </div>

            <div class='form-group mt-2'>
                <input type="file" name="archivo" class='form-control' accept=".csv" required>
                <button type="submit" class='form-control bg-sky-900 text-white bold mt-4'>Importar CSV</button>
            </div>
        </div>
    </div>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#banco').on('change', function () {
        
        var bancoId = $(this).val();
        var cuentaSelect = $('#cuenta');
         
        cuentaSelect.html('<option value="">Cargando...</option>');
        
        if (bancoId) {
            $.ajax({
                url: 'cuentas-por-banco/' + bancoId,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    cuentaSelect.empty();
                    cuentaSelect.append('<option value="">Seleccione una cuenta</option>');
                    $.each(data, function (key, cuenta) {
                        cuentaSelect.append('<option value="' + cuenta.id + '">' + cuenta.numcuenta + '</option>');
                    });
                },
                error: function () {
                    cuentaSelect.html('<option value="">Error al cargar</option>');
                }
            });
        } else {
            cuentaSelect.html('<option value="">Seleccione una cuenta</option>');
        }
    });
</script>

<style>
    /* ------------------ Responsive ------------------ */
    @media (max-width: 768px) {
        .card {
            width: 90% !important;
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
        label {
            font-size: 0.9rem;
        }
    }

    @media (max-width: 480px) {
        .card {
            width: 95% !important;
        }
        .card-header {
            font-size: 0.9rem;
        }
        .form-control {
            font-size: 0.8rem;
            padding: 0.35rem;
        }
        button.form-control {
            font-size: 0.8rem;
            padding: 0.4rem;
        }
        label {
            font-size: 0.8rem;
        }
    }
</style>
@endsection
