<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';
    public bool $confirmingDeletion = false;

    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    @if(!$confirmingDeletion)
        <button type="button" wire:click="$set('confirmingDeletion', true)" class="delete-btn">
            <i class="bi bi-trash3 me-2"></i>Eliminar mi cuenta
        </button>
    @else
        <div class="confirm-box">
            <p class="confirm-text">
                <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i>
                Esta acción es irreversible. Ingresa tu contraseña para confirmar.
            </p>
            <form wire:submit="deleteUser">
                <div class="mb-3">
                    <label class="form-label-custom">Contraseña</label>
                    <input type="password" wire:model="password" class="form-input-custom @error('password') is-invalid @enderror" placeholder="Tu contraseña actual" autocomplete="current-password">
                    @error('password') <span class="invalid-msg">{{ $message }}</span> @enderror
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="delete-btn-confirm">
                        <i class="bi bi-trash3 me-2"></i>Sí, eliminar cuenta
                    </button>
                    <button type="button" wire:click="$set('confirmingDeletion', false)" class="cancel-btn">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    @endif

<style>
    .delete-btn { display: inline-flex; align-items: center; padding: 0.7rem 1.5rem; background: #fff; color: #dc2626; border: 2px solid #fecaca; border-radius: 10px; font-weight: 700; font-size: 0.9rem; cursor: pointer; transition: all 0.25s; }
    .delete-btn:hover { background: #fff5f5; border-color: #dc2626; }
    .confirm-box { background: #fff5f5; border: 1px solid #fecaca; border-radius: 12px; padding: 1.25rem; }
    .confirm-text { font-size: 0.88rem; color: #666; margin-bottom: 1rem; }
    .form-label-custom { display: block; font-size: 0.82rem; font-weight: 600; color: #444; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .form-input-custom { width: 100%; padding: 0.8rem 1rem; border: 2px solid #e8e8ee; border-radius: 10px; font-size: 0.95rem; color: #1a1a2e; background: #fff; transition: all 0.2s; outline: none; }
    .form-input-custom:focus { border-color: #ef4444; box-shadow: 0 0 0 4px rgba(239,68,68,0.1); }
    .form-input-custom.is-invalid { border-color: #ef4444; }
    .invalid-msg { font-size: 0.8rem; color: #ef4444; margin-top: 0.4rem; display: block; }
    .delete-btn-confirm { display: inline-flex; align-items: center; padding: 0.7rem 1.5rem; background: #dc2626; color: #fff; border: none; border-radius: 10px; font-weight: 700; font-size: 0.9rem; cursor: pointer; transition: all 0.25s; }
    .delete-btn-confirm:hover { background: #b91c1c; transform: translateY(-1px); }
    .cancel-btn { display: inline-flex; align-items: center; padding: 0.7rem 1.5rem; background: #f1f3f5; color: #495057; border: none; border-radius: 10px; font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: all 0.2s; }
    .cancel-btn:hover { background: #e9ecef; }
</style>
</div>