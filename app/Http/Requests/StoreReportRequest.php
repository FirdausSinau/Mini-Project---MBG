<?php

namespace App\Http\Requests;

use App\Models\Distribution;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Validator;

class StoreReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create-report');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'distribution_id' => ['required', 'exists:distributions,id'],
            'incident_at' => ['required', 'date', 'before_or_equal:now'],
            'description' => ['required', 'string', 'min:20', 'max:2000'],
            'location_detail' => ['nullable', 'string', 'max:150'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $distribution = Distribution::find($this->input('distribution_id'));

            if (
                $distribution
                && $this->filled('incident_at')
                && Carbon::parse($this->input('incident_at'))->lt($distribution->tanggal)
            ) {
                $validator->errors()->add(
                    'incident_at',
                    'Tanggal kejadian tidak boleh sebelum tanggal distribusi.'
                );
            }
        });
    }
}
