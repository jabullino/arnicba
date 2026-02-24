<?php

namespace App\Observers;

use App\Models\Lote;

class LoteObserver
{
    /**
     * Handle the Lote "created" event.
     */
    public function created(Lote $lote)
    {
        \App\Models\Producto::where('id', $lote->producto_id)
            ->increment('lineas');
    }

    /**
     * Handle the Lote "updated" event.
     */
    public function updated(Lote $lote): void
    {
        //
    }

    /**
     * Handle the Lote "deleted" event.
     */
    public function deleted(Lote $lote): void
    {
        //
    }

    /**
     * Handle the Lote "restored" event.
     */
    public function restored(Lote $lote): void
    {
        //
    }

    /**
     * Handle the Lote "force deleted" event.
     */
    public function forceDeleted(Lote $lote): void
    {
        //
    }
}
