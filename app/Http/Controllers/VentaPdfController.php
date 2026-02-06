<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use Barryvdh\DomPDF\Facade\Pdf;

class VentaPdfController extends Controller
{
    public function show(Carro $carro)
    {
        abort_if($carro->estado !== 'vendido', 403);

        $pdf = Pdf::loadView('pdf.venta', [
            'carro' => $carro,
            'ganancia' => $carro->precio_venta - $carro->precio_compra,
        ]);

        return $pdf->download(
            'venta_' . $carro->marca . '_' . $carro->modelo . '.pdf'
        );
    }
}
