<?php

use Livewire\Component;


new class extends Component
{
    // 
};
?>

@extends('layouts.app')

@section('contenido')
    @livewire('productos-index')
@endsection

@section('formulario')
    @livewire('productos-form')
@endsection