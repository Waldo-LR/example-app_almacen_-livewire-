<?php

use Livewire\Component;

new class extends Component {};
?>

<div>
    <div class="container mx-auto grid grid-cols-6 py-10 px-6 rounded-2xl bg-gradient-to-b from-gray-300 to-gray-500 shadow-lg max-w-7xl mt-7">
        <div class="col-span-1"></div>
        <div class="col-span-3">
            @livewire('productos-index')
        </div>
        <div class="col-span-2">
            @livewire('productos-form')
        </div>
    </div>
</div>
