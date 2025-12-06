<form method="GET" action="{{ route('entregascajachicas.index') }}" class="mb-4 filtros-entregas">
    <div class="row">
        <div class="col">
            <label for="anio">Gestión (Año)</label>
            <select name="anio" id="anio" class="form-control">
                <option value="">-- Todas las gestiones --</option>
                @foreach($gestiones as $gestion)
                    <option value="{{ $gestion->nombre }}" {{ $anio == $gestion->nombre ? 'selected' : '' }}>
                        {{ $gestion->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col">
            <label for="mes">Mes</label>
            @php
                $meses = [
                    1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',
                    7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre'
                ];
            @endphp
            <select name="mes" id="mes" class="form-control">
                <option value="">-- Todos los meses --</option>
                @foreach($meses as $num => $nombre)
                    <option value="{{ $num }}" {{ $mes == $num ? 'selected' : '' }}>
                        {{ $nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
        </div>
    </div>

    <style>
        /* ===== Diseño base ===== */
        .row {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .col {
            flex: 1;
            min-width: 200px;
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: 600;
            margin-bottom: 5px;
        }

        select, button {
            padding: 8px 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 15px;
            width: 100%;
            box-sizing: border-box;
        }

        button.btn-primary {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button.btn-primary:hover {
            background-color: #0056b3;
        }

        /* ===== Media Queries ===== */
        @media (max-width: 992px) {
            .row {
                gap: 12px;
            }

            select, button {
                font-size: 14px;
                padding: 7px;
            }
        }

        @media (max-width: 768px) {
            .row {
                flex-direction: column;
            }

            .col {
                width: 100%;
            }

            button.btn-primary {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            select, button {
                font-size: 13px;
                padding: 6px;
            }

            label {
                font-size: 14px;
            }
        }
    </style>
</form>
