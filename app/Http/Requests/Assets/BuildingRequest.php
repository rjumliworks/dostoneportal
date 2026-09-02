<?php

namespace App\Http\Requests\Assets;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BuildingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('asset_buildings', 'name')->ignore($this->route('building'))],
            'address' => ['required', 'string', 'max:255'],
            'longitude' => ['nullable', 'numeric'],
            'latitude' => ['nullable', 'numeric'],
            'region_code' => ['nullable', 'string', 'exists:location_regions,code'],
            'province_code' => ['nullable', 'string', 'exists:location_provinces,code'],
            'municipality_code' => ['nullable', 'string', 'exists:location_municipalities,code'],
            'barangay_code' => ['nullable', 'string', 'exists:location_barangays,code'],
            'station_id' => ['required', 'integer', 'exists:list_dropdowns,id'],
        ];
    }
}
