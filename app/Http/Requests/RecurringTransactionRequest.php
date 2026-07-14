<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class RecurringTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = Auth::id();

        return [
            'account_id' => [
                'required',
                Rule::exists('accounts', 'id')->where('user_id', $userId),
            ],
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where('user_id', $userId),
            ],
            'type' => ['required', 'in:expense,income'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'frequency' => ['required', 'in:daily,weekly,monthly,yearly'],
            'next_date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $category = Category::find($this->input('category_id'));

            if ($category && $category->type !== $this->input('type')) {
                $validator->errors()->add(
                    'category_id',
                    'Cette catégorie ne correspond pas au type sélectionné (dépense/revenu).'
                );
            }
        });
    }
}