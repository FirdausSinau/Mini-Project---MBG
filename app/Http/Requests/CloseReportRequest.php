<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CloseReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('close', $this->route('report'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'closure_note' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
