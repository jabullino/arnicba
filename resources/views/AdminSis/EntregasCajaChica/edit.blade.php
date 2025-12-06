@extends('layouts.app')

@section('page_header')
    <h1>Editar Entrega de Caja Chica</h1>
@stop

@section('page_content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('entregascajachicas.update', $entrega->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Campo: Fecha de entrega --}}
                <div class="form-group mb-3">
                    <label for="fecha_entrega" class="form-label fw-bold">Fecha de Entrega</label>
                    <input type="date" name="fecha_entrega" id="fecha_entrega"
                        value="{{ old('fecha_entrega', \Carbon\Carbon::parse($entrega->fecha_entrega)->format('Y-m-d')) }}"
                        class="form-control @error('fecha_entrega') is-invalid @enderror">
                    @error('fecha_entrega')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Campo: Mes de entrega --}}
                <div class="form-group mb-3">
                    <label for="mes" class="form-label fw-bold">Mes</label>
                    @php
                        $mesActual = \Carbon\Carbon::parse($entrega->fecha_entrega)->format('n');
                    @endphp
                    <select name="mes" id="mes" class="form-control">
                        @foreach ($meses as $numero => $nombre)
                            <option value="{{ $numero }}"
                                {{ $numero == date('n', strtotime($entrega->fecha_entrega)) ? 'selected' : '' }}>
                                {{ $nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('mes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Campo: Monto --}}
                <div class="form-group mb-3">
                    <label for="monto" class="form-label fw-bold">Monto</label>
                    <input type="number" step="0.01" name="monto" id="monto"
                        value="{{ old('monto', $entrega->monto) }}"
                        class="form-control @error('monto') is-invalid @enderror">
                    @error('monto')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Botones --}}
                <div class="button-group">
                    <a href="{{ route('entregascajachicas.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        /* === Diseño base === */
        .card {
            max-width: 600px;
            margin: 30px auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            background-color: #fff;
        }

        .card-body {
            padding: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 1rem;
        }

        label {
            margin-bottom: 5px;
            font-weight: 600;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        .invalid-feedback {
            color: #e3342f;
            font-size: 14px;
            margin-top: 5px;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .btn {
            padding: 10px 15px;
            border-radius: 6px;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            font-size: 15px;
            transition: background-color 0.3s;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #565e64;
        }

        /* === RESPONSIVE === */

        /* Tablets (pantallas medianas) */
        @media (max-width: 768px) {
            .card {
                width: 90%;
                padding: 15px;
            }

            .button-group {
                flex-direction: column;
                align-items: stretch;
            }

            .btn {
                width: 100%;
            }
        }

        /* Móviles (pantallas pequeñas) */
        @media (max-width: 480px) {
            .card {
                width: 95%;
                margin: 15px auto;
            }

            input[type="text"],
            input[type="date"],
            input[type="number"],
            select {
                font-size: 14px;
                padding: 8px;
            }

            .btn {
                font-size: 14px;
                padding: 8px;
            }

            label {
                font-size: 14px;
            }

            h1 {
                font-size: 20px;
                text-align: center;
            }
        }
    </style>
@stop
