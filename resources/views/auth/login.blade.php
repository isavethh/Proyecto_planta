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

            <div class="d-flex flex-column align-items-center mt-3">
                <button type="submit" class="btn btn-primary mb-2" style="min-width:200px;">Iniciar Sesión</button>
                <a href="{{ route('register') }}" class="btn btn-link">Crear cuenta</a>
            </div>
        </div>
    </form>
</div>
@endsection

