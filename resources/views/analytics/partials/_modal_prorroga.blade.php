{{-- Modal Slide-over: Solicitud de Prórroga --}}
<div x-show="prorrogaModalOpen"
     x-cloak
     class="fixed inset-0 z-50 overflow-hidden"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">

    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-space/80 backdrop-blur-sm" @click="closeProrrogaModal()"></div>

    {{-- Panel --}}
    <div class="absolute inset-y-0 right-0 flex max-w-full pl-10"
         x-show="prorrogaModalOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full">

        <div class="w-screen max-w-lg">
            <div class="h-full flex flex-col bg-matter shadow-2xl border-l border-matter-light overflow-y-auto">

                {{-- Header --}}
                <div class="px-6 py-5 border-b border-matter-light bg-gradient-to-r from-quantum-500/10 to-void-500/10">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-white">Solicitud de Prórroga</h3>
                            <p class="text-sm text-gray-400 mt-1" x-text="prorrogaProjectName"></p>
                        </div>
                        <button @click="closeProrrogaModal()" class="text-gray-400 hover:text-white transition-colors p-1">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Form Content --}}
                <div class="flex-1 px-6 py-6 space-y-6 overflow-y-auto">

                    {{-- Sección 1: Tipo y Causa --}}
                    <div class="space-y-4">
                        <h4 class="text-sm font-semibold text-gray-300 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-6 h-6 bg-quantum-500/20 rounded-full flex items-center justify-center text-xs text-quantum-400 font-bold">1</span>
                            Tipo y Causa
                        </h4>

                        {{-- Tipo de solicitud --}}
                        <div>
                            <label class="text-sm text-gray-400 mb-2 block">Tipo de solicitud</label>
                            <div class="flex gap-3">
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" x-model="prorrogaForm.tipo_solicitud" value="prorroga" class="sr-only peer">
                                    <div @click="prorrogaForm.tipo_solicitud = 'prorroga'"
                                         :class="prorrogaForm.tipo_solicitud === 'prorroga' ? 'border-quantum-500/50 bg-quantum-500/10 text-white' : 'border-matter-light text-gray-400'"
                                         class="p-3 rounded-quantum border text-center transition-all hover:border-gray-500">
                                        <svg class="w-5 h-5 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-sm font-medium">Prórroga</span>
                                    </div>
                                </label>
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" x-model="prorrogaForm.tipo_solicitud" value="suspension" class="sr-only peer">
                                    <div @click="prorrogaForm.tipo_solicitud = 'suspension'"
                                         :class="prorrogaForm.tipo_solicitud === 'suspension' ? 'border-amber-500/50 bg-amber-500/10 text-white' : 'border-matter-light text-gray-400'"
                                         class="p-3 rounded-quantum border text-center transition-all hover:border-gray-500">
                                        <svg class="w-5 h-5 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-sm font-medium">Suspensión</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Causa principal --}}
                        <div>
                            <label class="text-sm text-gray-400 mb-2 block">Causa principal <span class="text-red-400">*</span></label>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="cursor-pointer">
                                    <input type="radio" x-model="prorrogaForm.causa_tipo" value="fuerza_mayor" class="sr-only peer" @change="prorrogaForm.causa_subtipo = ''">
                                    <div @click="prorrogaForm.causa_tipo = 'fuerza_mayor'; prorrogaForm.causa_subtipo = ''"
                                         :class="prorrogaForm.causa_tipo === 'fuerza_mayor' ? 'border-red-500/50 bg-red-500/10' : 'border-matter-light'"
                                         class="p-3 rounded-quantum border transition-all hover:border-gray-500">
                                        <div class="flex items-center gap-2 mb-1">
                                            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                            <span class="text-sm font-medium text-white">Fuerza Mayor</span>
                                        </div>
                                        <span class="text-xs text-gray-500">Eventos naturales</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" x-model="prorrogaForm.causa_tipo" value="caso_fortuito" class="sr-only peer" @change="prorrogaForm.causa_subtipo = ''">
                                    <div @click="prorrogaForm.causa_tipo = 'caso_fortuito'; prorrogaForm.causa_subtipo = ''"
                                         :class="prorrogaForm.causa_tipo === 'caso_fortuito' ? 'border-orange-500/50 bg-orange-500/10' : 'border-matter-light'"
                                         class="p-3 rounded-quantum border transition-all hover:border-gray-500">
                                        <div class="flex items-center gap-2 mb-1">
                                            <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                            <span class="text-sm font-medium text-white">Caso Fortuito</span>
                                        </div>
                                        <span class="text-xs text-gray-500">Eventos humanos</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" x-model="prorrogaForm.causa_tipo" value="necesidad_servicio" class="sr-only peer" @change="prorrogaForm.causa_subtipo = ''">
                                    <div @click="prorrogaForm.causa_tipo = 'necesidad_servicio'; prorrogaForm.causa_subtipo = ''"
                                         :class="prorrogaForm.causa_tipo === 'necesidad_servicio' ? 'border-blue-500/50 bg-blue-500/10' : 'border-matter-light'"
                                         class="p-3 rounded-quantum border transition-all hover:border-gray-500">
                                        <div class="flex items-center gap-2 mb-1">
                                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <span class="text-sm font-medium text-white">Necesidad</span>
                                        </div>
                                        <span class="text-xs text-gray-500">Ajustes operacionales</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" x-model="prorrogaForm.causa_tipo" value="mutuo_acuerdo" class="sr-only peer" @change="prorrogaForm.causa_subtipo = ''">
                                    <div @click="prorrogaForm.causa_tipo = 'mutuo_acuerdo'; prorrogaForm.causa_subtipo = ''"
                                         :class="prorrogaForm.causa_tipo === 'mutuo_acuerdo' ? 'border-green-500/50 bg-green-500/10' : 'border-matter-light'"
                                         class="p-3 rounded-quantum border transition-all hover:border-gray-500">
                                        <div class="flex items-center gap-2 mb-1">
                                            <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <span class="text-sm font-medium text-white">Mutuo Acuerdo</span>
                                        </div>
                                        <span class="text-xs text-gray-500">Convenio entre partes</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Subcategoría --}}
                        <div x-show="prorrogaForm.causa_tipo" x-transition>
                            <label class="text-sm text-gray-400 mb-2 block">Subcategoría</label>
                            <select x-model="prorrogaForm.causa_subtipo"
                                    class="w-full bg-matter border border-matter-light rounded-quantum px-4 py-2.5 text-sm text-gray-300 focus:ring-quantum-500 focus:border-quantum-500">
                                <option value="">Seleccionar...</option>
                                <template x-for="opt in causaSubtipoOptions" :key="opt.value">
                                    <option :value="opt.value" x-text="opt.label"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    {{-- Sección 2: Detalles de Extensión --}}
                    <div class="space-y-4 pt-2 border-t border-matter-light/30">
                        <h4 class="text-sm font-semibold text-gray-300 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-6 h-6 bg-quantum-500/20 rounded-full flex items-center justify-center text-xs text-quantum-400 font-bold">2</span>
                            Detalles de la Extensión
                        </h4>

                        {{-- Días solicitados --}}
                        <div>
                            <label class="text-sm text-gray-400 mb-2 block">Días solicitados <span class="text-red-400">*</span></label>
                            <input type="number" x-model.number="prorrogaForm.dias_solicitados"
                                   min="1" max="365" placeholder="30"
                                   class="w-full bg-matter border border-matter-light rounded-quantum px-4 py-2.5 text-sm text-white focus:ring-quantum-500 focus:border-quantum-500 placeholder-gray-600">
                        </div>

                        {{-- Cálculo reactivo de fechas --}}
                        <div x-show="prorrogaForm.dias_solicitados > 0" x-transition
                             class="p-3 bg-quantum-500/5 border border-quantum-500/20 rounded-quantum">
                            <div class="flex items-center justify-between text-sm mb-2">
                                <span class="text-gray-400">Fecha fin actual</span>
                                <span class="text-white font-medium" x-text="prorrogaProjectFechaFin"></span>
                            </div>
                            <div class="flex items-center justify-center my-2">
                                <svg class="w-5 h-5 text-quantum-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                </svg>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-400">Nueva fecha fin</span>
                                <span class="text-quantum-400 font-bold" x-text="prorrogaFechaFinPropuesta"></span>
                            </div>
                            <div class="mt-2 text-center">
                                <span class="text-xs px-2 py-1 rounded-full bg-quantum-500/20 text-quantum-400"
                                      x-text="'+' + prorrogaForm.dias_solicitados + ' días'"></span>
                            </div>
                        </div>

                        {{-- Departamento (solo fuerza mayor) --}}
                        <div x-show="prorrogaForm.causa_tipo === 'fuerza_mayor'" x-transition>
                            <label class="text-sm text-gray-400 mb-2 block">Departamento afectado</label>
                            <select x-model="prorrogaForm.departamento_afectado"
                                    class="w-full bg-matter border border-matter-light rounded-quantum px-4 py-2.5 text-sm text-gray-300 focus:ring-quantum-500 focus:border-quantum-500">
                                <option value="">Seleccionar departamento...</option>
                                @foreach(\App\Models\Prorroga::DEPARTAMENTOS_COLOMBIA as $depto)
                                    <option value="{{ $depto }}">{{ $depto }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Sección 3: Justificación Legal --}}
                    <div class="space-y-4 pt-2 border-t border-matter-light/30">
                        <h4 class="text-sm font-semibold text-gray-300 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-6 h-6 bg-quantum-500/20 rounded-full flex items-center justify-center text-xs text-quantum-400 font-bold">3</span>
                            Justificación Legal
                        </h4>

                        {{-- Justificación --}}
                        <div>
                            <label class="text-sm text-gray-400 mb-2 block">Justificación <span class="text-red-400">*</span></label>
                            <textarea x-model="prorrogaForm.justificacion" rows="4"
                                      placeholder="Describa detalladamente la razón de la solicitud de prórroga (mín. 30 caracteres)..."
                                      class="w-full bg-matter border border-matter-light rounded-quantum px-4 py-2.5 text-sm text-white focus:ring-quantum-500 focus:border-quantum-500 placeholder-gray-600 resize-none"></textarea>
                            <div class="flex justify-between mt-1">
                                <span class="text-xs" :class="(prorrogaForm.justificacion?.length || 0) >= 30 ? 'text-green-400' : 'text-gray-500'"
                                      x-text="(prorrogaForm.justificacion?.length || 0) + ' / 30 mín.'"></span>
                            </div>
                        </div>

                        {{-- Campos IDEAM y Declaratoria (solo fuerza mayor) --}}
                        <template x-if="prorrogaForm.causa_tipo === 'fuerza_mayor'">
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm text-gray-400 mb-2 block">Referencia IDEAM</label>
                                    <input type="text" x-model="prorrogaForm.referencia_ideam"
                                           placeholder="Ej: Boletín meteorológico #142-2026"
                                           class="w-full bg-matter border border-matter-light rounded-quantum px-4 py-2.5 text-sm text-white focus:ring-quantum-500 focus:border-quantum-500 placeholder-gray-600">
                                    <span class="text-xs text-gray-500 mt-1 block">Boletín del Instituto de Hidrología, Meteorología y Estudios Ambientales</span>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-400 mb-2 block">Referencia Declaratoria</label>
                                    <input type="text" x-model="prorrogaForm.referencia_declaratoria"
                                           placeholder="Ej: Decreto 1234 de 2026 - Emergencia departamental"
                                           class="w-full bg-matter border border-matter-light rounded-quantum px-4 py-2.5 text-sm text-white focus:ring-quantum-500 focus:border-quantum-500 placeholder-gray-600">
                                    <span class="text-xs text-gray-500 mt-1 block">Decreto de emergencia o declaratoria de desastre, si aplica</span>
                                </div>
                            </div>
                        </template>

                        {{-- Impacto (opcional) --}}
                        <div>
                            <label class="text-sm text-gray-400 mb-2 block">Impacto en el proyecto <span class="text-gray-600">(opcional)</span></label>
                            <textarea x-model="prorrogaForm.impacto_descripcion" rows="2"
                                      placeholder="Describa cómo afecta esta situación al alcance o entregables del proyecto..."
                                      class="w-full bg-matter border border-matter-light rounded-quantum px-4 py-2.5 text-sm text-white focus:ring-quantum-500 focus:border-quantum-500 placeholder-gray-600 resize-none"></textarea>
                        </div>
                    </div>

                    {{-- Sección 4: Evidencia --}}
                    <div class="space-y-4 pt-2 border-t border-matter-light/30">
                        <h4 class="text-sm font-semibold text-gray-300 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-6 h-6 bg-quantum-500/20 rounded-full flex items-center justify-center text-xs text-quantum-400 font-bold">4</span>
                            Evidencia
                        </h4>

                        <div class="relative">
                            <input type="file" x-ref="prorrogaFileInput"
                                   @change="prorrogaFile = $event.target.files[0]"
                                   accept=".pdf,.doc,.docx,.jpeg,.jpg,.png"
                                   class="sr-only">

                            <div @click="$refs.prorrogaFileInput.click()"
                                 @dragover.prevent="$el.classList.add('border-quantum-500', 'bg-quantum-500/5')"
                                 @dragleave="$el.classList.remove('border-quantum-500', 'bg-quantum-500/5')"
                                 @drop.prevent="prorrogaFile = $event.dataTransfer.files[0]; $el.classList.remove('border-quantum-500', 'bg-quantum-500/5')"
                                 class="border-2 border-dashed border-matter-light rounded-quantum p-6 text-center cursor-pointer hover:border-quantum-500/50 transition-all">

                                <template x-if="!prorrogaFile">
                                    <div>
                                        <svg class="w-10 h-10 text-gray-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                        <p class="text-sm text-gray-400">Arrastre o haga clic para seleccionar</p>
                                        <p class="text-xs text-gray-600 mt-1">PDF, DOC, DOCX, JPG, PNG — Máx. 10MB</p>
                                    </div>
                                </template>

                                <template x-if="prorrogaFile">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-8 h-8 text-quantum-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <div class="flex-1 text-left">
                                            <p class="text-sm text-white truncate" x-text="prorrogaFile.name"></p>
                                            <p class="text-xs text-gray-500" x-text="(prorrogaFile.size / 1024 / 1024).toFixed(2) + ' MB'"></p>
                                        </div>
                                        <button @click.stop="prorrogaFile = null; $refs.prorrogaFileInput.value = ''" class="text-red-400 hover:text-red-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Se recomienda consolidar toda la evidencia en un solo archivo PDF
                        </p>
                    </div>

                    {{-- Errores de validación --}}
                    <template x-if="Object.keys(prorrogaErrors).length > 0">
                        <div class="p-3 bg-red-500/10 border border-red-500/30 rounded-quantum">
                            <template x-for="(msgs, field) in prorrogaErrors" :key="field">
                                <template x-for="msg in msgs" :key="msg">
                                    <p class="text-sm text-red-400" x-text="msg"></p>
                                </template>
                            </template>
                        </div>
                    </template>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 border-t border-matter-light bg-matter flex items-center justify-end gap-3">
                    <button @click="closeProrrogaModal()"
                            class="px-5 py-2.5 rounded-quantum text-sm text-gray-400 hover:text-white hover:bg-matter-light transition-all">
                        Cancelar
                    </button>
                    <button @click="submitProrroga()"
                            :disabled="prorrogaSubmitting"
                            class="px-5 py-2.5 rounded-quantum text-sm font-medium text-white
                                   bg-gradient-to-r from-quantum-500 to-void-500
                                   hover:from-quantum-600 hover:to-void-600
                                   disabled:opacity-50 disabled:cursor-not-allowed
                                   transition-all flex items-center gap-2">
                        <template x-if="prorrogaSubmitting">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                        </template>
                        <span x-text="prorrogaSubmitting ? 'Enviando...' : 'Enviar Solicitud'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
