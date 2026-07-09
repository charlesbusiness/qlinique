<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RmeResult extends Model
{
    protected $fillable = [
        'treatment_chart_id',
        'test_name',
        'result',
        'amount',
        'comment',
    ];

    public function treatmentChart()
    {
        return $this->belongsTo(TreatmentChart::class);
    }
}
