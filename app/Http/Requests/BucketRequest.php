<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CategoryDefinitionRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category' => 'required',
            'mime' => 'required',
            'max_size_MB' => 'required',
        ];
    }
}
