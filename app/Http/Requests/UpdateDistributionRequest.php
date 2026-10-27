<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDistributionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-master-data');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'school_id' => ['required', 'exists:schools,id'],
            'tanggal' => [
                'required',
                'date',
                Rule::unique('distributions')
                    ->where(fn ($query) => $query->where('school_id', $this->input('school_id')))
                    ->ignore($this->route('distribution')),
            ],
            'menu' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'tanggal.unique' => 'Distribusi untuk sekolah ini pada tanggal tersebut sudah ada.',
        ];
    }
}
