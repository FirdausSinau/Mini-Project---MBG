<?php

namespace App\Http\Requests;

use App\Models\Distribution;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('report'));
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
            'distribution_id' => [
                'required',
                Rule::exists('distributions', 'id')
                    ->where(fn ($query) => $query->where('school_id', $this->input('school_id'))),
            ],
            'incident_at' => ['required', 'date', 'before_or_equal:now'],
            'description' => ['required', 'string', 'min:20', 'max:2000'],
            'location_detail' => ['nullable', 'string', 'max:150'],
            'evidence' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
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
