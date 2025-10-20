<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para gestión de envíos
 * Campos requeridos según especificaciones del usuario:
 * - dirección_desde_planta (hardcodeado)
 * - categoria_producto
 * - producto
 * - peso_producto_unidad
 * - unidades_totales
 * - transporte_sugerido
 * - transporte_seleccionado
 * - estado (pendiente, confirmado, recibido)
 * - precio_producto
 * - fecha_creacion
 * - fecha_confirmacion
 * - transportista_id
 * - vehiculo_id
 * - cliente_id (usuario que creó el envío)
 */
class Envio extends Model
{
    use HasFactory;

    protected $table = 'envios';

    protected $fillable = [
        'direccion_desde_planta',
        'destino_direccion',
        'destino_lat',
        'destino_lng',
        'distancia_km',
        'categoria_producto',
        'producto',
        'peso_producto_unidad',
        'unidades_totales',
        'transporte_sugerido',
        'transporte_seleccionado',
        'estado',
        'precio_producto',
        'items',
        'fecha_creacion',
        'fecha_confirmacion',
        'fecha_entrega_deseada',
        'transportista_id',
        'vehiculo_id',
        'cliente_id'
    ];

    protected $casts = [
        'fecha_creacion' => 'datetime',
        'fecha_confirmacion' => 'datetime',
        'peso_producto_unidad' => 'decimal:2',
        'precio_producto' => 'decimal:2',
        'unidades_totales' => 'integer',
        'fecha_entrega_deseada' => 'datetime',
        'items' => 'array'
    ];

    /**
     * Estados posibles del envío
     */
    const ESTADO_PENDIENTE = 'pendiente';
    const ESTADO_CONFIRMADO = 'confirmado';
    const ESTADO_EN_PROCESO = 'en_proceso';
    const ESTADO_RECIBIDO = 'recibido';

    /**
     * Categorías de productos disponibles
     */
    const CATEGORIAS_PRODUCTO = [
        'frutas',
        'verduras',
        'granos',
        'lacteos',
        'medicamentos'
    ];

    /**
     * Tipos de transporte disponibles
     */
    const TRANSPORTES_DISPONIBLES = [
        'aislado' => 'Transporte Aislado',
        'ventilado' => 'Transporte Ventilado',
        'refrigerado' => 'Transporte Refrigerado',
    ];

    /**
     * Obtiene el cliente (usuario) que creó el envío
     */
    public function cliente()
    {
        return $this->belongsTo(Usuario::class, 'cliente_id');
    }

    /**
     * Obtiene el transportista asignado
     */
    public function transportista()
    {
        return $this->belongsTo(Transportista::class, 'transportista_id');
    }

    /**
     * Obtiene el vehículo asignado
     */
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'vehiculo_id');
    }

    /**
     * Calcula el precio total del envío
     */
    public function getPrecioTotalAttribute()
    {
        // Si existen items, sumar total de cada item (precioUnidad * unidades)
        if (is_array($this->items) && !empty($this->items)) {
            $sum = 0.0;
            foreach ($this->items as $it) {
                $precioUnidad = isset($it['precio_producto']) ? (float) $it['precio_producto'] : 0.0;
                $unidades = isset($it['unidades_totales']) ? (int) $it['unidades_totales'] : 0;
                $sum += $precioUnidad * $unidades;
            }
            return $sum;
        }
        // Fallback al esquema anterior
        return (float) $this->precio_producto * (int) $this->unidades_totales;
    }

    /**
     * Calcula peso total considerando items si existen.
     */
    public function getPesoTotalAttribute()
    {
        if (is_array($this->items) && !empty($this->items)) {
            $sum = 0.0;
            foreach ($this->items as $it) {
                $pesoUnidad = isset($it['peso_producto_unidad']) ? (float) $it['peso_producto_unidad'] : 0.0;
                $unidades = isset($it['unidades_totales']) ? (int) $it['unidades_totales'] : 0;
                $sum += $pesoUnidad * $unidades;
            }
            return $sum;
        }
        return (float) $this->peso_producto_unidad * (int) $this->unidades_totales;
    }

    /**
     * Calcula unidades totales considerando items si existen.
     */
    public function getUnidadesTotalizadasAttribute()
    {
        if (is_array($this->items) && !empty($this->items)) {
            $sum = 0;
            foreach ($this->items as $it) {
                $sum += isset($it['unidades_totales']) ? (int) $it['unidades_totales'] : 0;
            }
            return $sum;
        }
        return (int) $this->unidades_totales;
    }

    /**
     * Verifica si el envío está pendiente
     */
    public function isPendiente()
    {
        return $this->estado === self::ESTADO_PENDIENTE;
    }

    /**
     * Verifica si el envío está confirmado
     */
    public function isConfirmado()
    {
        return $this->estado === self::ESTADO_CONFIRMADO;
    }

    /**
     * Verifica si el envío está recibido
     */
    public function isRecibido()
    {
        return $this->estado === self::ESTADO_RECIBIDO;
    }
}

