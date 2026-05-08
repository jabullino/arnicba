@extends('layouts.app')

@section('content')
<div class="container-fluid py-3" style="background-color: #343a40;">
    <div class="d-flex justify-content-center">
        <div class="card shadow-lg rounded-3 w-100 mx-2" style="background-color: #454d55; color: #f8f9fa; max-width: 900px;">
            <div class="card-header text-center bg-teal-900" >
                <h4 class="mb-0">Registrar Datos</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('escolaridad.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        {{-- Select Residente --}}
                        <div class="col-12 col-md-6 form-group">
                            <label for="residente" class="form-label">Residente</label>
                            <select class="form-select form-control" id="residente" name="residente" required>
                                <option value="">Seleccione un residente</option>
                                @foreach($residentes as $residente)
                                    <option value="{{ $residente->id }}">{{ $residente->nombre }} {{ $residente->apellido }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Select Gestión --}}
                        <div class="col-12 col-md-6 form-group">
                            <label for="gestion" class="form-label">Gestión</label>
                            <select class="form-select form-control" id="gestion" name="gestion" required>
                                <option value="">Seleccione una gestión</option>
                                @foreach($gestiones as $gestion)
                                    <option value="{{ $gestion->id }}">{{ $gestion->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Select Unidad Educativa --}}
                        <div class="col-12 col-md-6 form-group">
                            <label for="ueducativa" class="form-label ">Unidad Educativa</label>
                            <select class="form-select form-control" id="ueducativa" name="ueducativa" required>
                                <option value="">Seleccione una unidad educativa</option>
                                @foreach($ueducativas as $ue)
                                    <option value="{{ $ue->id }}">{{ $ue->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                         {{-- Select Curso --}}
                        <div class="col-12 col-md-6 form-group">
                            <label for="curso" class="form-label">Curso</label>
                            <select class="form-select form-control" id="curso" name="curso" required>
                                <option value="">Seleccione un curso</option>
                                @foreach($cursos as $curso)
                                    <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Select Grado --}}
                        <div class="col-12 col-md-6 form-group">
                            <label for="grado" class="form-label">Grado</label>
                            <select class="form-select form-control" id="grado" name="grado" required>
                                <option value="">Seleccione un grado</option>
                                @foreach($grados as $grado)
                                    <option value="{{ $grado->id }}">{{ $grado->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Datos RUDE --}}
                        <div class="col-12 col-md-6 form-group">
                            <label for="rude" class="form-label">RUDE</label>
                            <input 
                                type="text" 
                                name="rude" 
                                id="rude" 
                                class="form-control"
                                maxlength="70"
                            />
                        </div>    
                    </div>

                    {{-- Botón Registrar --}}
                    <div class="d-flex justify-content-center mt-4">
                        <button type="submit" class="btn btn-success bg-teal-900 px-4 py-2 rounded-pill shadow-sm">
                            Registrar
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

{{-- Ajuste visual para pantallas pequeñas --}}
<style>
    @media (max-width: 576px) {
        .card {
            border-radius: 0;
            box-shadow: none;
        }
        .card-body {
            padding: 1rem;
        }
        label {
            font-size: 0.9rem;
        }
    }
</style>
@endsection

@section('js')
{{-- SweetAlert para mostrar mensajes --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: '{{ session('success') }}',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Aceptar'
        });
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Aceptar'
        });
    </script>
@endif

{{-- Mensajes de validación --}}
@if ($errors->any())
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Campos requeridos',
            html: `
                <ul style="text-align:left;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `,
            confirmButtonColor: '#3085d6',
        });
    </script>
@endif

{{-- Solo permitir dígitos en RUDE --}}
<script>
    document.getElementById('rude').addEventListener('input', function () {

        this.value = this.value
            // Convierte letras a mayúsculas
            .toUpperCase()
            // Permite solo letras y números del 1 al 9
            .replace(/[^A-Z1-9]/g, '');

    });
</script>
@endsection
