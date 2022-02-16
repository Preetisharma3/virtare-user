<?php

namespace App\Http\Requests\Contact;

use Urameshibr\Requests\FormRequest;


class ContactRequest extends FormRequest
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
            'contactTiming'=> 'required',
        ];
    }

    public function messages()
    {
        return [
            'contactTiming.required' => 'Contact Time  must be required',
        ];
    }
}
