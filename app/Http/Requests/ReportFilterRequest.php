<?php

namespace App\Http\Requests;

use App\Enums\ReportStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ReportFilterRequest extends FormRequest
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
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::enum(ReportStatus::class)],
            'school_id' => ['nullable', 'exists:schools,id'],
        ];
    }

    /**
     * Redirect to a clean report index when the filter is invalid.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException(
            $validator,
            redirect()
                ->route('reports.index')
                ->withErrors($validator)
                ->withInput()
        );
    }
}
