<?php

namespace App\Livewire\Concerns;

trait WithConstants
{
    public static function subCategoryOptions(): array
    {
        return [
            'mild_ailments' => 'Mild Aliments',
            'palliative_care' => 'Palliative Care',
            'home_based_care' => 'Home Based Care Support',
            'age_related_care' => 'Age Related Care Support',
        ];
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
