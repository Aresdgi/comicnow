<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    /**
     * Muestra una lista de todos los usuarios.
     */
    public function index()
    {
        $usuarios = Usuario::orderBy('created_at', 'desc')->paginate(10);
        return view('usuarios.index', compact('usuarios'));
    }
}