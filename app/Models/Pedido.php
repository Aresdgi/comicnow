<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';
    protected $primaryKey = 'id_pedido';
    
    protected $fillable = [
        'id_usuario',
        'fecha',
        'estado',
        'metodo_pago'
    ];

    // Relación con usuario (un pedido pertenece a un usuario)
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    // Relación con detalles del pedido (un pedido puede tener muchos detalles)
    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, 'id_pedido');
    }
}