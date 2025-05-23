<?php

namespace App\Actions\Fortify;

use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): Usuario
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:usuarios'],
            'direccion' => ['required', 'string', 'max:500'],
            'preferencias' => ['array'],
            'preferencias.*' => ['string', 'in:accion,aventura,comedia,drama,fantasia,ciencia-ficcion,romance,terror,superheros,manga'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return Usuario::create([
            'nombre' => $input['name'],
            'email' => $input['email'],
            'contraseña' => Hash::make($input['password']),
            'direccion' => $input['direccion'],
            'preferencias' => json_encode($input['preferencias'] ?? []),
            'rol' => 'cliente', // Por defecto, los nuevos usuarios son clientes
        ]);
    }
}
