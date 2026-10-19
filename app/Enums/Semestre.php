<?php

namespace App\Enums;

enum Semestre: string
{
    case Primero = 'Primero';
    case Segundo = 'Segundo';
    case Tercero = 'Tercero';
    case Cuarto = 'Cuarto';
    case Quinto = 'Quinto';
    case Sexto = 'Sexto';

    public static function normalize(mixed $state): ?string
    {
        if (blank($state)) {
            return null;
        }

        $state = trim((string) $state);

        if (is_numeric($state)) {
            $cases = self::cases();
            $index = (int) $state - 1;

            return isset($cases[$index]) ? $cases[$index]->value : $state;
        }

        return ucfirst(strtolower($state));
    }
}
