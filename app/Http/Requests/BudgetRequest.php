<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class BudgetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = Auth::id();
        $budgetId = $this->route('budget')?->id;

        return [
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where('user_id', $userId),
            ],
            'amount_limit' => ['required', 'numeric', 'min:0.01'],
            'month' => [
                'required',
                'date_format:Y-m',
                Rule::unique('budgets', 'month')
                    ->where('user_id', $userId)
                    ->where('category_id', $this->input('category_id'))
                    ->ignore($budgetId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'month.unique' => 'Un budget existe déjà pour cette catégorie ce mois-ci.',
        ];
    }

    /**
     * Vérifie que la catégorie choisie est bien de type "dépense".
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $category = Category::find($this->input('category_id'));

            if ($category && $category->type !== 'expense') {
                $validator->errors()->add(
                    'category_id',
                    'Un budget ne peut être défini que sur une catégorie de dépense.'
                );
            }
        });
    }
}