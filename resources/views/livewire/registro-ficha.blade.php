<div
    x-data="{ showNotice: false, noticeMessage: '' }"
    x-on:notify.window="if ($event.detail) { showNotice = true; noticeMessage = $event.detail.message; setTimeout(() => showNotice = false, 4000) }"
    x-on:ficha-creada.window="if (window.fichaWindow && !window.fichaWindow.closed) { window.fichaWindow.location.href = $event.detail.url; } else { window.open($event.detail.url, '_blank'); } window.fichaWindow = null;">
    <div x-show="showNotice" x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="fixed top-4 right-4 z-[100] rounded-lg bg-yellow-100 border border-yellow-300 px-5 py-3 text-sm text-yellow-800 shadow-lg"
        x-text="noticeMessage">
    </div>

    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-start justify-center pt-10 sm:pt-16 overflow-y-auto"
            x-data="{ show: @entangle('showModal') }"
            x-show="show"
            x-cloak>
            <div class="fixed inset-0 bg-black/60" wire:click="closeModal"></div>

            <div class="relative w-full max-w-3xl mx-4 bg-white rounded-2xl shadow-2xl z-10">
                <div class="sticky top-0 z-20 flex items-center justify-between px-6 py-4 border-b bg-white rounded-t-2xl">
                    <h2 class="text-lg font-bold text-gray-900">Registro de Ficha de Nuevo Ingreso</h2>
                    <button type="button" wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit="submit" x-on:submit="window.fichaWindow = window.open('', '_blank')" class="p-6 space-y-6">
                    @if ($errors->has('general'))
                        <div class="rounded-lg bg-red-50 p-4 text-sm text-red-700">{{ $errors->first('general') }}</div>
                    @endif

                    <fieldset class="border rounded-lg p-4">
                        <legend class="text-sm font-semibold text-green-800 px-2">Datos del Estudiante</legend>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3">
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                                <input type="text" wire:model="nombre_completo"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                                @error('nombre_completo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Fecha de Nacimiento</label>
                                <input type="date" wire:model="fecha_nacimiento"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                                @error('fecha_nacimiento') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Edad</label>
                                <input type="number" wire:model="edad" min="0" max="100"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                                @error('edad') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Género</label>
                                <select wire:model="genero"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                                    <option value="">Seleccionar</option>
                                    <option value="femenino">Femenino</option>
                                    <option value="masculino">Masculino</option>
                                </select>
                                @error('genero') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Núm. Celular</label>
                                <input type="tel" wire:model="num_celular" maxlength="20"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                                @error('num_celular') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                                <input type="email" wire:model="correo_electronico"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                                @error('correo_electronico') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Dirección</label>
                                <textarea wire:model="direccion" rows="2"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm"></textarea>
                                @error('direccion') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Localidad</label>
                                <input type="text" wire:model="localidad"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                                @error('localidad') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Municipio</label>
                                <input type="text" wire:model="municipio"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                                @error('municipio') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border rounded-lg p-4">
                        <legend class="text-sm font-semibold text-green-800 px-2">Datos de Secundaria</legend>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3">
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Secundaria de Procedencia</label>
                                <input type="text" wire:model="secundaria_procedencia"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                                @error('secundaria_procedencia') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tipo de Secundaria</label>
                                <select wire:model="tipo_secundaria"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                                    <option value="">Seleccionar</option>
                                    <option value="tecnica">Técnica</option>
                                    <option value="telesecundaria">Telesecundaria</option>
                                </select>
                                @error('tipo_secundaria') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Turno</label>
                                <select wire:model="turno"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                                    <option value="">Seleccionar</option>
                                    <option value="matutino">Matutino</option>
                                    <option value="vespertino">Vespertino</option>
                                </select>
                                @error('turno') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Localidad de la Escuela de Procedencia</label>
                                <input type="text" wire:model="localidad_secundaria"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                                @error('localidad_secundaria') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Promedio</label>
                                <input type="number" wire:model="promedio" step="0.01" min="0" max="100"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                                @error('promedio') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border rounded-lg p-4">
                        <legend class="text-sm font-semibold text-green-800 px-2">Datos del Tutor Legal</legend>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3">
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                                <input type="text" wire:model="tutor_nombre_completo"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                                @error('tutor_nombre_completo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Núm. Celular</label>
                                <input type="tel" wire:model="tutor_num_celular" maxlength="20"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                                @error('tutor_num_celular') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                                <input type="email" wire:model="tutor_correo_electronico"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                                @error('tutor_correo_electronico') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </fieldset>

                    <div class="flex items-center justify-end gap-3">
                        <button type="button" wire:click="closeModal"
                            class="rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="rounded-lg bg-green-800 px-6 py-2 text-sm font-semibold text-white hover:bg-green-700 transition-colors">
                            Guardar y Descargar Ficha
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
