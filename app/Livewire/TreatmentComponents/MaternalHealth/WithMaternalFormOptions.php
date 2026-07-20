<?php

namespace App\Livewire\TreatmentComponents\MaternalHealth;

trait WithMaternalFormOptions
{
    public static array $currentSymptomOptions = [
        'none' => 'None',
        'nausea_vomiting' => 'Nausea/Vomiting',
        'vaginal_bleeding' => 'Vaginal Bleeding/Spotting',
        'abdominal_pain' => 'Abdominal Pain/Cramping',
        'pelvic_discharge' => 'Pelvic Discharge',
    ];

    public static array $nextVisitDurationOptions = [
        '2_weeks' => '2 Weeks',
        '4_weeks' => '4 Weeks',
        '1_month' => '1 Month',
        '2_months' => '2 Months',
        '3_months' => '3 Months',
        '6_months' => '6 Months',
    ];

    public static array $chronicConditionOptions = [
        'hypertension' => 'Hypertension',
        'diabetes' => 'Diabetes (Type 1 or 2)',
        'asthma' => 'Asthma / Respiratory',
        'epilepsy' => 'Epilepsy / Seizures',
        'cardiac' => 'Cardiac Disease',
        'thromboembolism' => 'Thromboembolism/Clots',
        'mental_health' => 'Mental Health (Depression/Anxiety)',
        'thyroid' => 'Thyroid Disorder',
    ];

    public static array $infectiousDiseaseOptions = [
        'sti' => 'STI (Chlamydia/Gonorrhea)',
        'syphilis' => 'Syphilis',
        'hiv' => 'HIV',
        'hepatitis' => 'Hepatitis B/C',
        'tb' => 'Tuberculosis',
    ];

    public static array $familyGeneticOptions = [
        'genetic_errors' => 'Genetic / Inborn Errors',
        'heart_defects' => 'Congenital Heart Defects / Birth Defects',
        'family_history_conditions' => 'Family History',
    ];

    public static array $geneticErrorsOptions = [
        'inborn_errors' => 'Inborn Errors',
        'cystic_fibrosis' => 'Cystic Fibrosis',
        'sickle_cell_disease' => 'Sickle Cell Disease',
        'sickle_cell_trait' => 'Sickle Cell Trait',
        'thalassemia' => 'Thalassemia',
    ];

    public static array $heartDefectsOptions = [
        'heart_defects' => 'Heart Defects',
        'birth_defects' => 'Birth Defects',
    ];

    public static array $familyHistoryOptions = [
        'preeclampsia' => 'Preeclampsia',
        'gestational_diabetes' => 'Gestational Diabetes',
        'twins_multiples' => 'Twins/Multiples',
    ];

    public static array $dietIntakeOptions = [
        'general' => 'General Dietary Habit',
        'restriction' => 'Nutritional Restriction',
        'adequate' => 'Adequate Dietary Intake',
        'balanced' => 'Balance Dieting',
    ];

    public static array $physicalActivityOptions = [
        'sedentary' => 'Sedentary Habit',
        'frequent' => 'Frequent',
        'intensity' => 'Intensity',
    ];

    public static array $cardioRespOptions = [
        'lub_dub' => 'lub-dub',
        'murmur' => 'murmur/swishing',
        'arrhythmia' => 'arrhythmia/irregular',
        'crackles' => 'crackles/fluid sound',
    ];

    public static array $thyroidOptions = [
        'tenderness' => 'Tenderness',
        'masses' => 'Abnormal Masses',
        'pain' => 'Pain',
        'inflammation' => 'Inflammation',
        'ascites' => 'Ascites/Fluid Retain',
    ];

    public static array $breastOptions = [
        'structural_change' => 'Structural Change',
        'inverted_nipple' => 'Inverted Nipple',
        'masses' => 'Abnormal Masses',
        'pain' => 'Pain',
    ];

    public static array $extremitiesOptions = [
        'edema' => 'Edema (Swelling)',
        'dvt' => 'Deep Vein Thrombosis (DVT)',
        'risk_signs' => 'Risk Signs',
    ];

    public static array $routeCategories = [
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

    public static array $regimeOptions = [
        'dly' => 'Daily (dly)',
        'bd' => 'bd (12hrs)',
        'tds' => 'tds (8hrs)',
        'qds' => 'qds (6hrs)',
        'nocte' => 'Nocte (night/bed)',
        'stat' => 'Stat (once)',
        'prn' => 'PRN (when needed)',
    ];

    public static function routeForms(?string $category): array
    {
        return match ($category) {
            'oral' => ['tablet' => 'Tablet', 'capsule' => 'Capsule', 'syrup' => 'Syrup', 'powder' => 'Powder', 'mixture' => 'Mixture', 'emulsion' => 'Emulsion', 'linctus' => 'Linctus', 'suspension' => 'Suspension', 'solution' => 'Solution', 'drop' => 'Drop'],
            'parenteral' => ['iv' => 'I.V - Intravenous', 'im' => 'I.M - Intramuscular', 'id' => 'I.D - Intradermal', 'it' => 'I.T - Intra Thecal', 'subq' => 'SubQ - Subcutaneous', 'ip' => 'I.P - Intraperitonial'],
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

    public static function lengthUnitOptions(): array
    {
        return ['days' => 'Days', 'weeks' => 'Weeks', 'months' => 'Months'];
    }

    public static function ordinal(int $number): string
    {
        return match ($number) {
            1 => '1ST',
            2 => '2ND',
            3 => '3RD',
            default => $number.'TH',
        };
    }
}
