<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class RecapFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'school_id' => ['nullable', 'exists:schools,id'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ];
    }

    /**
     * Redirect to a clean recap page when the filter is invalid.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException(
            $validator,
            redirect()
                ->route('recap.index')
                ->withErrors($validator)
                ->withInput()
        );
    }
}
