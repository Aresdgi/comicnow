<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    use HasFactory;

    protected $table = 'detalle_pedidos';
    protected $primaryKey = 'id_detalle';
    
    protected $fillable = [
        'id_pedido',
        'id_comic',
        'precio',
        'cantidad'
    ];

    // Relación con pedido (un detalle pertenece a un pedido)
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }

    // Relación con comic (un detalle de pedido está asociado a un comic)
    public function comic()
    {
        return $this->belongsTo(Comic::class, 'id_comic');
    }
}