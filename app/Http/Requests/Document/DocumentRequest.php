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
            'document' => 'required|max:2048',
            'name' => 'required',
            'type' => 'required',
            'tags' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'document.max' => 'Document Must be less than 2MB',
            'document.required' => 'Document is Required',
            'name.required' => 'Name is Required',
            'type.required' => 'Type is Required',
            'tags.required' => 'Tags is Required',
        ];
    }
}
