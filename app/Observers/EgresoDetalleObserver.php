<?php

namespace App\Observers;

use App\Models\EgresoDetalle;

class EgresoDetalleObserver
{
    /**
     * Handle the EgresoDetalle "created" event.
     */
    public function created(EgresoDetalle $egresoDetalle)
    {
        \App\Models\Producto::where('id', $egresoDetalle->producto_id)
            ->increment('lineas');
    }

    /**
     * Handle the EgresoDetalle "updated" event.
     */
    public function updated(EgresoDetalle $egresoDetalle): void
    {
        //
    }

    /**
     * Handle the EgresoDetalle "deleted" event.
     */
    public function deleted(EgresoDetalle $egresoDetalle): void
    {
        //
    }

    /**
     * Handle the EgresoDetalle "restored" event.
     */
    public function restored(EgresoDetalle $egresoDetalle): void
    {
        //
    }

    /**
     * Handle the EgresoDetalle "force deleted" event.
     */
    public function forceDeleted(EgresoDetalle $egresoDetalle): void
    {
        //
    }
}
