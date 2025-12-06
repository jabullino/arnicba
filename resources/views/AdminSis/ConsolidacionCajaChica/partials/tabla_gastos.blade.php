@forelse ($gastos as $gasto)
<tr>
    <td data-label="Fecha">{{ \Carbon\Carbon::parse($gasto->fecha_doc)->format('d-m-Y') }}</td>
    <td data-label="Documento">
        {{ $gasto->factura ? 'Factura: '.$gasto->documentos : 'Recibo: '.$gasto->documentos }}
    </td>
    <td data-label="Cuenta">{{ $gasto->cuenta->nombre ?? '—' }}</td>
    <td data-label="Subcuenta">{{ $gasto->subcuenta->nombre ?? '—' }}</td>
    <td data-label="Importe">{{ number_format($gasto->importe, 2, '.', '') }}</td>
    <td data-label="Escoger" class='w-8'>
        <input type="checkbox" name="escogidos[]" value="{{ $gasto->id }}">
    </td>
</tr>
@empty
<tr>
    <td colspan="6" class="text-center text-gray-500">
        No se registraron pagos para este período.
    </td>
</tr>
@endforelse

{{-- === ESTILOS RESPONSIVE SIN BOOTSTRAP === --}}
<style>
    /* Tabla base */
    table {
        width: 100%;
        border-collapse: collapse;
    }

    table th,
    table td {
        padding: 8px 10px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    /* Colores y estilo básico */
    table th {
        background-color: #f5f5f5;
        font-weight: bold;
    }

    /* --- RESPONSIVE --- */
    @media (max-width: 768px) {
        table thead {
            display: none;
        }

        table, table tbody, table tr, table td {
            display: block;
            width: 100%;
        }

        table tr {
            margin-bottom: 1rem;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        table td {
            text-align: right;
            padding: 10px 15px;
            position: relative;
            border: none;
            border-bottom: 1px solid #eee;
        }

        table td:last-child {
            border-bottom: none;
        }

        table td::before {
            content: attr(data-label);
            position: absolute;
            left: 10px;
            top: 10px;
            font-weight: bold;
            text-transform: capitalize;
            color: #333;
            text-align: left;
        }

        /* Ajuste para checkbox */
        table td input[type="checkbox"] {
            transform: scale(1.2);
            margin-top: 5px;
        }

        /* Texto centrado cuando no hay registros */
        .text-center {
            text-align: center !important;
        }
    }

    /* --- Tablets (769px a 1024px) --- */
    @media (min-width: 769px) and (max-width: 1024px) {
        table th,
        table td {
            padding: 6px 8px;
            font-size: 0.9rem;
        }
    }

    /* --- Escritorio grande --- */
    @media (min-width: 1025px) {
        table th,
        table td {
            font-size: 0.95rem;
        }
    }
</style>
