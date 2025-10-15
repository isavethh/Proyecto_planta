@extends('layouts.guest')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="card card-primary shadow-sm">
    <div class="card-header">
        <h3 class="card-title mb-0">Iniciar Sesión</h3>
    </div>

    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="email">Usuario</label>
                <input type="text" class="form-control" id="email" name="email"
                       value="{{ old('email') }}" required autofocus>
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="alert alert-info">
                <strong>Cuentas de prueba:</strong><br>
                <strong>Administrador:</strong> admin / admin1<br>
                <strong>Usuario:</strong> usuario / usuario1
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
        </div>
    </form>
</div>
@endsection

