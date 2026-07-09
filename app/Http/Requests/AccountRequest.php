<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // la vérification de propriété se fait dans le contrôleur
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:checking,savings,card,cash'],
            'balance' => ['required', 'numeric'],
        ];
    }
}