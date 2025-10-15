<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transportista extends Model
{
    use HasFactory;

    protected $table = 'transportistas';
    
    protected $fillable = [
        'nombre',
        'telefono',
        'licencia',
        'empresa',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // Relación con vehículos
    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class);
    }
}
