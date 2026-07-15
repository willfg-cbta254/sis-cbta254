<?php

namespace App\Livewire;

use App\Models\Ficha;
use App\Models\FichaSetting;
use Livewire\Attributes\On;
use Livewire\Component;

class RegistroFicha extends Component
{
    public bool $showModal = false;

    public string $nombre_completo = '';

    public string $fecha_nacimiento = '';

    public int $edad = 0;

    public string $genero = '';

    public string $direccion = '';

    public string $num_celular = '';

    public string $correo_electronico = '';

    public string $localidad = '';

    public string $municipio = '';

    public string $secundaria_procedencia = '';

    public string $tipo_secundaria = '';

    public string $localidad_secundaria = '';

    public string $turno = '';

    public string $promedio = '';

    public string $tutor_nombre_completo = '';

    public string $tutor_num_celular = '';

    public string $tutor_correo_electronico = '';

    public ?int $createdFichaId = null;

    #[On('openFichaModal')]
    protected function rules(): array
    {
        return [
            'nombre_completo' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'edad' => 'required|integer|min:0|max:100',
            'genero' => 'required|in:femenino,masculino',
            'direccion' => 'required|string',
            'num_celular' => 'required|string|max:20',
            'correo_electronico' => 'required|email|max:255',
            'localidad' => 'required|string|max:255',
            'municipio' => 'required|string|max:255',
            'secundaria_procedencia' => 'required|string|max:255',
            'tipo_secundaria' => 'required|in:tecnica,telesecundaria',
            'localidad_secundaria' => 'required|string|max:255',
            'turno' => 'required|in:matutino,vespertino',
            'promedio' => 'required|numeric|min:0|max:100',
            'tutor_nombre_completo' => 'required|string|max:255',
            'tutor_num_celular' => 'required|string|max:20',
            'tutor_correo_electronico' => 'required|email|max:255',
        ];
    }

    public function getIsOpenProperty(): bool
    {
        $settings = FichaSetting::first();

        return $settings && $settings->is_open;
    }

    public function openModal(): void
    {
        $settings = FichaSetting::first();

        if (! $settings || ! $settings->is_open) {
            $this->dispatch('notify', message: 'El registro de fichas está cerrado actualmente.');

            return;
        }

        $this->resetErrorBag();
        $this->createdFichaId = null;
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->reset([
            'nombre_completo', 'fecha_nacimiento', 'edad', 'genero', 'direccion',
            'num_celular', 'correo_electronico', 'localidad', 'municipio',
            'secundaria_procedencia', 'tipo_secundaria', 'localidad_secundaria',
            'turno', 'promedio', 'tutor_nombre_completo', 'tutor_num_celular',
            'tutor_correo_electronico',
        ]);
    }

    public function submit(): void
    {
        $this->validate();

        $settings = FichaSetting::first();

        if (! $settings || ! $settings->is_open) {
            $this->addError('general', 'El registro de fichas está cerrado actualmente.');

            return;
        }

        $folioNum = $settings->current_folio;
        $folio = "{$folioNum}-{$settings->ciclo_escolar}";

        $ficha = Ficha::create([
            'folio' => $folio,
            'folio_num' => $folioNum,
            'ciclo_escolar' => $settings->ciclo_escolar,
            'nombre_completo' => $this->nombre_completo,
            'fecha_nacimiento' => $this->fecha_nacimiento,
            'edad' => $this->edad,
            'genero' => $this->genero,
            'direccion' => $this->direccion,
            'num_celular' => $this->num_celular,
            'correo_electronico' => $this->correo_electronico,
            'localidad' => $this->localidad,
            'municipio' => $this->municipio,
            'secundaria_procedencia' => $this->secundaria_procedencia,
            'tipo_secundaria' => $this->tipo_secundaria,
            'localidad_secundaria' => $this->localidad_secundaria,
            'turno' => $this->turno,
            'promedio' => $this->promedio,
            'tutor_nombre_completo' => $this->tutor_nombre_completo,
            'tutor_num_celular' => $this->tutor_num_celular,
            'tutor_correo_electronico' => $this->tutor_correo_electronico,
        ]);

        $settings->increment('current_folio');

        $this->resetForm();
        $this->showModal = false;

        $this->dispatch('ficha-creada', url: route('fichas.download', $ficha));
    }

    public function render()
    {
        return view('livewire.registro-ficha');
    }
}
