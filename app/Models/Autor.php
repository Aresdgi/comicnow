<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    use HasFactory;

    protected $table = 'autores';
    protected $primaryKey = 'id_autor';
    
    protected $fillable = [
        'nombre',
        'biografia',
        'editorial',
        'comision'
    ];

    // Relación con comics (un autor puede tener muchos comics)
    public function comics()
    {
        return $this->hasMany(Comic::class, 'id_autor');
    }
}