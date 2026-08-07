<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class PdsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        switch ($this->option) {
            case 'academic':
                return [
                    'school_id' => 'required',
                    'course_id' => 'required',
                    'level_id' => 'required',
                    'is_ongoing' => 'required|boolean',
                    'attended_from' => 'nullable|digits:4',
                    'attended_to' => 'nullable|digits:4',
                    'graduated_at' => 'nullable|digits:4|required_if:is_ongoing,0',
                    'units_earned' => 'nullable|string|max:150',
                    'honors' => 'nullable|string|max:255',
                ];
            case 'eligibility':
                return [
                    'exam_name' => 'required|string|max:255',
                    'rating' => 'nullable|string|max:20',
                    'exam_at' => 'nullable|date',
                    'exam_place' => 'nullable|string|max:255',
                    'license_number' => 'nullable|string|max:100',
                    'license_valid_until' => 'nullable|date',
                ];
            case 'work_experience':
                return [
                    'start_at' => 'required|date',
                    'end_at' => 'nullable|date|after_or_equal:start_at',
                    'position_title' => 'required|string|max:255',
                    'department_agency' => 'required|string|max:255',
                    'monthly_salary' => 'nullable|numeric',
                    'salary_grade' => 'nullable|string|max:20',
                    'appointment_status' => 'nullable|string|max:100',
                    'is_government' => 'required|boolean',
                ];
            case 'voluntary_work':
                return [
                    'organization' => 'required|string|max:255',
                    'start_at' => 'required|date',
                    'end_at' => 'nullable|date|after_or_equal:start_at',
                    'hours' => 'nullable|integer|min:0',
                    'position_nature' => 'nullable|string|max:255',
                ];
            case 'training':
                return [
                    'title' => 'required|string|max:255',
                    'start_at' => 'required|date',
                    'end_at' => 'nullable|date|after_or_equal:start_at',
                    'hours' => 'nullable|integer|min:0',
                    'type' => 'nullable|string|max:100',
                    'sponsored_by' => 'nullable|string|max:255',
                ];
            case 'other_information':
                return [
                    'type' => 'required|in:skill,distinction,organization',
                    'value' => 'required|string|max:255',
                ];
            case 'reference':
                return [
                    'name' => 'required|string|max:255',
                    'address' => 'nullable|string|max:255',
                    'contact' => 'nullable|string|max:255',
                ];
            case 'government_ids':
                return [
                    'accounts' => 'required|array',
                    'accounts.*.name' => 'required|string|max:100',
                    'accounts.*.number' => 'nullable|string|max:50',
                    'accounts.*.deduction' => 'nullable|numeric',
                    'accounts.*.is_contribution' => 'required|boolean',
                ];
            case 'family_background':
                return [
                    'parents.father.lastname' => 'nullable|string|max:100',
                    'parents.father.firstname' => 'nullable|string|max:100',
                    'parents.father.middlename' => 'nullable|string|max:100',
                    'parents.father.suffix' => 'nullable|string|max:20',
                    'parents.father.address' => 'nullable|string|max:255',
                    'parents.mother.lastname' => 'nullable|string|max:100',
                    'parents.mother.firstname' => 'nullable|string|max:100',
                    'parents.mother.middlename' => 'nullable|string|max:100',
                    'parents.mother.suffix' => 'nullable|string|max:20',
                    'parents.mother.address' => 'nullable|string|max:255',
                    'spouse.lastname' => 'nullable|string|max:100',
                    'spouse.firstname' => 'nullable|string|max:100',
                    'spouse.middlename' => 'nullable|string|max:100',
                    'spouse.suffix' => 'nullable|string|max:20',
                    'spouse.address' => 'nullable|string|max:255',
                    'spouse.contact_no' => 'nullable|string|max:20',
                    'spouse.occupation' => 'nullable|string|max:255',
                    'spouse.company' => 'nullable|string|max:255',
                    'children' => 'nullable|array',
                    'children.*.name' => 'required_with:children.*.birthdate|nullable|string|max:255',
                    'children.*.birthdate' => 'nullable|date',
                ];
            case 'declaration':
                return [
                    'related_third_degree' => 'nullable|boolean',
                    'related_third_degree_details' => 'nullable|string|max:255',
                    'related_fourth_degree' => 'nullable|boolean',
                    'related_fourth_degree_details' => 'nullable|string|max:255',
                    'admin_offense_found_guilty' => 'nullable|boolean',
                    'admin_offense_details' => 'nullable|string|max:255',
                    'criminally_charged' => 'nullable|boolean',
                    'criminal_charge_details' => 'nullable|string|max:255',
                    'criminal_charge_date_filed' => 'nullable|date',
                    'criminal_charge_case_status' => 'nullable|string|max:255',
                    'convicted_crime' => 'nullable|boolean',
                    'convicted_crime_details' => 'nullable|string|max:255',
                    'separated_from_service' => 'nullable|boolean',
                    'separated_from_service_details' => 'nullable|string|max:255',
                    'election_candidate' => 'nullable|boolean',
                    'election_candidate_details' => 'nullable|string|max:255',
                    'resigned_to_campaign' => 'nullable|boolean',
                    'resigned_to_campaign_details' => 'nullable|string|max:255',
                    'immigrant_status' => 'nullable|boolean',
                    'immigrant_status_country' => 'nullable|string|max:100',
                    'indigenous_group_member' => 'nullable|boolean',
                    'indigenous_group_details' => 'nullable|string|max:255',
                    'is_pwd' => 'nullable|boolean',
                    'pwd_id_number' => 'nullable|string|max:100',
                    'is_solo_parent' => 'nullable|boolean',
                    'solo_parent_id_number' => 'nullable|string|max:100',
                    'government_id_type' => 'nullable|string|max:100',
                    'government_id_number' => 'nullable|string|max:100',
                    'government_id_issued_at' => 'nullable|string|max:255',
                    'declared_at' => 'nullable|date',
                ];
            default:
                return [];
        }
    }
}
