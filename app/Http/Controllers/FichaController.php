<?php

namespace App\Http\Controllers;

use App\Models\Ficha;
use Barryvdh\DomPDF\Facade\Pdf;

class FichaController extends Controller
{
    public function download(Ficha $ficha)
    {
        $pdf = Pdf::loadView('pdf.ficha', ['ficha' => $ficha]);

        return $pdf->download("ficha-{$ficha->folio}.pdf");
    }
}
