<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'email' =>'sometimes|email|string',
            'password' => 'sometimes|string|min:6|max:20',
            'first_name' => 'sometimes|string|max:20',
            'last_name' => 'sometimes|string|max:40|nullable',
            'country' => 'sometimes|string|max:100',
            'city' => 'sometimes|string|max:100',
            'phone' =>'sometimes|numeric|',
            'role' => ['in:' . implode(',', $user_roles), 'nullable'],
        ];
    }
}
