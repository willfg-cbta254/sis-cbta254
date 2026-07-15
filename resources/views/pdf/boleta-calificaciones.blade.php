<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Boleta de Calificaciones</title>
    <style>
        @page {
            margin: 12px;
            padding: 0;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            line-height: 1.3;
            color: #333;
            margin: 0;
            padding: 8px;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #1e40af;
            padding-bottom: 12px;
            margin-bottom: 12px;
        }

        .header-table td {
            vertical-align: middle;
        }

        .school-info {
            font-size: 9px;
            line-height: 1.4;
        }

        .school-info strong {
            font-size: 11px;
        }

        .title {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            color: #1e40af;
            margin: 8px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .subtitle {
            text-align: center;
            font-size: 11px;
            color: #555;
            margin-bottom: 10px;
        }

        .info-section {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .info-section td {
            padding: 2px 4px;
            font-size: 10px;
        }

        .info-section .label {
            font-weight: bold;
            color: #555;
            width: 100px;
        }

        .info-section .separator {
            width: 8px;
            text-align: center;
            color: #999;
        }

        .grades-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 9px;
        }

        .grades-table thead th {
            background-color: #1e40af;
            color: #fff;
            padding: 5px 3px;
            text-align: center;
            font-weight: bold;
            font-size: 8px;
            text-transform: uppercase;
        }

        .grades-table tbody td {
            padding: 4px 3px;
            text-align: center;
            border-bottom: 1px solid #ddd;
            font-size: 9px;
        }

        .grades-table tbody tr:nth-child(even) {
            background-color: #f8faff;
        }

        .grades-table tbody tr:hover {
            background-color: #eef2ff;
        }

        .grades-table .materia-nombre {
            text-align: left;
            font-weight: bold;
        }

        .promedio-aprobado {
            color: #059669;
            font-weight: bold;
        }

        .promedio-reprobado {
            color: #dc2626;
            font-weight: bold;
        }

        .footer {
            margin-top: 15px;
            font-size: 8px;
            color: #999;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 6px;
        }

        .legend {
            margin-top: 8px;
            font-size: 8px;
            color: #666;
            border-top: 1px dashed #ccc;
            padding-top: 5px;
        }

        .legend span {
            margin-right: 12px;
        }

        .promedio-general {
            font-size: 12px;
            font-weight: bold;
            color: #1e40af;
            text-align: right;
            margin-top: 8px;
        }
    </style>
</head>

<body>
    <table class="header-table">
        <tr>
            <td style="width: 150px;"><img src="{{ $logoSep }}" alt="LogoSEP" style="max-width: 150px;"></td>
            <td style="width: 150px;"><img src="{{ $logoDgetaycm }}" alt="LogoDGETAyCM" style="max-width: 150px;"></td>
            <td style="text-align: left; font-size: 9px; line-height: 1.4;">
                <strong style="font-size: 11px;">Subsecretaría de Educación Media Superior</strong><br>
                Dirección General de Educación Tecnológica<br>
                Agropecuaria y Ciencias del Mar<br>
                Centro de Bachillerato Tecnológico Agropecuario<br>
                No. 254 "Aquiles Serdán Alatriste"<br>
                CCT. 21DTA0254Y
            </td>
            <td style="width: 70px;"><img src="{{ $logoBandera }}" alt="LogoBandera" style="max-width: 70px;"></td>
        </tr>
    </table>

    <div class="title">Boleta de Calificaciones</div>
    <div class="subtitle">Educación Media Superior</div>

    <table class="info-section">
        <tr>
            <td class="label">Alumno:</td>
            <td class="separator">:</td>
            <td>{{ $alumno->user->name }}</td>
            <td class="label">Matrícula:</td>
            <td class="separator">:</td>
            <td>{{ $alumno->matricula }}</td>
        </tr>
        <tr>
            <td class="label">Carrera:</td>
            <td class="separator">:</td>
            <td>{{ $alumno->carrera?->nombre ?? '---' }}</td>
            <td class="label">Semestre:</td>
            <td class="separator">:</td>
            <td>{{ $alumno->semestre_actual_id?->value ? ucfirst($alumno->semestre_actual_id->value) : '---' }}</td>
        </tr>
        <tr>
            <td class="label">Grupo:</td>
            <td class="separator">:</td>
            <td>{{ $grupo?->nombre ?? '---' }}</td>
            <td class="label">Periodo:</td>
            <td class="separator">:</td>
            <td>{{ $periodoEscolar?->clave ?? '---' }}</td>
        </tr>
        <tr>
            <td class="label">Generación:</td>
            <td class="separator">:</td>
            <td>{{ $alumno->generacion?->clave ?? '---' }}</td>
            <td class="label">Fecha:</td>
            <td class="separator">:</td>
            <td>{{ now()->format('d/m/Y') }}</td>
        </tr>
    </table>

    <table class="grades-table">
        <thead>
            <tr>
                <th style="text-align: left; width: 28%;">Materia</th>
                <th>Parcial 1</th>
                <th>Parcial 2</th>
                <th>Parcial 3</th>
                <th>Promedio</th>
                <th>Extraordinario</th>
                <th>Curso Int.</th>
                <th>Recursamiento</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($materiasConCalificaciones as $materia)
                <tr>
                    <td class="materia-nombre">{{ $materia->materia?->nombre ?? '---' }}</td>
                    <td>{{ $materia->parcial1 !== null ? number_format($materia->parcial1, 2) : '-' }}</td>
                    <td>{{ $materia->parcial2 !== null ? number_format($materia->parcial2, 2) : '-' }}</td>
                    <td>{{ $materia->parcial3 !== null ? number_format($materia->parcial3, 2) : '-' }}</td>
                    <td
                        class="{{ $materia->promedio !== null && $materia->promedio >= 6 ? 'promedio-aprobado' : 'promedio-reprobado' }}">
                        {{ $materia->promedio !== null ? number_format($materia->promedio, 2) : '-' }}
                    </td>
                    <td>{{ $materia->extraordinario !== null ? number_format($materia->extraordinario, 2) : '-' }}</td>
                    <td>{{ $materia->curso_intersemestral !== null ? number_format($materia->curso_intersemestral, 2) : '-' }}
                    </td>
                    <td>{{ $materia->recursamiento ? 'Sí' : 'No' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 15px; color: #999;">
                        No hay materias asignadas para este periodo.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @php
        $promedioGeneral = $materiasConCalificaciones->avg('promedio');
    @endphp
    @if ($promedioGeneral !== null)
        <div class="promedio-general">
            Promedio General: {{ number_format($promedioGeneral, 2) }}
        </div>
    @endif



    <table style="width: 100%; margin-top: 20px;">
        <tr>
            <td style="text-align: center; width: 50%;">
                <div
                    style="width: 200px; border-top: 1px solid #333; margin: 0 auto; padding-top: 4px; font-size: 10px; color: #555;">
                    Firma del Alumno
                </div>
            </td>
            <td style="text-align: center; width: 50%;">
                <div
                    style="width: 200px; border-top: 1px solid #333; margin: 0 auto; padding-top: 4px; font-size: 10px; color: #555;">
                    Firma del Tutor
                </div>
            </td>
        </tr>
    </table>

    <div class="footer">
        CBTA 254 &mdash; Cuacnopalan, Palmar de Bravo, Puebla &mdash; Tel. (249) 154-5238
    </div>

    <footer style="position: fixed; bottom: 20px; width: 95%;">
        <table style="width: 95%;">
            <tr>
                <td rowspan="3"><img src="{{ $logoCta }}" alt="LogoCBTA254" style="max-width: 150px;"></td>
                <td></td>
            </tr>
            <tr>
                <td>
                    <hr
                        style="border: none; height: 4px; background-color: #621132; vertical-align: bottom; margin-bottom: 0;">
                </td>
            </tr>
            <tr>
                <td
                    style="text-align: left; font-size: 9px; line-height: 1.4; color: #621132; vertical-align: top; margin-top: 0;">
                    Km.26.5 Carretera Estatal Tecamachalco, Cañada Morelos, Palmar de Bravo, Puebla.
                    Email: pue.cbta254.direccion@dgetaycm.sems.gob.mx Tel. 2491545238
                </td>
            </tr>
        </table>
    </footer>
</body>

</html>
