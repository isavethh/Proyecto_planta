@extends('admin')

@section('title', 'Usuarios')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h3 class="card-title mb-0">Usuarios Registrados</h3>
          <a href="{{ route('admin.envios') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-1"></i> Volver</a>
        </div>
        <div class="card-body p-0">
          <table class="table table-striped mb-0">
            <thead>
              <tr>
                <th style="width: 80px">ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th style="width: 160px">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @forelse(($usuarios ?? []) as $u)
                <tr>
                  <td>{{ $u->id }}</td>
                  <td>{{ $u->nombre ?? 'Usuario' }}</td>
                  <td>{{ $u->email ?? '-' }}</td>
                  <td>
                    <a href="{{ route('admin.usuarios.envios', $u->id) }}" class="btn btn-primary btn-sm">
                      <i class="fas fa-list mr-1"></i> Pedidos
                    </a>
                  </td>
                </tr>
              @empty
                <tr><td colspan="4" class="text-center py-4 text-muted">No hay usuarios</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection


