<div>
    <style>
    .report-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.8rem;
        font-weight: 500;
        color: #dc3545;
        background: rgba(220, 53, 69, 0.07);
        border: 1.5px solid rgba(220, 53, 69, 0.2);
        border-radius: 8px;
        padding: 5px 12px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .report-btn:hover {
        background: rgba(220, 53, 69, 0.15);
        border-color: rgba(220, 53, 69, 0.45);
    }

    /* Overlay */
    .report-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }

    /* Modal */
    .report-modal {
        background: #fff;
        border-radius: 16px;
        width: 100%;
        max-width: 400px;
        box-shadow: 0 24px 60px rgba(0,0,0,0.18);
        overflow: hidden;
        animation: modalIn 0.2s ease;
    }
    @keyframes modalIn {
        from { transform: translateY(12px); opacity: 0; }
        to   { transform: translateY(0);   opacity: 1; }
    }
    .report-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 20px 14px;
        border-bottom: 1px solid #f0f0f0;
    }
    .report-modal-title {
        font-size: 1rem;
        font-weight: 700;
        color: #212529;
        margin: 0;
    }
    .report-close-btn {
        background: #f5f5f5;
        border: none;
        color: #666;
        font-size: 0.85rem;
        cursor: pointer;
        padding: 5px 8px;
        border-radius: 8px;
        transition: all 0.2s;
        line-height: 1;
    }
    .report-close-btn:hover { background: #ebebeb; color: #222; }

    .report-modal-body { padding: 18px 20px 20px; }

    .report-label {
        display: block;
        font-size: 0.82rem;
        font-weight: 700;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
    }

    /* Opciones de motivo */
    .report-options { display: flex; flex-direction: column; gap: 7px; }

    .report-option {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 14px;
        border: 1.5px solid #e9ecef;
        border-radius: 10px;
        cursor: pointer;
        font-size: 0.9rem;
        font-weight: 500;
        color: #495057;
        transition: all 0.15s ease;
        position: relative;
        user-select: none;
    }
    .report-option:hover {
        border-color: #aab4f5;
        background: #f7f8ff;
        color: #3d52d5;
    }
    .report-option.is-selected {
        border-color: #667eea;
        background: #eef0fd;
        color: #3d52d5;
        font-weight: 600;
    }

    /* Círculo de selección */
    .report-option-radio {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        border: 2px solid #ced4da;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.15s ease;
    }
    .report-option.is-selected .report-option-radio {
        border-color: #667eea;
        background: #667eea;
    }
    .report-option.is-selected .report-option-radio::after {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #fff;
        display: block;
    }

    .report-option-icon {
        font-size: 1.1rem;
        color: #adb5bd;
        transition: color 0.15s;
    }
    .report-option.is-selected .report-option-icon { color: #667eea; }

    /* Textarea */
    .report-textarea {
        border: 1.5px solid #e9ecef;
        border-radius: 10px;
        font-size: 0.88rem;
        resize: none;
        transition: border-color 0.2s;
        width: 100%;
    }
    .report-textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
    }

    /* Botones de acción */
    .report-actions {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
        margin-top: 16px;
    }
    .report-btn-cancel {
        padding: 8px 16px;
        border: 1.5px solid #dee2e6;
        border-radius: 8px;
        background: #fff;
        color: #6c757d;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }
    .report-btn-cancel:hover { background: #f8f9fa; border-color: #adb5bd; }
    .report-btn-submit {
        padding: 8px 18px;
        border: none;
        border-radius: 8px;
        background: #dc3545;
        color: #fff;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .report-btn-submit:hover { background: #c82333; }
    .report-btn-submit:disabled { opacity: 0.65; cursor: not-allowed; }
    </style>

    {{-- Botón reportar --}}
    <button wire:click="openModal" class="report-btn">
        <i class="bi bi-flag-fill"></i>
        <span>{{ __("Reportar") }}</span>
    </button>

    {{-- Modal --}}
    @if($showModal)
        <div class="report-overlay" wire:click.self="closeModal">
            <div class="report-modal">

                <div class="report-modal-header">
                    <p class="report-modal-title">
                        <i class="bi bi-flag-fill text-danger me-2"></i>{{ __("Reportar problema") }}
                    </p>
                    <button wire:click="closeModal" class="report-close-btn">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <div class="report-modal-body">
                    @if($submitted)
                        {{-- Éxito --}}
                        <div class="text-center py-2">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 2.8rem;"></i>
                            <h6 class="mt-3 fw-bold">{{ __("¡Reporte enviado!") }}</h6>
                            <p class="text-muted small mb-4">{{ __("Gracias por ayudarnos a mejorar el contenido.") }}</p>
                            <button wire:click="closeModal" class="report-btn-cancel px-4">{{ __("Cerrar") }}</button>
                        </div>
                    @else
                        @if($errorMsg)
                            <div class="alert alert-warning py-2 small mb-3">
                                <i class="bi bi-exclamation-triangle me-1"></i>{{ $errorMsg }}
                            </div>
                        @endif

                        {{-- Motivos --}}
                        <label class="report-label">{{ __("Motivo del reporte") }}</label>
                        <div class="report-options mb-3">

                            <div class="report-option {{ $reason === 'link_caido' ? 'is-selected' : '' }}"
                                 wire:click="$set('reason', 'link_caido')">
                                <div class="report-option-radio"></div>
                                <i class="bi bi-link-45deg report-option-icon"></i>
                                <span>{{ __("Enlace caído") }}</span>
                            </div>

                            <div class="report-option {{ $reason === 'contenido_incorrecto' ? 'is-selected' : '' }}"
                                 wire:click="$set('reason', 'contenido_incorrecto')">
                                <div class="report-option-radio"></div>
                                <i class="bi bi-x-circle report-option-icon"></i>
                                <span>{{ __("Contenido incorrecto") }}</span>
                            </div>

                            <div class="report-option {{ $reason === 'otro' ? 'is-selected' : '' }}"
                                 wire:click="$set('reason', 'otro')">
                                <div class="report-option-radio"></div>
                                <i class="bi bi-chat-dots report-option-icon"></i>
                                <span>{{ __("Otro") }}</span>
                            </div>

                        </div>
                        @error('reason')
                            <span class="text-danger small d-block mb-2">{{ $message }}</span>
                        @enderror

                        {{-- Descripción --}}
                        <label class="report-label">{{ __("Descripción") }} <span class="text-muted fw-normal normal-case" style="text-transform:none;">({{ __("opcional") }})</span></label>
                        <textarea
                            wire:model="description"
                            class="form-control report-textarea"
                            placeholder="{{ __("Describe el problema con más detalle...") }}"
                            maxlength="500"
                            rows="3"
                        ></textarea>
                        @error('description')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror

                        {{-- Acciones --}}
                        <div class="report-actions">
                            <button wire:click="closeModal" class="report-btn-cancel">{{ __("Cancelar") }}</button>
                            <button wire:click="submit" class="report-btn-submit" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="submit">
                                    <i class="bi bi-flag-fill"></i> {{ __("Enviar reporte") }}
                                </span>
                                <span wire:loading wire:target="submit">
                                    <span class="spinner-border spinner-border-sm"></span> {{ __("Enviando...") }}
                                </span>
                            </button>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    @endif
</div>