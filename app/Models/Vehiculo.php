<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;

    protected $table = 'vehiculos';
    
    protected $fillable = [
        'transportista_id',
        'placa',
        'tipo',
        'capacidad_kg',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // Relación con transportista
    public function transportista()
    {
        return $this->belongsTo(Transportista::class);
    }
}
