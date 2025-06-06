<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //validate if the user is admin

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role' => 'required|in:admin,user',
            'name' => 'required|string|max:255',
            'uuid' => 'required|string|unique:users,uuid',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'isActive' => 'nullable|boolean',
        ];
    }

    public function messages()
    {
        return [
            'image.image' => 'La imagen debe ser un archivo de imagen válido.',
            'image.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif.',
            'image.max' => 'La imagen no debe exceder los 2MB.',
            'role.required' => 'El rol es obligatorio.',
            'role.in' => 'El rol debe ser uno de los siguientes: admin, user.',
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no debe exceder los 255 caracteres.',
            'uuid.required' => 'El UUID es obligatorio.',
            'uuid.string' => 'El UUID debe ser una cadena de texto.',
            'uuid.unique' => 'El UUID ya está en uso.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección de correo válida.',
            'email.unique' => 'El correo electrónico ya está en uso.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.string' => 'La contraseña debe ser una cadena de texto.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'password_confirmation.required' => 'La confirmación de la contraseña es obligatoria.',
            'password_confirmation.string' => 'La confirmación de la contraseña debe ser una cadena de texto.',
            'password_confirmation.min' => 'La confirmación de la contraseña debe tener al menos 6 caracteres.',
        ];
    }
}
