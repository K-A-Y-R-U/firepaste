<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="login">
        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" wire:model="form.email" id="email" class="form-control @error('form.email') is-invalid @enderror" required autofocus autocomplete="username">
            @error('form.email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" wire:model="form.password" id="password" class="form-control @error('form.password') is-invalid @enderror" required autocomplete="current-password">
            @error('form.password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="mb-3 form-check">
            <input type="checkbox" wire:model="form.remember" id="remember" class="form-check-input">
            <label for="remember" class="form-check-label">Recordarme</label>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-primary" wire:navigate>¿Olvidaste tu contraseña?</a>
            @endif
            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
        </div>
    </form>
</div>