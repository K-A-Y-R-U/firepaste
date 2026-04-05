<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();
        $this->form->authenticate();
        Session::regenerate();
        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="auth-card">

        <div class="auth-card-title">Bienvenido de nuevo</div>
        <div class="auth-card-subtitle">Ingresa a tu cuenta para continuar</div>

        @if (session('status'))
            <div class="auth-alert">{{ session('status') }}</div>
        @endif

        <form wire:submit="login">
            <!-- Email -->
            <div class="auth-field">
                <label for="email" class="auth-label">Correo Electrónico</label>
                <input
                    type="email"
                    wire:model="form.email"
                    id="email"
                    class="auth-input @error('form.email') is-invalid @enderror"
                    placeholder="tu@correo.com"
                    required autofocus autocomplete="username"
                >
                @error('form.email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Contraseña -->
            <div class="auth-field">
                <label for="password" class="auth-label">Contraseña</label>
                <input
                    type="password"
                    wire:model="form.password"
                    id="password"
                    class="auth-input @error('form.password') is-invalid @enderror"
                    placeholder="••••••••"
                    required autocomplete="current-password"
                >
                @error('form.password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Recordarme + Olvidaste contraseña -->
            <div class="d-flex justify-content-between align-items-center mb-3" style="margin-bottom:1.5rem;">
                <div class="auth-check" style="margin-bottom:0;">
                    <input type="checkbox" wire:model="form.remember" id="remember">
                    <label for="remember">Recordarme</label>
                </div>
                @if (Route::has('password.request'))
                    <div class="auth-forgot" style="margin-bottom:0;">
                        <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                    </div>
                @endif
            </div>

            <button type="submit" class="auth-btn">
                <span wire:loading.remove wire:target="login">Iniciar Sesión</span>
                <span wire:loading wire:target="login">Iniciando...</span>
            </button>
        </form>
    </div>

    <div class="auth-footer">
        ¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate gratis</a>
    </div>
</div>