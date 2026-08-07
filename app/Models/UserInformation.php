<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class UserInformation extends Model
{
    use LogsActivity;

    protected $fillable = ['accounts','contacts','backgrounds','personal','barangay_code','user_id'];

    protected $casts = [
        'accounts' => 'encrypted:json',
        'contacts' => 'encrypted:json',
        'backgrounds' => 'encrypted:json',
        'personal' => 'encrypted:json',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    /**
     * Blank-slate shape for accounts/backgrounds/contacts/personal. Shared by the
     * HR "new employee" seed path and the users:reset-information command so the
     * two don't drift out of sync with each other.
     */
    public static function defaultAttributes(): array
    {
        return [
            'accounts' => [
                ["name" => "Pag-Ibig", "number" => null, "deduction" => null, "is_contribution" => true],
                ["name" => "SSS", "number" => null, "deduction" => null, "is_contribution" => true],
                ["name" => "GSIS", "number" => null, "deduction" => null, "is_contribution" => true],
                ["name" => "PhilHealth", "number" => null, "deduction" => null, "is_contribution" => true],
                ["name" => "TIN", "number" => null, "deduction" => null, "is_contribution" => false],
                ["name" => "LandBank", "number" => null, "deduction" => null, "is_contribution" => false],
                // UMID and PhilSys aren't payroll deductions, but the Personal Data Sheet requires them.
                ["name" => "UMID", "number" => null, "deduction" => null, "is_contribution" => false],
                ["name" => "PhilSys", "number" => null, "deduction" => null, "is_contribution" => false],
            ],
            'backgrounds' => [
                "parents" => [
                    "father" => ["lastname" => null, "firstname" => null, "middlename" => null, "suffix" => null, "address" => null],
                    "mother" => ["lastname" => null, "firstname" => null, "middlename" => null, "suffix" => null, "address" => null],
                ],
                "spouse" => [
                    "lastname" => null, "firstname" => null, "middlename" => null, "suffix" => null,
                    "address" => null, "contact_no" => null, "occupation" => null, "company" => null,
                ],
                "children" => [],
            ],
            'contacts' => [
                "emergency_contact" => [
                    "name" => null, "relationship" => null, "contact_no" => null,
                    "address" => ["region" => null, "province" => null, "municipality" => null, "barangay" => null, "street" => null],
                ],
            ],
            'personal' => [
                "height" => null, "weight" => null, "citizenship" => null, "citizenship_type" => null,
                "citizenship_country" => null, "place_of_birth" => null, "agency_employee_no" => null,
            ],
        ];
    }

    public static function createDefaultFor(int $userId): self
    {
        return static::create(array_merge(static::defaultAttributes(), ['user_id' => $userId]));
    }

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
        ->logOnly(['accounts','contacts','backgrounds','personal'])
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName} the user information")
        ->useLogName('User Information')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }
}
