<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Recibo de Pago</title>
    <style>
        @page {
            margin: 15px;
            padding: 0;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 10px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #1e40af;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 18px;
            color: #1e40af;
            margin: 0 0 3px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header h2 {
            font-size: 14px;
            color: #2563eb;
            margin: 0 0 3px 0;
        }

        .header p {
            font-size: 11px;
            color: #666;
            margin: 0;
        }

        .folio {
            text-align: right;
            font-size: 18px;
            margin-bottom: 10px;
            color: #555;
        }

        .folio strong {
            color: #1e40af;
        }

        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            color: #1e40af;
            margin: 10px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .info-table td {
            padding: 3px 5px;
            font-size: 12px;
        }

        .info-table .label {
            font-weight: bold;
            width: 130px;
            color: #555;
        }

        .info-table .value {
            color: #222;
        }

        .info-table .separator {
            width: 10px;
            text-align: center;
            color: #999;
        }

        .detalle-box {
            border: 1.5px solid #1e40af;
            border-radius: 5px;
            padding: 10px 12px;
            margin: 12px 0;
            background: #f8faff;
        }

        .detalle-box table {
            width: 100%;
            border-collapse: collapse;
        }

        .detalle-box td {
            padding: 4px 5px;
        }

        .detalle-box .label {
            font-weight: bold;
            color: #555;
            width: 130px;
        }

        .detalle-box .monto-final {
            font-size: 16px;
            font-weight: bold;
            color: #1e40af;
        }

        .letras-box {
            border: 1px solid #ccc;
            padding: 8px 12px;
            margin: 10px 0;
            font-size: 11px;
            color: #555;
            text-align: center;
            font-style: italic;
            border-radius: 3px;
        }

        .firma-section {
            margin-top: 25px;
            text-align: center;
        }

        .firma-section .linea {
            width: 250px;
            border-top: 1px solid #333;
            margin: 30px auto 5px auto;
        }

        .firma-section .texto {
            font-size: 11px;
            color: #555;
        }

        .footer {
            margin-top: 20px;
            font-size: 9px;
            color: #999;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }

        .badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-pagado {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-pendiente {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-cancelado {
            background: #fee2e2;
            color: #991b1b;
        }

        .sep {
            font-size: 10px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <table style="width: 100%; border-bottom: 2px solid #1e40af; padding-bottom: 15px; margin-bottom: 15px;">
        <tr>
            <td style="width: 170px;"><img src="{{ $logoSep }}" alt="LogoSEP" style="max-width: 170px;"></td>
            <td style="width: 170px;"><img src="{{ $logoDgetaycm }}" alt="LogoDGETAyCM" style="max-width: 170px;"></td>
            <td style="text-align: left; font-size: 10px; line-height: 1.5;">
                <strong style="font-size: 12px;">Subsecretaría de Educación Media Superior</strong><br>
                Dirección General de Educación Tecnológica<br>
                Agropecuaria y Ciencias del Mar<br>Centro de Bachillerato Tecnológico Agropecuario<br>
                No. 254 "Aquiles Serdán Alatriste"<br>
                CCT. 21DTA0254Y
            </td>
            <td style="width: 80px;"><img src="{{ $logoBandera }}" alt="LogoBandera" style="max-width: 80px;"></td>
        </tr>
    </table>

    <div class="folio">
        Folio: <strong>{{ $pago->referencia ?? $pago->id }}</strong>
    </div>

    <div class="title">Recibo de Pago</div>

    <table class="info-table">
        <tr>
            <td class="label">Fecha:</td>
            <td class="separator">:</td>
            <td class="value">{{ $pago->fecha_pago?->format('d/m/Y') ?? $pago->created_at->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td class="label">Alumno:</td>
            <td class="separator">:</td>
            <td class="value">{{ $pago->alumno?->user?->name ?? '---' }}</td>
        </tr>
        <tr>
            <td class="label">Matrícula:</td>
            <td class="separator">:</td>
            <td class="value">{{ $pago->alumno?->matricula ?? '---' }}</td>
        </tr>
        <tr>
            <td class="label">Carrera:</td>
            <td class="separator">:</td>
            <td class="value">{{ $pago->alumno?->carrera?->nombre ?? '---' }}</td>
        </tr>
        <tr>
            <td class="label">Generación:</td>
            <td class="separator">:</td>
            <td class="value">{{ $pago->alumno?->generacion?->clave ?? '---' }}</td>
        </tr>
    </table>

    <div class="detalle-box">
        <table>
            <tr>
                <td class="label">Concepto:</td>
                <td>{{ $pago->conceptoPago?->nombre ?? '---' }}</td>
            </tr>
            <tr>
                <td class="label">Método de pago:</td>
                <td>{{ match ($pago->metodo_pago) {'efectivo' => 'Efectivo','transferencia' => 'Transferencia','tarjeta' => 'Tarjeta','deposito' => 'Depósito',default => $pago->metodo_pago} }}
                </td>
            </tr>
            <tr>
                <td class="label">Estatus:</td>
                <td>
                    <span class="badge badge-{{ $pago->estatus }}">
                        {{ match ($pago->estatus) {'pagado' => 'Pagado','pendiente' => 'Pendiente','cancelado' => 'Cancelado',default => $pago->estatus} }}
                    </span>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding-top: 8px;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="font-weight: bold; color: #555; width: 130px;">Monto:</td>
                            <td class="monto-final">$ {{ number_format($pago->monto, 2) }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    @php
        $numberToWords = new \App\Helpers\NumberToWords();
        $montoLetras = $numberToWords->convert($pago->monto);
    @endphp
    <div class="letras-box">
        Son: <strong>{{ $montoLetras }}</strong>
    </div>

    @if ($pago->observaciones)
        <div style="margin-top: 8px; font-size: 11px; color: #666; border-top: 1px dashed #ccc; padding-top: 6px;">
            <strong>Observaciones:</strong><br>
            {{ $pago->observaciones }}
        </div>
    @endif

    <div class="firma-section">
        <div class="linea"></div>
        <div class="texto">Recibí conforme</div>
        <div style="margin-top: 8px; font-size: 11px; color: #888;">
            {{ $pago->alumno?->user?->name ?? '---' }}
        </div>
    </div>

    <div style="margin-top: 10px; font-size: 10px; color: #888; text-align: center;">
        Atendió: {{ $pago->usuario?->name ?? 'Sistema' }}
    </div>

    <div class="footer">
        CBTA 254 &mdash; Cuacnopalan, Palmar de Bravo, Puebla &mdash; Tel. (249) 154-5238
    </div>

    <footer style="position:fixed; bottom: 30px; width:95%;">
        <table style="width:95%">
            <tr>
                <td rowspan="3"><img src="{{ $logoCta }}" alt="LogoCBTA254" style="max-width: 170px;"></td>
                <td></td>
            </tr>
            <tr>
                <td>
                    <hr
                        style="border: none; height: 5px; background-color: #621132; vertical-align: bottom; margin-bottom: 0px;">
                </td>
            </tr>
            <tr>
                <td
                    style="text-align: left; font-size: 10px; line-height: 1.5; color: #621132; vertical-align: top; margin-top: 0px;">
                    Km.26.5 Carretera Estatal Tecamachalco, Cañada Morelos, Palmar de Bravo, Puebla. Email:
                    pue.cbta254.direccion@dgetaycm.sems.gob.mx
                    Tel. 2491545238
                </td>
            </tr>
        </table>
    </footer>
</body>

</html>
