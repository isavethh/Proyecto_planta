@extends('layouts.guest')

@section('title', 'Crear Cuenta')

@section('content')
<div class="card card-primary shadow-sm">
    <div class="card-header">
        <h3 class="card-title mb-0">Crear Cuenta</h3>
    </div>

    <form method="POST" action="{{ route('register.post') }}">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Tu nombre" required>
            </div>
            <div class="form-group">
                <label for="email">Correo electrónico (opcional)</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="(dejar vacío por ahora)">
                <small class="form-text text-muted">Por ahora se permite vacío.</small>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <small class="form-text text-muted">Se iniciará sesión como usuario estándar.</small>
            </div>
        </div>
        <div class="card-footer text-center">
            <div class="btn-group">
                <a href="{{ route('login') }}" class="btn btn-secondary">Volver al login</a>
                <button type="submit" class="btn btn-primary">Crear cuenta</button>
            </div>
        </div>
    </form>
</div>
@endsection


