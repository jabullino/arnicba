@extends('layouts.app')

@section('page_header')
    <h1>Enviar Correos a Usuarios</h1>
@stop

@section('page_content')
    <div class="card" >
        <div class="card-header text-center text-white text-bold text-base">
            Lista de Usuarios Habilitados
        </div>
        <div class="card-body">
            {{-- Mensajes de éxito o error --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form action="{{ route('emails.send') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Checkbox para seleccionar todos --}}
                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="select_all">
                    <label class="form-check-label" for="select_all">Seleccionar Todos</label>
                </div>

                <div class="row">
                    @foreach ($users as $user)
                        <div class="col-md-6 mb-2">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input user-checkbox" name="users[]"
                                    value="{{ $user->id }}" id="user_{{ $user->id }}">
                                <label class="form-check-label" for="user_{{ $user->id }}">
                                    {{ $user->nombre }} {{ $user->apellido }} ({{ $user->email }})
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="form-group mt-3">
                    <label for="subject">Asunto del Correo</label>
                    <input type="text" name="subject" id="subject" class="form-control" required>
                </div>

                <div class="form-group mt-3">
                    <label for="message">Mensaje</label>
                    <textarea name="message" id="message" rows="6" class="form-control" required></textarea>
                </div>

                <div class="form-group mt-3">
                    <label for="attachments">Adjuntar Archivos</label>
                    <input type="file" name="attachments[]" id="attachments" class="form-control" multiple>
                    <small class="text-muted">Puedes adjuntar PDFs, imágenes u otros archivos.</small>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Enviar Correos</button>
            </form>
        </div>
    </div>
@stop

@section('css')
    <style>
       
        .form-check-label {
            margin-left: 0.5rem;
            color: #fff;
        }

        .card {
            background-color: #2c2c2c !important;
            color: #fff !important;
            border: 1px solid #444;
        }

        input.form-control,
        textarea.form-control {
            background-color: #222;
            color: #fff;
            border: 1px solid #555;
        }

        .form-check-input:checked {
            background-color: #007bff;
            border-color: #007bff;
        }
    </style>
@stop

@section('js')
    <script>
        document.getElementById('select_all').addEventListener('change', function() {
            const checked = this.checked;
            document.querySelectorAll('.user-checkbox').forEach(cb => cb.checked = checked);
        });
    </script>
@stop
