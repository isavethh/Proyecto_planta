<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'products';
    
    protected $fillable = [
        'nombre',
        'tipo',
        'unidad',
        'precio_unitario',
        'stock',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'precio_unitario' => 'decimal:2',
        'stock' => 'decimal:3',
    ];
}
