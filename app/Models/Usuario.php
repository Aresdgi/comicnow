<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasProfilePhoto;

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
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->contraseña;
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function getUsernameAttribute()
    {
        return $this->nombre;
    }

    /**
     * Get the name attribute.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->nombre;
    }

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'contraseña' => 'hashed',
        ];
    }

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