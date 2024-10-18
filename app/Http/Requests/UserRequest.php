<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'email' => 'required|email|max:255|unique:users,email',
            'password'=> 'required|required_with:password_confirm|same:password_confirm',
            'password_confirm'=> 'required',
        ];

    }
}
