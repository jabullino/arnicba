@forelse ($gastos as $gasto)
<tr class="gasto-row">
    <td data-label="Fecha">{{ \Carbon\Carbon::parse($gasto->fecha_doc)->format('d-m-Y') }}</td>
    <td data-label="Documento">
        {{ $gasto->factura ? 'Factura: ' . $gasto->factura : 'Recibo: ' . $gasto->recibo }}
    </td>
    <td data-label="Cuenta">{{ $gasto->cuenta->nombre ?? '—' }}</td>
    <td data-label="Subcuenta">{{ $gasto->subcuenta->nombre ?? '—' }}</td>
    <td data-label="Importe">{{ number_format($gasto->importe, 2, '.', '') }}</td>
    <td data-label="Acciones">
        <a href="{{ route('gastoscajachica.show', $gasto->id) }}" class="btn btn-info btn-sm">Ver</a>
        <a href="{{ route('gastoscajachica.edit', $gasto->id) }}" class="btn btn-warning btn-sm">Editar</a>
        <form action="{{ route('gastoscajachica.destroy', $gasto->id) }}" method="POST"
              class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar este registro?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
        </form>
    </td>
</tr>
@empty
<tr>
    <td colspan="6" class="text-center text-gray-500">
        No se registraron pagos para este período.
    </td>
</tr>
@endforelse

@section('css')
<style>
/* Responsividad para tabla sin bootstrap */
@media (max-width: 768px) {
    table {
        display: block;
        width: 100%;
        overflow-x: auto;
        border-collapse: collapse;
    }

    thead {
        display: none;
    }

    tbody tr {
        display: block;
        margin-bottom: 1rem;
        border: 1px solid #ccc;
        padding: 0.5rem;
        border-radius: 0.25rem;
    }

    tbody td {
        display: flex;
        justify-content: space-between;
        padding: 0.25rem 0.5rem;
        border: none;
        border-bottom: 1px solid #eee;
    }

    tbody td:last-child {
        border-bottom: 0;
        flex-direction: column;
        gap: 0.25rem;
    }

    tbody td::before {
        content: attr(data-label);
        font-weight: bold;
        flex: 1;
    }

    .btn {
        width: 100%;
        text-align: center;
    }

    form.d-inline {
        width: 100%;
    }
}
</style>
@stop
