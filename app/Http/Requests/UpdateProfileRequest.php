<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateProfileRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "slug" => "min:2|max:100|unique:users,slug," . $this->user()->id,
            "display_name" => "required|max:200|min:2",
            "email" => "required|email|unique:users,email," . $this->user()->id,
            "description" => "max:350",
            "password" => "confirmed|min:6"
        ];
    }

    /**
     * Custom attributes
     *
     * @return array
     */
    public function attributes()
    {
        return [
            "slug" => trans('setting/account.slug'),
            "display_name" => trans('setting/account.display_name'),
            "description" => trans('setting/account.description'),
            "email" => "Email",
            "password" => trans('auth.input.password')
        ];
    }
}
