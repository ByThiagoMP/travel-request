<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTravelOrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // aqui pode limitar por roles/policies se quiser depois
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:2,3',
        ];
    }
}
