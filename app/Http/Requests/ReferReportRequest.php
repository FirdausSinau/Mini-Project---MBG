<?php

namespace App\Http\Requests;

use App\Enums\ReferralParty;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReferReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('refer', $this->route('report'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'party_type' => ['required', Rule::enum(ReferralParty::class)],
            'party_name' => ['required', 'string', 'max:150'],
            'note' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
