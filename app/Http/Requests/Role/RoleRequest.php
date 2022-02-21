<?php

namespace App\Http\Requests\Role;

use Urameshibr\Requests\FormRequest;

class RoleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'description'=>'required',
            'roleTypeId' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'description.required' => 'Description is required',
            'roleTypeId.required' => 'Role Type Id is required',
        ];
    }
}
