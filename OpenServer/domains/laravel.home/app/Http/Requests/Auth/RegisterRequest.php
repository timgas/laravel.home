<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */


    public function rules()
    {
        $user_roles = ['worker', 'employer'];
        return [
            'role' => ['in:' . implode(',', $user_roles), 'nullable'],
            'email'=>'required|email|unique:users',
            'password' => 'required|string|min:6|max:20',
            'first_name' => 'sometimes|string|max:20',
            'last_name' => 'sometimes|string|max:40|nullable',
            'country' => 'sometimes|string|max:100',
            'city' => 'sometimes|string|max:100',
            'phone' => 'sometimes|numeric|',
        ];
    }
}
