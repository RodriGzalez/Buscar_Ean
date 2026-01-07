<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => [
            'required', 
            'string', 
            'lowercase', 
            'email', 
            'max:255', 
            'unique:'.User::class,
            // Agregamos esta línea para validar el dominio
            'regex:/^[a-zA-Z0-9._%+-]+@chedraui\.com\.mx$/i'
        ],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ], [
        // Mensaje de error personalizado en español
        'email.regex' => 'Solo se permiten registros con correos de @chedraui.com.mx',
    ]);

    // ... resto del código para crear el usuario
}
}