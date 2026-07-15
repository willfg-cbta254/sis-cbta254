<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\CargaAcademica;
use Barryvdh\DomPDF\Facade\Pdf;
use stdClass;

class BoletaController extends Controller
{
    public function download(Alumno $alumno)
    {
        $user = auth()->user();

        if (! $user->hasRole('Administrador')) {
            $userAlumno = $user->alumno;

            if (! $userAlumno || $alumno->id !== $userAlumno->id) {
                abort(403);
            }
        }

        $alumno->load([
            'user',
            'carrera',
            'generacion',
            'calificaciones.materia',
            'calificaciones.profesor.user',
            'calificaciones.grupo',
            'calificaciones.periodoEscolar',
            'inscripciones.grupo',
            'inscripciones.periodoEscolar',
        ]);

        $path = public_path('images');

        $logoSep = $this->getBase64Image($path, 'image3.png');
        $logoDgetaycm = $this->getBase64Image($path, 'image2.png');
        $logoBandera = $this->getBase64Image($path, 'image1.png');
        $logoCta = $this->getBase64Image($path, 'image4.png');

        $inscripcion = $alumno->inscripciones
            ->where('status', 'Inscrito')
            ->sortByDesc('created_at')
            ->first();

        $grupo = $inscripcion?->grupo;

        $cargas = collect();
        if ($inscripcion?->periodo_escolar_id) {
            $cargas = CargaAcademica::with(['materia', 'profesor.user', 'grupo', 'periodoEscolar'])
                ->where('grupo_id', $inscripcion->grupo_id)
                ->where('periodo_escolar_id', $inscripcion->periodo_escolar_id)
                ->get();
        }

        $periodoEscolar = $cargas->first()?->periodoEscolar ?? $inscripcion?->periodoEscolar;

        $calificaciones = $alumno->calificaciones->keyBy('materia_id');

        $materiasConCalificaciones = $cargas->map(function ($carga) use ($calificaciones) {
            $existente = $calificaciones->get($carga->materia_id);

            $item = new stdClass;
            $item->materia = $carga->materia;
            $item->profesor = $carga->profesor;
            $item->grupo = $carga->grupo;
            $item->periodoEscolar = $carga->periodoEscolar;
            $item->parcial1 = $existente?->parcial1;
            $item->parcial2 = $existente?->parcial2;
            $item->parcial3 = $existente?->parcial3;
            $item->promedio = $existente?->promedio;
            $item->extraordinario = $existente?->extraordinario;
            $item->curso_intersemestral = $existente?->curso_intersemestral;
            $item->recursamiento = $existente?->recursamiento ?? false;

            return $item;
        });

        $pdf = Pdf::loadView('pdf.boleta-calificaciones', compact(
            'alumno', 'logoSep', 'logoDgetaycm', 'logoBandera', 'logoCta',
            'periodoEscolar', 'grupo', 'materiasConCalificaciones'
        ));

        $pdf->setPaper('letter', 'portrait');

        $nombreArchivo = 'boleta-'.$alumno->matricula.'.pdf';

        return $pdf->download($nombreArchivo);
    }

    private function getBase64Image(string $path, string $filename): string
    {
        $filePath = $path.DIRECTORY_SEPARATOR.$filename;

        if (! file_exists($filePath)) {
            return '';
        }

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $mime = match ($extension) {
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'webp' => 'image/webp',
            default => 'image/png',
        };

        $data = file_get_contents($filePath);

        return "data:{$mime};base64,".base64_encode($data);
    }
}
