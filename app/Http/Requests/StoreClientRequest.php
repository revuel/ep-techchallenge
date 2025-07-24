<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    public function authorize()
    {
        return true;  // all users can create clients
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:190'],
            'email' => ['nullable', 'email:rfc,dns'],
            'phone' => ['nullable', 'regex:/^[\d\s\+]+$/'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (empty($this->email) && empty($this->phone)) {
                $validator->errors()->add('email', 'At least one contact method (email or phone) is required.');
                $validator->errors()->add('phone', 'At least one contact method (email or phone) is required.');
            }
        });
    }
}
