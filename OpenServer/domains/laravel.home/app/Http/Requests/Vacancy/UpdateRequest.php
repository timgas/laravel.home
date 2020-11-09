<?php

namespace App\Http\Requests\Vacancy;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = auth()->user()->role;
        return [
            'vacancy_name' => 'required|string|min:4',
            'workers_amount' =>'required|numeric|min:1',
            'organization_id' => "exclude_if: auth()->user()->role,true|required|numeric|min:1",
            'salary' => 'required|numeric'
        ];
    }

}
