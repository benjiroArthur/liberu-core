<?php

namespace LaravelEnso\Core\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ValidateProfilePageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $user = $this->route('user');

        return [
            'first_name' => 'required|max:50',
            'last_name'  => 'required|max:50',
            'phone'      => 'max:30',
        ];
    }
}
