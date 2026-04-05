<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');
            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');
        $this->dispatch('password-updated');
    }
}; ?>

<div>
    <form wire:submit="updatePassword">
        <div class="row g-3 mb-4">
            <div class="col-12">
                <label class="form-label-custom">Contraseña Actual</label>
                <input type="password" wire:model="current_password" id="update_password_current_password" class="form-input-custom @error('current_password') is-invalid @enderror" autocomplete="current-password">
                @error('current_password') <span class="invalid-msg">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label-custom">Nueva Contraseña</label>
                <input type="password" wire:model="password" id="update_password_password" class="form-input-custom @error('password') is-invalid @enderror" autocomplete="new-password">
                @error('password') <span class="invalid-msg">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label-custom">Confirmar Contraseña</label>
                <input type="password" wire:model="password_confirmation" id="update_password_password_confirmation" class="form-input-custom @error('password_confirmation') is-invalid @enderror" autocomplete="new-password">
                @error('password_confirmation') <span class="invalid-msg">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="save-btn warning">
                <i class="bi bi-shield-lock me-2"></i>Actualizar Contraseña
            </button>
            <span wire:loading wire:target="updatePassword" class="saving-text">Actualizando...</span>
            <x-action-message on="password-updated">
                <span class="saved-text"><i class="bi bi-check-circle-fill me-1"></i>Actualizada</span>
            </x-action-message>
        </div>
    </form>

<style>
    .form-label-custom { display: block; font-size: 0.82rem; font-weight: 600; color: #444; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .form-input-custom { width: 100%; padding: 0.8rem 1rem; border: 2px solid #e8e8ee; border-radius: 10px; font-size: 0.95rem; color: #1a1a2e; background: #fafafa; transition: all 0.2s; outline: none; }
    .form-input-custom:focus { border-color: #667eea; background: #fff; box-shadow: 0 0 0 4px rgba(102,126,234,0.1); }
    .form-input-custom.is-invalid { border-color: #ef4444; }
    .invalid-msg { font-size: 0.8rem; color: #ef4444; margin-top: 0.4rem; display: block; }
    .save-btn { display: inline-flex; align-items: center; padding: 0.7rem 1.5rem; background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; border: none; border-radius: 10px; font-weight: 700; font-size: 0.9rem; cursor: pointer; transition: all 0.25s; }
    .save-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(102,126,234,0.4); }
    .save-btn.warning { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .save-btn.warning:hover { box-shadow: 0 6px 20px rgba(245,158,11,0.4); }
    .saving-text { font-size: 0.85rem; color: #888; }
    .saved-text { font-size: 0.85rem; color: #16a34a; font-weight: 600; }
</style>
</div>