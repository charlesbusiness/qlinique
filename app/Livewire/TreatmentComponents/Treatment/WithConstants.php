<?php

namespace App\Livewire\TreatmentComponents\Treatment;

trait WithConstants
{
    public static function assessmentCategories(): array
    {
        return [
            'checkup' => 'Check-up',
            'treatment' => 'Treatment',
            'maternal_health' => 'Maternal Health Care',
            'enrollment_palliative' => 'Enrollment & Palliative Care',
            'emergency_accident' => 'Emergency & Accident Management',
            'consultancy' => 'Consultancy / Counseling / Educating',
        ];
    }

    public static function implementedCategories(): array
    {
        return [
            'treatment' => 'Treatment',
            'maternal_health' => 'Maternal Health Care',
        ];
    }

    public static function assessmentSubOptions(string $category): array
    {
        return match ($category) {
            'checkup' => [
                'periodic_health_evaluation' => 'Periodic Health Evaluation',
                'annual_physical' => 'Annual Physical / Medical Examination',
                'executive_screening' => 'Executive Health Screening',
                'comprehensive_health' => 'Comprehensive Health Evaluation',
                'infectious_disease' => 'Infectious Disease Screening',
                'genetic_infectious' => 'Genetics & Infectious Screening',
                'pre_employment' => 'Pre-employment / Admission Medical Screening',
                'standard_pre_partner' => 'Standard Pre-partner Clinical Panel',
                'well_child' => 'Well Child Visit / Well Baby Assessment',
            ],
            'treatment' => [
                'mild_ailments' => 'Mild Ailments',
                'palliative_care' => 'Palliative Care',
                'home_based_care' => 'Home Based Care Support',
                'age_related_care' => 'Age Related Care Support',
            ],
            'maternal_health' => [
                'antenatal_care' => 'Antenatal Care',
                'labour_delivery' => 'Labour & Delivery',
                'postnatal_care' => 'Postnatal Disorder Care (Postpartum Care)',
                'infertility' => 'Infertility',
                'pre_menopause' => 'Pre-menopause',
            ],
            'enrollment_palliative' => [
                'hypertension' => 'Hypertension Management',
                'diabetes' => 'Diabetes Management',
                'hypertension_diabetes' => 'Hypertension & Diabetes Management',
                'diabetes_wound' => 'Diabetes & Wound Care',
            ],
            'emergency_accident' => [],
            'consultancy' => [],
            default => [],
        };
    }

    public static function routeCategories(): array
    {
        return [
            'oral' => 'Oral',
            'parenteral' => 'Parenteral (Injection)',
            'buccal' => 'Buccal (Lozenges)',
            'sublingual' => 'Sublingual',
            'inhalation' => 'Inhalation',
            'topical' => 'Topical',
            'rectal' => 'Rectal',
            'vaginal' => 'Vaginal',
            'drop' => 'Drop',
        ];
    }

    public static function routeForms(?string $category): array
    {
        return match ($category) {
            'oral' => [
                'tablet' => 'Tablet', 'capsule' => 'Capsule', 'syrup' => 'Syrup',
                'powder' => 'Powder', 'mixture' => 'Mixture', 'emulsion' => 'Emulsion',
                'linctus' => 'Linctus', 'suspension' => 'Suspension', 'solution' => 'Solution', 'drop' => 'Drop',
            ],
            'parenteral' => [
                'iv' => 'I.V - Intravenous', 'im' => 'I.M - Intramuscular',
                'id' => 'I.D - Intradermal', 'it' => 'I.T - Intra Thecal',
                'subq' => 'SubQ - Subcutaneous', 'ip' => 'I.P - Intraperitonial',
            ],
            'buccal' => ['candied' => 'Candied', 'pastilles' => 'Pastilles (Gumes)', 'troches' => 'Troches', 'elixirs' => 'Elixirs (Sweet)'],
            'sublingual' => ['tablet' => 'Tablet', 'films' => 'Films', 'spray' => 'Spray', 'drops' => 'Drops'],
            'inhalation' => ['pmdi' => 'Pressurized Metered Dose Inhalers', 'dpi' => 'Dry Power Inhalers', 'smi' => 'Soft Mist Inhalers', 'mobilizers' => 'Mobilizers', 'low_flow' => 'Low Flow Devices'],
            'topical' => ['cream' => 'Cream', 'ointment' => 'Ointment', 'gel' => 'Gel', 'lotion' => 'Lotion', 'liniment' => 'Liniment', 'collodion' => 'Collodion', 'patches' => 'Patches', 'powder' => 'Powder'],
            'rectal' => ['cream' => 'Cream', 'enemas' => 'Enemas', 'suppository' => 'Suppository', 'ointment' => 'Ointment', 'pessary' => 'Pessary'],
            'vaginal' => ['cream' => 'Cream', 'suppository' => 'Suppository', 'ointment' => 'Ointment', 'pessary' => 'Pessary'],
            'drop' => ['drop' => 'Drop'],
            default => [],
        };
    }

    public static function strengthOptions(): array
    {
        return ['ml' => 'ML', 'cc' => 'CC', 'mg' => 'MG', 'g' => 'G'];
    }

    public static function regimeOptions(): array
    {
        return [
            'dly' => 'Daily (dly)',
            'bd' => 'bd (12hrs)',
            'tds' => 'tds (8hrs)',
            'qds' => 'qds (6hrs)',
            'nocte' => 'Nocte (night/bed)',
            'stat' => 'Stat (once)',
            'prn' => 'PRN (when needed)',
        ];
    }

    public static function eentConditions(): array
    {
        return [
            'inflammation' => 'Inflammation',
            'congestion' => 'Congestion',
            'structural_issues' => 'Structural issues',
            'foreign_body' => 'Foreign Body',
            'stock_discharge' => 'Stock Discharge',
            'redness' => 'Redness',
            'tenderness_pain' => 'Tenderness/Pain',
        ];
    }

    public static function rmeTestOptions(): array
    {
        return ['FBS', 'RBS', 'SPO2', 'P.T', 'Cholesterol', 'P.C.V', 'RDTA', 'XYZ', 'Other'];
    }
}
