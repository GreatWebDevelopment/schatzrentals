<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreApplicantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'business_name' => ['nullable', 'string', 'max:255'],
            'service_area' => ['nullable', 'string', 'max:255'],

            'years_experience' => ['required', Rule::in(['lt1', '1-3', '3-5', '5plus'])],
            'has_turnover_experience' => ['required', 'boolean'],
            'experience_details' => ['nullable', 'string', 'max:5000'],
            'services' => ['nullable', 'array'],
            'services.*' => ['string', 'max:100'],

            'lead_time' => ['required', Rule::in(['same_day', '1_2_days', '3_5_days', '1_week_plus'])],
            'crew_size' => ['required', Rule::in(['solo', '2_3', '4_plus'])],
            'has_backup' => ['required', 'boolean'],
            'weekend_availability' => ['required', 'boolean'],

            'is_insured' => ['required', 'boolean'],
            'is_bonded' => ['required', 'boolean'],
            'provides_invoices' => ['required', 'boolean'],

            'price_1br' => ['nullable', 'numeric', 'min:0', 'max:99999'],
            'price_2br' => ['nullable', 'numeric', 'min:0', 'max:99999'],
            'price_3br' => ['nullable', 'numeric', 'min:0', 'max:99999'],
            'pricing_notes' => ['nullable', 'string', 'max:2000'],

            'reclean_guarantee' => ['required', 'boolean'],
            'sends_photos' => ['required', 'boolean'],

            'references' => ['nullable', 'array', 'max:3'],
            'references.*.name' => ['nullable', 'string', 'max:255'],
            'references.*.phone' => ['nullable', 'string', 'max:30'],
            'references.*.relationship' => ['nullable', 'string', 'max:255'],

            'additional_notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
