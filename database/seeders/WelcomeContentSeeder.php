<?php

namespace Database\Seeders;

use App\Models\WelcomeContent;
use Illuminate\Database\Seeder;

class WelcomeContentSeeder extends Seeder
{
    public function run(): void
    {
        WelcomeContent::create([
            'meta_title' => 'CBTA 254 | Educación Media Superior',
            'meta_description' => 'CBTA 254 - Centro de Bachillerato Tecnológico Agropecuario No. 254. Educación Media Superior de Calidad en México.',
            'hero_badge' => 'Educación Media Superior — Modalidad Presencial',
            'hero_title' => 'CBTA No. 254: Por Una Educación, Científica y Crítica',
            'hero_description' => 'Formamos técnicos competentes con valores, capaces de enfrentar los retos del sector agropecuario y de la sociedad mexicana del siglo XXI.',
            'hero_image' => 'https://images.unsplash.com/photo-1588072432836-e10032774350?q=80&w=2072&auto=format&fit=crop',
            'hero_btn_text' => 'Conocer Oferta Educativa',
            'hero_btn_secondary_text' => 'Ver Convocatorias',
            'careers' => [
                [
                    'title' => 'Técnico en Agropecuario',
                    'description' => 'Formación en producción agropecuaria, manejo sustentable de recursos y tecnología aplicada al campo.',
                    'icon' => 'leaf',
                    'color' => 'green',
                ],
                [
                    'title' => 'Técnico en Ofimática',
                    'description' => 'Competencias digitales, administración documental y gestión de tecnologías de la información.',
                    'icon' => 'computer',
                    'color' => 'blue',
                ],
                [
                    'title' => 'Técnico en Administración de Emprendimientos',
                    'description' => 'Habilidades directivas, creación de proyectos productivos y gestión empresarial.',
                    'icon' => 'chart',
                    'color' => 'amber',
                ],
                [
                    'title' => 'Técnico en Agricultura Sustentable',
                    'description' => 'Producción agrícola ecológica, conservación de recursos naturales y soberanía alimentaria.',
                    'icon' => 'sun',
                    'color' => 'green',
                ],
            ],
            'gallery' => [
                [
                    'image' => 'https://images.unsplash.com/photo-1562774053-701939374585?q=80&w=1786&auto=format&fit=crop',
                    'label' => 'Aulas Modernas',
                ],
                [
                    'image' => 'https://images.unsplash.com/photo-1581092795360-fd1ca04f0952?q=80&w=1887&auto=format&fit=crop',
                    'label' => 'Talleres Agropecuarios',
                ],
                [
                    'image' => 'https://images.unsplash.com/photo-1523050854058-8df90110c7f1?q=80&w=2070&auto=format&fit=crop',
                    'label' => 'Laboratorios de Cómputo',
                ],
            ],
            'announcements' => [
                [
                    'badge' => 'Inscripciones',
                    'badge_color' => 'green',
                    'title' => 'Inscripciones Abiertas',
                    'description' => 'Ya están abiertas las inscripciones para el ciclo escolar 2026-2027. Acude a nuestras instalaciones con los documentos requeridos.',
                    'date' => '14 de junio, 2026',
                    'color' => 'from-green-700 to-green-500',
                ],
                [
                    'badge' => 'Becas',
                    'badge_color' => 'blue',
                    'title' => 'Becas Benito Juárez',
                    'description' => 'Convocatoria abierta para becas de educación media superior. Registra tu solicitud en línea antes del 31 de julio.',
                    'date' => '10 de junio, 2026',
                    'color' => 'from-blue-700 to-blue-500',
                ],
                [
                    'badge' => 'Evento',
                    'badge_color' => 'amber',
                    'title' => 'Feria de Ciencias 2026',
                    'description' => 'Participa en la Feria de Ciencias del CBTA 254. Proyectos, experimentos y muestras académicas de todas las especialidades.',
                    'date' => '5 de junio, 2026',
                    'color' => 'from-amber-700 to-amber-500',
                ],
            ],
            'contact_address' => 'Carretera Federal México 15, Km. 178, S/N, 63470',
            'contact_email' => 'contacto@cbta254.edu.mx',
            'contact_phone' => '+52 (123) 456 7890',
            'footer_description' => 'Centro de Bachillerato Tecnológico Agropecuario No. 254. Educación Media Superior de Calidad.',
        ]);
    }
}
