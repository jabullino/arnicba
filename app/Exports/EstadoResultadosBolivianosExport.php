<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class EstadoResultadosBolivianosExport implements
    FromView,
    ShouldAutoSize,
    WithColumnWidths
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view(
            'Administrador.partials.FormEstadoResultadosBolivianosExcel',
            $this->data
        );
    }

    public function columnWidths(): array
    {
        return [

            // TABLA 1
            'A' => 18,
            'B' => 40,
            'C' => 18,
            'D' => 15,
            'E' => 15,
            'F' => 20,
            

        ];
    }
}
