<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resena extends Model
{
    use HasFactory;

    protected $table = 'resenas';
    protected $primaryKey = 'id_resena';
    
    protected $fillable = [
        'id_usuario',
        'id_comic',
        'valoracion',
        'comentario',
        'fecha'
    ];

    // Relación con usuario (una reseña pertenece a un usuario)
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    // Relación con comic (una reseña está asociada a un comic)
    public function comic()
    {
        return $this->belongsTo(Comic::class, 'id_comic');
    }
}