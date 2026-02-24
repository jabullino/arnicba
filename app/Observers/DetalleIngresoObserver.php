<?php

namespace App\Observers;

use App\Models\DetalleIngreso;
use App\Models\Producto;

class DetalleIngresoObserver
{
    /**
     * Handle the DetalleIngreso "created" event.
     */
    public function created(DetalleIngreso $detalleIngreso): void
    {
       $detalleIngreso->producto->increment('lineas');
    }

    /**
     * Handle the DetalleIngreso "updated" event.
     */
    public function updated(DetalleIngreso $detalleIngreso): void
    {
        //
    }

    /**
     * Handle the DetalleIngreso "deleted" event.
     */
    public function deleted(DetalleIngreso $detalleIngreso): void
    {
        //
    }

    /**
     * Handle the DetalleIngreso "restored" event.
     */
    public function restored(DetalleIngreso $detalleIngreso): void
    {
        //
    }

    /**
     * Handle the DetalleIngreso "force deleted" event.
     */
    public function forceDeleted(DetalleIngreso $detalleIngreso): void
    {
        //
    }
}
