<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class LoginForm extends Component
{
    public $email = '';

    public $password = '';

    public int $segundosRestantes = 0;

    public function ingresar()
    {
        $datos = $this->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $clave = 'login:' . mb_strtolower($datos['email']) . '|' . request()->ip();

        if (RateLimiter::tooManyAttempts($clave, 5)) {
            $this->segundosRestantes = RateLimiter::availableIn($clave);
            $this->addError('email', 'Demasiados intentos.');

            return;
        }

        if (Auth::attempt($datos)) {
            RateLimiter::clear($clave);
            $this->segundosRestantes = 0;
            session()->regenerate();

            return redirect()->route('admin.panel');
        }

        RateLimiter::hit($clave, 60);
        $this->addError('email', 'Correo o contraseña incorrectos.');

        if (RateLimiter::tooManyAttempts($clave, 5)) {
            $this->segundosRestantes = RateLimiter::availableIn($clave);
            $this->addError('email', 'Demasiados intentos.');
        }
    }

    public function actualizarCuentaRegresiva(): void
    {
        if ($this->segundosRestantes > 0) {
            $this->segundosRestantes--;
        }
    }

    public function render()
    {
        return view('livewire.login-form');
    }
}
