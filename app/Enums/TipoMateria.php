<?php

namespace App\Enums;

enum TipoMateria: string
{
    case CurriculumFundamental = 'Curriculum Fundamental';
    case CurriculumFundamentalExtendida = 'Curriculum Fundamental Extendida';
    case CurriculumLaboral = 'Curriculum Laboral';
    case CurriculumAmpliado = 'Curriculum Ampliado';
}
