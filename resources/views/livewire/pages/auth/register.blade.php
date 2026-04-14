<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="auth-card">

        <div class="auth-card-title">{{ __("Crear cuenta") }}</div>
        <div class="auth-card-subtitle">{{ __("unete a Firepaste hoy, es gratis") }}</div>

        <form wire:submit="register">
            <!-- Nombre -->
            <div class="auth-field">
                <label for="name" class="auth-label">{{ __("Nombre") }}</label>
                <input
                    type="text"
                    wire:model="name"
                    id="name"
                    class="auth-input @error('name') is-invalid @enderror"
                    placeholder="Tu nombre"
                    required autofocus autocomplete="name"
                >
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email -->
            <div class="auth-field">
                <label for="email" class="auth-label">{{ __("Correo Electrónico") }}</label>
                <input
                    type="email"
                    wire:model="email"
                    id="email"
                    class="auth-input @error('email') is-invalid @enderror"
                    placeholder="tu@correo.com"
                    required autocomplete="username"
                >
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Contraseña -->
            <div class="auth-field">
                <label for="password" class="auth-label">{{ __("Contrasena") }}</label>
                <input
                    type="password"
                    wire:model="password"
                    id="password"
                    class="auth-input @error('password') is-invalid @enderror"
                    placeholder="Mínimo 8 caracteres"
                    required autocomplete="new-password"
                >
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Confirmar Contraseña -->
            <div class="auth-field">
                <label for="password_confirmation" class="auth-label">{{ __("Confirmar Contrasena") }}</label>
                <input
                    type="password"
                    wire:model="password_confirmation"
                    id="password_confirmation"
                    class="auth-input @error('password_confirmation') is-invalid @enderror"
                    placeholder="Repite tu contraseña"
                    required autocomplete="new-password"
                >
                @error('password_confirmation')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="auth-btn" style="margin-top:0.5rem;">
                <span wire:loading.remove wire:target="register">{{ __("Crear cuenta") }}</span>
                <span wire:loading wire:target="register">{{ __("Creando cuenta...") }}</span>
            </button>
        </form>
    </div>

    <div class="auth-footer">
        {{ __("¿Ya tienes cuenta?") }} <a href="{{ route('login') }}">{{ __("Inicia sesion") }}</a>
    </div>
</div>