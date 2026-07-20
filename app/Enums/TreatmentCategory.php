<?php

namespace App\Enums;

enum TreatmentCategory: string
{
    case Checkup = 'checkup';
    case Treatment = 'treatment';
    case MaternalHealth = 'maternal_health';
    case EnrollmentPalliative = 'enrollment_palliative';
    case EmergencyAccident = 'emergency_accident';
    case Consultancy = 'consultancy';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Checkup => 'Check-up',
            self::Treatment => 'Treatment',
            self::MaternalHealth => 'Maternal Health Care',
            self::EnrollmentPalliative => 'Enrollment & Palliative Care',
            self::EmergencyAccident => 'Emergency & Accident Management',
            self::Consultancy => 'Consultancy / Counseling / Educating',
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
