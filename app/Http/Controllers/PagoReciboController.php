<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use Barryvdh\DomPDF\Facade\Pdf;

class PagoReciboController extends Controller
{
    private function getBase64Image(string $filename): string
    {
        $path = public_path("images/{$filename}");

        if (! file_exists($path)) {
            return '';
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $mime = match ($extension) {
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'webp' => 'image/webp',
            default => 'image/png',
        };

        $data = file_get_contents($path);

        return "data:{$mime};base64,".base64_encode($data);
    }

    public function download(Pago $pago)
    {
        $user = auth()->user();

        if (! $user->hasRole('Administrador')) {
            $alumno = $user->alumno;

            if (! $alumno || $pago->alumno_id !== $alumno->id) {
                abort(403);
            }
        }

        $pago->load(['alumno.user', 'alumno.carrera', 'alumno.generacion', 'conceptoPago', 'usuario']);

        $pdf = Pdf::loadView('pdf.recibo-pago', [
            'pago' => $pago,
            'logoSep' => $this->getBase64Image('image3.png'),
            'logoDgetaycm' => $this->getBase64Image('image2.png'),
            'logoBandera' => $this->getBase64Image('image1.png'),
            'logoCta' => $this->getBase64Image('image4.png'),
        ]);

        $pdf->setPaper('letter', 'portrait');

        $nombreArchivo = 'recibo-'.($pago->referencia ?? $pago->id).'.pdf';

        return $pdf->download($nombreArchivo);
    }

    public function stream(Pago $pago)
    {
        $pago->load(['alumno.user', 'alumno.carrera', 'alumno.generacion', 'conceptoPago', 'usuario']);

        $pdf = Pdf::loadView('pdf.recibo-pago', [
            'pago' => $pago,
            'logoSep' => $this->getBase64Image('image3.png'),
            'logoDgetaycm' => $this->getBase64Image('image2.png'),
            'logoBandera' => $this->getBase64Image('image1.png'),
        ]);

        $pdf->setPaper('letter', 'portrait');

        $nombreArchivo = 'recibo-'.($pago->referencia ?? $pago->id).'.pdf';

        return $pdf->stream($nombreArchivo);
    }
}
