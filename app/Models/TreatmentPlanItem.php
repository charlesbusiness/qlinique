<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TreatmentPlanItem extends Model
{
    protected $fillable = [
        'treatment_chart_id',
        'route_category',
        'route_form',
        'drug_name',
        'strength',
        'dosage',
        'regime',
        'length_value',
        'length_unit',
        'length_display',
        'amount',
        'is_take_home',
    ];

    protected function casts(): array
    {
        return [
            'is_take_home' => 'boolean',
        ];
    }

    public function treatmentChart()
    {
        return $this->belongsTo(TreatmentChart::class);
    }
}
