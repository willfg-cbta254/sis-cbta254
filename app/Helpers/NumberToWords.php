<?php

namespace App\Helpers;

class NumberToWords
{
    private array $unidades = [
        '', 'UN', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE',
    ];

    private array $decenas = [
        10 => 'DIEZ', 11 => 'ONCE', 12 => 'DOCE', 13 => 'TRECE', 14 => 'CATORCE', 15 => 'QUINCE',
        16 => 'DIECISÉIS', 17 => 'DIECISIETE', 18 => 'DIECIOCHO', 19 => 'DIECINUEVE',
        20 => 'VEINTE', 21 => 'VEINTIUN', 22 => 'VEINTIDÓS', 23 => 'VEINTITRÉS',
        24 => 'VEINTICUATRO', 25 => 'VEINTICINCO', 26 => 'VEINTISÉIS', 27 => 'VEINTISIETE',
        28 => 'VEINTIOCHO', 29 => 'VEINTINUEVE', 30 => 'TREINTA', 31 => 'TREINTA Y UN',
        32 => 'TREINTA Y DOS', 33 => 'TREINTA Y TRES', 34 => 'TREINTA Y CUATRO',
        35 => 'TREINTA Y CINCO', 36 => 'TREINTA Y SEIS', 37 => 'TREINTA Y SIETE',
        38 => 'TREINTA Y OCHO', 39 => 'TREINTA Y NUEVE', 40 => 'CUARENTA',
        41 => 'CUARENTA Y UN', 42 => 'CUARENTA Y DOS', 43 => 'CUARENTA Y TRES',
        44 => 'CUARENTA Y CUATRO', 45 => 'CUARENTA Y CINCO', 46 => 'CUARENTA Y SEIS',
        47 => 'CUARENTA Y SIETE', 48 => 'CUARENTA Y OCHO', 49 => 'CUARENTA Y NUEVE',
        50 => 'CINCUENTA', 51 => 'CINCUENTA Y UN', 52 => 'CINCUENTA Y DOS',
        53 => 'CINCUENTA Y TRES', 54 => 'CINCUENTA Y CUATRO', 55 => 'CINCUENTA Y CINCO',
        56 => 'CINCUENTA Y SEIS', 57 => 'CINCUENTA Y SIETE', 58 => 'CINCUENTA Y OCHO',
        59 => 'CINCUENTA Y NUEVE', 60 => 'SESENTA', 61 => 'SESENTA Y UN',
        62 => 'SESENTA Y DOS', 63 => 'SESENTA Y TRES', 64 => 'SESENTA Y CUATRO',
        65 => 'SESENTA Y CINCO', 66 => 'SESENTA Y SEIS', 67 => 'SESENTA Y SIETE',
        68 => 'SESENTA Y OCHO', 69 => 'SESENTA Y NUEVE', 70 => 'SETENTA',
        71 => 'SETENTA Y UN', 72 => 'SETENTA Y DOS', 73 => 'SETENTA Y TRES',
        74 => 'SETENTA Y CUATRO', 75 => 'SETENTA Y CINCO', 76 => 'SETENTA Y SEIS',
        77 => 'SETENTA Y SIETE', 78 => 'SETENTA Y OCHO', 79 => 'SETENTA Y NUEVE',
        80 => 'OCHENTA', 81 => 'OCHENTA Y UN', 82 => 'OCHENTA Y DOS', 83 => 'OCHENTA Y TRES',
        84 => 'OCHENTA Y CUATRO', 85 => 'OCHENTA Y CINCO', 86 => 'OCHENTA Y SEIS',
        87 => 'OCHENTA Y SIETE', 88 => 'OCHENTA Y OCHO', 89 => 'OCHENTA Y NUEVE',
        90 => 'NOVENTA', 91 => 'NOVENTA Y UN', 92 => 'NOVENTA Y DOS', 93 => 'NOVENTA Y TRES',
        94 => 'NOVENTA Y CUATRO', 95 => 'NOVENTA Y CINCO', 96 => 'NOVENTA Y SEIS',
        97 => 'NOVENTA Y SIETE', 98 => 'NOVENTA Y OCHO', 99 => 'NOVENTA Y NUEVE',
    ];

    private array $centenas = [
        '', 'CIENTO', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS',
        'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS',
    ];

    public function convert(float|int $number): string
    {
        $entero = (int) $number;
        $decimal = (int) round(($number - $entero) * 100);

        if ($entero === 0) {
            $letras = 'CERO';
        } else {
            $letras = $this->convertGroup($entero);
        }

        $letras .= ' PESOS';

        if ($decimal > 0) {
            $letras .= " {$decimal}/100 M.N.";
        } else {
            $letras .= ' 00/100 M.N.';
        }

        return mb_convert_case($letras, MB_CASE_TITLE, 'UTF-8');
    }

    private function convertGroup(int $number): string
    {
        if ($number === 0) {
            return '';
        }

        if ($number < 10) {
            return $this->unidades[$number];
        }

        if ($number < 100) {
            return $this->decenas[$number];
        }

        if ($number < 1000) {
            $centena = (int) ($number / 100);
            $resto = $number % 100;

            if ($centena === 1 && $resto === 0) {
                return 'CIEN';
            }

            $result = $this->centenas[$centena];
            if ($resto > 0) {
                $result .= ' '.$this->convertGroup($resto);
            }

            return $result;
        }

        if ($number < 1_000_000) {
            $miles = (int) ($number / 1000);
            $resto = $number % 1000;

            if ($miles === 1) {
                $result = 'MIL';
            } else {
                $result = $this->convertGroup($miles).' MIL';
            }

            if ($resto > 0) {
                $result .= ' '.$this->convertGroup($resto);
            }

            return $result;
        }

        $millones = (int) ($number / 1_000_000);
        $resto = $number % 1_000_000;

        if ($millones === 1) {
            $result = 'UN MILLÓN';
        } else {
            $result = $this->convertGroup($millones).' MILLONES';
        }

        if ($resto > 0) {
            $result .= ' '.$this->convertGroup($resto);
        }

        return $result;
    }
}
