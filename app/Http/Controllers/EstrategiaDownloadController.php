<?php

namespace App\Http\Controllers;

use App\Models\EstrategiaDidactica;
use Illuminate\Support\Facades\Storage;

class EstrategiaDownloadController extends Controller
{
    public function download(EstrategiaDidactica $estrategia)
    {
        $archivo = $estrategia->archivo;

        $disk = match (true) {
            Storage::disk('public')->exists($archivo) => 'public',
            Storage::disk('local')->exists($archivo) => 'local',
            default => abort(404, 'Archivo no encontrado'),
        };

        return Storage::disk($disk)->download($archivo, $estrategia->titulo.'.'.pathinfo($archivo, PATHINFO_EXTENSION));
    }

    public function stream(EstrategiaDidactica $estrategia)
    {
        $archivo = $estrategia->archivo;

        $disk = match (true) {
            Storage::disk('public')->exists($archivo) => 'public',
            Storage::disk('local')->exists($archivo) => 'local',
            default => abort(404, 'Archivo no encontrado'),
        };

        return Storage::disk($disk)->response($archivo);
    }
}
