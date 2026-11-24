<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
new class extends Component {
    public $email = '';
    public $password = '';
    public function ingresar()
    {
    $this->validate(['email' => 'required|email' , 'password' => 'required']);
    if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
    session()->regenerate();
    return redirect()->route('productos.index' );
    }
    $this->addError('email', 'Correo o contraseña incorrectos.' );
    }
};

$clave = 'login:' . $this->email;
if (RateLimiter::tooManyAttempts ($clave, 5)) {
 $this->addError('email', 'Demasiados intentos. Espera '
 . RateLimiter::availableIn($clave) . ' segundos.');
 return;
}

?>

<div>
    <form wire:submit="ingresar">
        <input type="email" wire:model="email" placeholder="Correo">
        @error('email') <span>{{ $message }}</span> @enderror
        <input type="password" wire:model="password" placeholder="Contraseña">
        <button type="submit">Ingresar</button>
    </form>
</div>