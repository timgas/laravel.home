<?php

namespace App\Http\Requests\Vacancy;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'vacancy_name' => 'required|string|min:4',
            'workers_amount' =>'required|numeric|min:1',
            'organization_id' => 'required|numeric|min:1',
            'salary' => 'required|numeric'
        ];
    }
}
