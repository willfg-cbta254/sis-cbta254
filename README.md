# Sistema DGETAyCM — CBTA 254

Sistema de gestión escolar para el **Centro de Bachillerato Tecnológico Agropecuario No. 254**. Desarrollado con Laravel 13, Filament 5 y MySQL.

---

## Arquitectura General

| Componente | Tecnología |
|------------|------------|
| Framework | Laravel 13 |
| Panel Administrativo | Filament 5 (3 paneles) |
| PHP | 8.4 |
| Base de datos | MySQL 8.4 |
| CSS/Tema | Tailwind CSS 4 |
| PDF | DomPDF (barryvdh/laravel-dompdf) |
| Roles/Permisos | Spatie Laravel Permission |
| Autenticación | Sanctum, Filament Breezy (2FA, avatares) |
| SQLite (cache/queue) | Cache, sesiones, jobs |

---

## Paneles del Sistema

Existen **3 paneles** independientes, cada uno con su propia URL, login y conjunto de recursos:

### 1. Administración (`/admin`)
Panel principal para la gestión integral del plantel.

| Grupo | Recursos |
|-------|----------|
| **Usuarios** | Usuarios, Alumnos, Profesores, Roles |
| **Catálogos** | Carreras, Periodos Escolares, Generaciones |
| **Académico** | Materias, Grupos, Carga Académica, Calificaciones, Asistencias, Inscripciones, Horarios |
| **Financiero** | Conceptos de Pago, Pagos |
| **Prefectura** | Faltas Reglamentarias, Horas Labor Social |
| **Página** | Blog, Galería, Contenido de Bienvenida, Anuncios, Fichas |

**Páginas personalizadas:**
- Configuración de fichas (abrir/cerar registro, ciclo escolar, folio actual)
- Respaldos de base de datos (crear, descargar, eliminar vía mysqldump)
- Horarios de docentes (vista general)

### 2. Alumno (`/alumno`)
Panel de consulta para estudiantes.

| Página | Descripción |
|--------|-------------|
| Dashboard | Estadísticas: materias cursando, % asistencia, promedio general, horas labor social |
| Mis Calificaciones | Tabla de calificaciones por parcial con promedios |
| Mis Asistencias | Historial de asistencias con filtros |
| Mi Horario | Horario semanal por día |
| Mis Materias | Materias inscritas en el período actual |
| Mis Pagos | Historial de pagos realizados |
| Mis Horas Labor Social | Detalle y progreso de horas labor social |
| Mis Avisos | Anuncios clasificados por tipo (comunitario, grupal, individual) |

### 3. Profesor (`/profesor`)
Panel para docentes y prefectos.

| Recurso/Página | Descripción |
|----------------|-------------|
| Dashboard | Estadísticas: materias, asistencias hoy, calificaciones |
| Mis Grupos | Grupos asignados (vista de tarjetas) |
| Mis Materias | Materias asignadas con total de horas |
| Asistencias | Registro de asistencias (filtrado por profesor) |
| Calificaciones | Captura de calificaciones (filtrado por profesor) |
| Cargar Calificaciones | Captura masiva por grupo/materia/parcial |
| Tomar Asistencia | Pase de lista por grupo/materia |
| Mi Horario | Horario semanal del docente |
| Faltas Reglamentarias | (solo Prefecto) |
| Horas Labor Social | (solo Prefecto) |

---

## Modelo de Datos

### Estructura Principal

```
Usuario ──┬── Alumno ──┬── Inscripcion ──┬── Grupo
          │             │                  └── PeriodoEscolar
          │             ├── Calificacion ──┬── Materia
          │             │                   ├── Profesor
          │             │                   ├── Grupo
          │             │                   └── PeriodoEscolar
          │             ├── Asistencia ──┬── Materia
          │             │                ├── Profesor
          │             │                └── Grupo
          │             ├── Pago ──────── ConceptoPago
          │             └── HoraLaborSocial ── FaltaReglamentaria
          │
          └── Profesor ──┬── CargaAcademica ──┬── Materia
                         │                     ├── Grupo
                         │                     ├── PeriodoEscolar
                         │                     └── Horario
                         ├── Asistencia
                         └── Calificacion
```

### Catálogos Base

| Entidad | Propósito |
|---------|-----------|
| `Carrera` | Programas educativos (clave, nombre, activo) |
| `Generacion` | Cohortes generacionales (clave, año inicio, año fin) |
| `PeriodoEscolar` | Períodos escolares (clave, fechas, activo) |
| `Materia` | Asignaturas con tipo y semestre |
| `Grupo` | Grupos vinculados a carrera, generación y semestre |
| `ConceptoPago` | Conceptos de pago configurables |
| `FaltaReglamentaria` | Tipos de falta con horas asignadas |

### Entidades Transaccionales

| Entidad | Descripción |
|---------|-------------|
| `Inscripcion` | Alumno → Grupo + PeriodoEscolar (con status) |
| `CargaAcademica` | Profesor → Materia + Grupo + PeriodoEscolar |
| `Horario` | Día, hora inicio/fin, aula para una carga académica |
| `Calificacion` | 3 parciales, promedio, extraordinario, recursamiento |
| `Asistencia` | Registro diario por alumno/materia con estado |
| `Pago` | Registro de pago con referencia, método, estatus |
| `HoraLaborSocial` | Horas asignadas vs cumplidas por falta |

### Contenido del Sitio

| Entidad | Descripción |
|---------|-------------|
| `BlogPost` | Entradas de blog con slug automático |
| `Gallery` | Imágenes de galería con orden y categoría |
| `Announcement` | Anuncios clasificados por tipo (community/group/student) |
| `Ficha` | Solicitudes de pre-registro (aspirantes) |
| `WelcomeContent` | Configuración de la landing page (hero, contacto, etc.) |
| `FichaSetting` | Control de apertura/cierre de registro de aspirantes |

### Enumeraciones

**`Semestre`**: Primero, Segundo, Tercero, Cuarto, Quinto, Sexto

**`TipoMateria`**: Curriculum Fundamental, Curriculum Fundamental Extendida, Curriculum Laboral, Curriculum Ampliado

---

## Roles y Permisos

| Rol | Acceso | Permisos |
|-----|--------|----------|
| **Administrador** | Panel Admin (`/admin`) | 89 permisos (todos) |
| **Profesor** | Panel Profesor (`/profesor`) | CRUD materias, grupos, alumnos, asistencias, calificaciones |
| **Alumno** | Panel Alumno (`/alumno`) | Sin permisos directos (acceso vía páginas con `can()`) |
| **Prefecto** | Panel Profesor (`/profesor`) | CRUD faltas reglamentarias y horas labor social |
| **Admin. Redes Sociales** | Panel Admin (`/admin`) | CRUD blog |

Los permisos se agrupan en 16 módulos con operaciones: `ver`, `crear`, `editar`, `eliminar` (más `ver_any`, `ver_own` en algunos casos).

---

## Generación de PDFs

Se utiliza **DomPDF** para 3 tipos de documentos:

| PDF | Ruta | Controlador |
|-----|------|-------------|
| Boleta de calificaciones | `/boletas/{alumno}` | `BoletaController@download` |
| Ficha de registro | `/fichas/{ficha}/descargar` | `FichaController@download` |
| Recibo de pago | `/pagos/{pago}/recibo` | `PagoReciboController@download` |

Los PDFs incorporan logotipos de SEP, DGETAyCM y CBTA 254 como imágenes base64.

---

## Rutas Públicas

| Ruta | Controlador | Vista |
|------|-------------|-------|
| `/` | — | `welcome.blade.php` |
| `/blog` | `BlogController@index` | `blog.index` |
| `/blog/{post}` | `BlogController@show` | `blog.show` |
| `/galeria` | `BlogController@gallery` | `blog.gallery` |

---

## Componente Livewire

**`RegistroFicha`** — Modal de registro para aspirantes con 3 secciones:
1. Datos del aspirante
2. Datos de secundaria
3. Datos del tutor

Valida campos, genera folio auto-incremental, crea registro en `fichas` y ofrece descarga de PDF.

---

## Comandos Artisan

| Comando | Descripción |
|---------|-------------|
| `php artisan alumnos:sync` | Asigna rol "Alumno" a usuarios sin rol y crea perfiles Alumno |

---

## Importación CSV

8 importadores para carga masiva desde archivos CSV/Excel a través de Filament:

- AlumnoImporter (crea usuario + perfil + inscripción a grupo)
- ProfesorImporter (crea usuario + perfil)
- UserImporter (crea usuarios genéricos)
- GeneracionImporter, GrupoImporter, InscripcionImporter
- MateriaImporter, PeriodoEscolarImporter

---

## Respaldos

El sistema incluye:
- **Spatie Laravel Backup** configurado para respaldar `storage/app` y `storage/logs`
- **Página personalizada** en admin para crear, descargar y eliminar respaldos vía mysqldump
- Estrategia de limpieza: 7 días, 16 diarios, 8 semanales, 4 mensuales, 2 anuales

---

## Landing Page

La página de bienvenida (`/`) es completamente configurable desde el panel admin mediante el modelo `WelcomeContent`:

- Hero: badge, título, descripción, imagen, botones
- Sección de carreras
- Galería de imágenes
- Anuncios destacados
- Información de contacto (dirección, email, teléfono)
- Descripción del footer

---

## Vistas Disponibles

```
resources/views/
├── blog/
│   ├── index.blade.php        # Listado de posts (paginación 9)
│   ├── show.blade.php         # Post individual
│   └── gallery.blade.php      # Galería de imágenes (paginación 24)
├── errors/
│   └── 503.blade.php          # Página de mantenimiento
├── filament/
│   ├── admin/pages/           # Páginas custom del admin
│   └── pages/                 # Páginas de paneles (10 páginas)
├── livewire/
│   └── registro-ficha.blade.php
├── pdf/
│   ├── boleta-calificaciones.blade.php
│   ├── ficha.blade.php
│   └── recibo-pago.blade.php
└── welcome.blade.php          # Landing page
```
