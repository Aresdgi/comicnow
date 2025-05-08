<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Biblioteca extends Model
{
    use HasFactory;

    protected $table = 'bibliotecas';
    
    // Esta tabla usa una clave primaria compuesta
    public $incrementing = false;
    protected $primaryKey = ['id_usuario', 'id_comic'];
    
    protected $fillable = [
        'id_usuario',
        'id_comic',
        'progreso_lectura',
        'ultimo_marcador'
    ];

    // Relación con usuario (una entrada de biblioteca pertenece a un usuario)
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    // Relación con comic (una entrada de biblioteca está asociada a un comic)
    public function comic()
    {
        return $this->belongsTo(Comic::class, 'id_comic');
    }
}