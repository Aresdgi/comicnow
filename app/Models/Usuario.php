<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    
    protected $fillable = [
        'nombre',
        'email',
        'contraseña',
        'direccion',
        'preferencias',
        'rol'
    ];

    protected $hidden = [
        'contraseña',
    ];

    // Relación con pedidos (un usuario puede tener muchos pedidos)
    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'id_usuario');
    }

    // Relación con reseñas (un usuario puede escribir muchas reseñas)
    public function resenas()
    {
        return $this->hasMany(Resena::class, 'id_usuario');
    }

    // Relación con biblioteca (un usuario puede tener muchos comics en su biblioteca)
    public function biblioteca()
    {
        return $this->hasMany(Biblioteca::class, 'id_usuario');
    }
}