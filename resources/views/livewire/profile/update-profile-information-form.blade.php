<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';

    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));
            return;
        }

        $user->sendEmailVerificationNotification();
        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<div>
    @if (session('status') === 'verification-link-sent')
        <div class="profile-alert success mb-3">
            <i class="bi bi-check-circle-fill me-2"></i>Se envió un nuevo enlace de verificación a tu correo.
        </div>
    @endif

    <form wire:submit="updateProfileInformation">
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label-custom">Nombre</label>
                <input type="text" wire:model="name" id="name" class="form-input-custom @error('name') is-invalid @enderror" required autofocus autocomplete="name">
                @error('name') <span class="invalid-msg">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label-custom">Correo Electrónico</label>
                <input type="email" wire:model="email" id="email" class="form-input-custom @error('email') is-invalid @enderror" required autocomplete="username">
                @error('email') <span class="invalid-msg">{{ $message }}</span> @enderror

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                    <div class="mt-2">
                        <span class="unverified-note">Tu email no está verificado.</span>
                        <button wire:click.prevent="sendVerification" class="resend-link">Reenviar verificación</button>
                    </div>
                @endif
            </div>
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="save-btn">
                <i class="bi bi-check-lg me-2"></i>Guardar Cambios
            </button>
            <span wire:loading wire:target="updateProfileInformation" class="saving-text">Guardando...</span>
            <x-action-message on="profile-updated">
                <span class="saved-text"><i class="bi bi-check-circle-fill me-1"></i>Guardado</span>
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
    .saving-text { font-size: 0.85rem; color: #888; }
    .saved-text { font-size: 0.85rem; color: #16a34a; font-weight: 600; }
    .unverified-note { font-size: 0.82rem; color: #d97706; }
    .resend-link { background: none; border: none; color: #667eea; font-size: 0.82rem; font-weight: 600; cursor: pointer; text-decoration: underline; padding: 0; margin-left: 4px; }
    .profile-alert { padding: 0.8rem 1rem; border-radius: 10px; font-size: 0.88rem; font-weight: 500; display: flex; align-items: center; }
    .profile-alert.success { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
</style>
</div>