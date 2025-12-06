@extends('layouts.app')
@section('content')
<div class="hoja">
    <form action="" method="POST" target="_blank">
        @csrf

        @foreach ($usuarios as $userId => $primero)
            @php
                $usuarioBonos = $bonos[$userId] ?? collect();
                $usuarioDescuentos = $descuentos[$userId] ?? collect();

                $haberBasico = $primero->haber_basico ?? 0;
                $sumaBonos = $usuarioBonos->sum('monto');
                $sumaDescuentos = $usuarioDescuentos->sum('monto');
                $liquido = $haberBasico + $sumaBonos - $sumaDescuentos;
            @endphp

            <div class="page">
                @for ($copy = 1; $copy <= 2; $copy++)
                    <div class="boleta @if($copy==1) original @else copia @endif">
                        <div class="boleta-content">
                            <div class="text-right font-bold mb-1">
                                {{ $copy == 1 ? 'ORIGINAL' : 'COPIA' }}
                            </div>

                           <div class="text-left text-bold mb-1">
                                FUNDACION ARCA DE RESCATE DE LOS NIÑOS<br>
                                PERSONERÍA JURÍDICA 215532<br>
                                NIT 1029939029
                            </div>


                            <div class="text-center text-bold text-lg mb-1">
                                BOLETA DE PAGO DE SUELDOS
                            </div>

                            <div class="text-bold small-meta">
                                <div>Gestión: <span>{{ $gestion }}</span></div>
                                <div>Mes: <span>{{ $mes }}</span></div>
                            </div>

                            <div class="text-bold mt-1 empleado-info">
                                <div>Empleado: <span>{{ $primero->nombre }} {{ $primero->apellido }}</span></div>
                                <div>Cargo: <span>{{ $primero->cargo }}</span></div>
                            </div>

                            <div class="flex gap-2 mb-2 mt-2 detalles">
                                <div class="flex-1 box">
                                    <h5>Haber Básico</h5>
                                    <p class="text-right font-bold">{{ number_format($haberBasico,2) }}</p>
                                </div>

                                <div class="flex-1 box list-box">
                                    <h5>Bonos</h5>
                                    <div class="items">
                                        @forelse($usuarioBonos as $bono)
                                            <div class="flex item-line">
                                                <span class="truncate">{{ $bono->nombre }}</span>
                                                <span class="ml-auto font-bold">{{ number_format($bono->monto,2) }}</span>
                                            </div>
                                        @empty
                                            <p class="empty-line">Sin bonos</p>
                                        @endforelse
                                    </div>
                                    <hr class="my-1">
                                    <p class="text-right font-bold">Total: {{ number_format($sumaBonos,2) }}</p>
                                </div>

                                <div class="flex-1 box list-box">
                                    <h5>Descuentos</h5>
                                    <div class="items">
                                        @forelse($usuarioDescuentos as $descuento)
                                            <div class="flex item-line">
                                                <span class="truncate">{{ $descuento->nombre }}</span>
                                                <span class="ml-auto font-bold text-red-600">-{{ number_format($descuento->monto,2) }}</span>
                                            </div>
                                        @empty
                                            <p class="empty-line">Sin descuentos</p>
                                        @endforelse
                                    </div>
                                    <hr class="my-1">
                                    <p class="text-right font-bold">Total: {{ number_format($sumaDescuentos,2) }}</p>
                                </div>
                            </div>

                            <div class="text-right font-bold bg-gray-100 p-1 border mb-12 total">
                                Total Líquido Pagable: {{ number_format($liquido,2) }}
                            </div>

                            <!-- Firmas con espacio vertical suficiente y todo contenido visible -->
                            <div class="firmas">
                                <div class="firma">
                                    Entregué Conforme<br>
                                    {{ $administrador->nombre }} {{ $administrador->apellido }}<br>
                                    <span class="small-line">{{ $administrador->ci }} {{ $administrador->extension }}</span><br>
                                    <span class="small-line">{{ $administrador->cargo }}</span>
                                </div>
                                <div class="firma">
                                    Recibí Conforme<br>
                                    {{ $primero->nombre }} {{ $primero->apellido }}<br>
                                    <span class="small-line">{{ $primero->ci }} {{ $primero->extension }}</span><br>
                                    <span class="small-line my-0">{{ $primero->cargo }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($copy == 1)
                        <div class="divisor">
                            ————————————————————————————————————————————————————————————————
                        </div>
                    @endif
                @endfor
            </div>
        @endforeach
    </form>
</div>
@endsection

@section('css')
<style>
@page { size: letter; margin: 0; }
body, html { margin:0; padding:0; background:#eee; height:100%; -webkit-print-color-adjust: exact; }

.hoja { width: 21.59cm; margin:0 auto; box-sizing:border-box; }

.page {
    width: 21.59cm;
    height: 27.94cm;
    box-sizing: border-box;
    padding: 0.45cm 0.4cm;
    background: #fff;
    page-break-after: always;
    overflow: visible;
}

.boleta {
    width: 100%;
    box-sizing: border-box;
    border:1px solid #aaa;
    padding:0.25cm;
    display:flex;
    flex-direction:column;
    flex: 1 1 50%;
    min-height: 13.5cm;
}

.boleta-content {
    display:flex;
    flex-direction:column;
    flex: 1 1 auto;
    overflow: visible;
}

.total { margin-top:0.1cm; margin-bottom:0; padding:0.05cm; }

.firmas {
    display:flex;
    justify-content:space-between;
    margin-top:auto;
    gap:0.4cm;
    padding-top:0.5cm;
}

.firma {
    flex:0 0 48%;
    text-align:center;
    border-bottom:1px solid #000;
    padding-bottom:0.25cm;
    font-size:0.85rem;
    line-height:1.05;
}

.small-line { font-size:0.85rem; display:block; }
.firma span.small-line {
    display:inline;      /* cambia de block a inline para eliminar salto de línea extra */
    line-height:1;       /* altura de línea mínima */
    margin:0;
    padding:0;
}

.flex { display:flex; gap:0.35rem; }
.flex-1 { flex:1; min-width:80px; }
.box { border:1px solid #ddd; padding:0.10cm; box-sizing:border-box; background:transparent; }
.list-box .items { max-height:4.2cm; overflow:hidden; }
.item-line { display:flex; gap:0.3rem; align-items:center; font-size:0.9rem; }
.empty-line { font-size:0.9rem; color:#666; margin:0; }

.truncate { overflow:hidden; white-space:nowrap; text-overflow:ellipsis; }
.gap-2 { gap:0.3rem; }
.mb-1 { margin-bottom:0.1rem; }
.mt-1 { margin-top:0.1rem; }
.mb-2 { margin-bottom:0.2rem; }

.divisor {
    height:0.3cm;
    border-top:1px dashed #999;
    margin:0.15cm 0;
}

/* --- Impresión --- */
@media print {
    body { background:none !important; margin:0; padding:0; }
    .page { padding:0; margin:0; height: 27.94cm; }

    .boleta {
        flex: 1 1 50%;
        min-height: auto;
        height: auto;
        padding:0.25cm;
    }

    .boleta-content { overflow: visible; }

    /* Ajuste de firmas para impresión */
    .firmas {
        flex-shrink:0;
        padding-top:1cm; /* más espacio entre total líquido y firmas */
        justify-content:space-between;
        gap:0.4cm;
    }

    .firma {
        flex:0 0 48%;
        text-align:center;
        border-bottom:1px solid #000;
        padding-bottom:0.25cm;
        font-size:0.85rem;
        line-height:1.05;
    }

    .small-line {
        font-size:0.85rem;
        display:block;
        margin:0;       /* elimina márgenes */
        line-height:1;  /* ajusta la altura de línea para compactar */
    }

    /* opcional: si quieres que el último span no tenga padding extra */
    .firma span.small-line {
    display:inline;      /* cambia de block a inline para eliminar salto de línea extra */
    line-height:1;       /* altura de línea mínima */
    margin:0;
    padding:0;
}
    .divisor {
        height:0.3cm;
        border-top:1px dashed #999;
        margin:0.15cm 0;
    }
}

</style>
@endsection
