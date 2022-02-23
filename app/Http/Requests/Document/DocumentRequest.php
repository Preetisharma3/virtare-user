<?php

namespace App\Http\Requests\Document;

use Urameshibr\Requests\FormRequest;


class DocumentRequest extends FormRequest
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
            'document' => 'max:5120'
        ];
    }

    public function messages()
    {
        return [
            'document.size' => 'Document Must be less than 5MB'
        ];
    }
}
