<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ficha de Nuevo Ingreso - {{ $ficha->folio }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #1a202c;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #166534;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            color: #166534;
            margin: 0 0 5px;
        }
        .header p {
            font-size: 12px;
            color: #4a5568;
            margin: 0;
        }
        .folio-box {
            text-align: center;
            margin: 15px 0;
            padding: 10px;
            border: 2px dashed #166534;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            color: #166534;
        }
        h2 {
            font-size: 14px;
            color: #166534;
            border-bottom: 1px solid #cbd5e0;
            padding-bottom: 4px;
            margin: 15px 0 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table td {
            padding: 4px 6px;
            vertical-align: top;
        }
        .label {
            font-weight: bold;
            color: #4a5568;
            width: 35%;
        }
        .value {
            color: #1a202c;
        }
        .footer {
            margin-top: 25px;
            padding-top: 10px;
            border-top: 1px solid #cbd5e0;
            font-size: 9px;
            color: #718096;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>CBTA No. 254</h1>
        <p>Ficha de Nuevo Ingreso</p>
    </div>

    <div class="folio-box">
        Folio: {{ $ficha->folio }}
    </div>

    <h2>Datos del Estudiante</h2>
    <table>
        <tr><td class="label">Nombre Completo:</td><td class="value">{{ $ficha->nombre_completo }}</td></tr>
        <tr><td class="label">Fecha de Nacimiento:</td><td class="value">{{ $ficha->fecha_nacimiento->format('d/m/Y') }}</td></tr>
        <tr><td class="label">Edad:</td><td class="value">{{ $ficha->edad }} años</td></tr>
        <tr><td class="label">Género:</td><td class="value">{{ $ficha->genero === 'femenino' ? 'Femenino' : 'Masculino' }}</td></tr>
        <tr><td class="label">Dirección:</td><td class="value">{{ $ficha->direccion }}</td></tr>
        <tr><td class="label">Núm. Celular:</td><td class="value">{{ $ficha->num_celular }}</td></tr>
        <tr><td class="label">Correo Electrónico:</td><td class="value">{{ $ficha->correo_electronico }}</td></tr>
        <tr><td class="label">Localidad:</td><td class="value">{{ $ficha->localidad }}</td></tr>
        <tr><td class="label">Municipio:</td><td class="value">{{ $ficha->municipio }}</td></tr>
    </table>

    <h2>Datos de Secundaria</h2>
    <table>
        <tr><td class="label">Secundaria de Procedencia:</td><td class="value">{{ $ficha->secundaria_procedencia }}</td></tr>
        <tr><td class="label">Tipo de Secundaria:</td><td class="value">{{ $ficha->tipo_secundaria === 'tecnica' ? 'Técnica' : 'Telesecundaria' }}</td></tr>
        <tr><td class="label">Localidad de la Escuela:</td><td class="value">{{ $ficha->localidad_secundaria }}</td></tr>
        <tr><td class="label">Turno:</td><td class="value">{{ $ficha->turno === 'matutino' ? 'Matutino' : 'Vespertino' }}</td></tr>
        <tr><td class="label">Promedio:</td><td class="value">{{ number_format($ficha->promedio, 2) }}</td></tr>
    </table>

    <h2>Datos del Tutor Legal</h2>
    <table>
        <tr><td class="label">Nombre Completo:</td><td class="value">{{ $ficha->tutor_nombre_completo }}</td></tr>
        <tr><td class="label">Núm. Celular:</td><td class="value">{{ $ficha->tutor_num_celular }}</td></tr>
        <tr><td class="label">Correo Electrónico:</td><td class="value">{{ $ficha->tutor_correo_electronico }}</td></tr>
    </table>

    <div class="footer">
        <p>Ciclo Escolar {{ $ficha->ciclo_escolar }} - CBTA No. 254</p>
        <p>Documento generado el {{ now()->format('d/m/Y \a \l\a\s H:i') }}</p>
    </div>
</body>
</html>
