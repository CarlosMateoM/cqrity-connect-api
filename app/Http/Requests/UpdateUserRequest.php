<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'role'      => 'nullable|in:admin,user',
            'isActive'  => 'nullable|boolean',
            'name'      => 'nullable|string|max:255',
            'email'     => 'nullable|email|unique:users,email,' . $this->route('user'),
            'password'  => 'nullable|string|min:6|confirmed',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'uuid'      => 'nullable|string|unique:users,uuid,' . $this->route('user'),
        ];
    }

    public function messages()
    {
        return [
            'role.in' => 'El rol debe ser uno de los siguientes: admin, user.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no debe exceder los 255 caracteres.',
            'email.email' => 'El correo electrónico debe ser una dirección de correo válida.',
            'email.unique' => 'El correo electrónico ya está en uso.',
            'password.string' => 'La contraseña debe ser una cadena de texto.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'image.image' => 'La imagen debe ser un archivo de imagen válido.',
            'image.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif.',
            'image.max' => 'La imagen no debe exceder los 2MB.',
            'uuid.string' => 'El UUID debe ser una cadena de texto.',
            'uuid.unique' => 'El UUID ya está en uso.',
        ];
    }
}
