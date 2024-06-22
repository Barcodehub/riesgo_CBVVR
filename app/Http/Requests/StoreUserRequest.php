<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'nombre' => 'required|string|max:255',
            'apellido' => 'nullable|string|max:255',
            'documento' => 'required|unique:users,documento|max:15',
            'telefono' => 'required|max:12',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8',
            'rol_id' => 'required|exists:roles,id',
            'disponibilidad' => 'string',
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'documento.required' => 'El documento es obligatorio.',
            'documento.unique' => 'Este documento ya está registrado.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección de correo válida.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña minimo de 8 caracteres.',
            'rol_id.required' => 'El rol es obligatorio.',
            'rol_id.exists' => 'El rol seleccionado no es válido.',
        ];
    }
}
