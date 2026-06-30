<?php

namespace App\Enums;

enum TreatmentCategory: string
{
    case Checkup = 'checkup';
    case Treatment = 'treatment';
    case Emergency = 'emergency';
    case Antenatal = 'antenatal';
    case Consultancy = 'consultancy';
    case EnrollmentTreatmentManagement = 'enrollment_treatment_management';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Checkup => 'Checkup',
            self::Treatment => 'Treatment',
            self::Emergency => 'Emergency / Accident',
            self::Antenatal => 'Antenatal',
            self::Consultancy => 'Consultancy / Counselling',
            self::EnrollmentTreatmentManagement => 'Enrollment and treatment Management',
            self::Other => 'Other',
        };
    }

    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }
        return $options;
    }
}
