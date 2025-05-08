<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comic extends Model
{
    use HasFactory;

    protected $table = 'comics';
    protected $primaryKey = 'id_comic';
    
    protected $fillable = [
        'titulo',
        'descripcion',
        'portada_url',
        'archivo_comic',
        'precio',
        'stock',
        'id_autor'
    ];

    // Relación con autor (un comic pertenece a un autor)
    public function autor()
    {
        return $this->belongsTo(Autor::class, 'id_autor');
    }

    // Relación con detalles de pedido (un comic puede estar en muchos detalles de pedido)
    public function detallesPedido()
    {
        return $this->hasMany(DetallePedido::class, 'id_comic');
    }

    // Relación con reseñas (un comic puede tener muchas reseñas)
    public function resenas()
    {
        return $this->hasMany(Resena::class, 'id_comic');
    }

    // Relación con bibliotecas (un comic puede estar en muchas bibliotecas de usuarios)
    public function bibliotecas()
    {
        return $this->hasMany(Biblioteca::class, 'id_comic');
    }
}