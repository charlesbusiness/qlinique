<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasStaffId
{
    public static function bootHasStaffId(): void
    {
        static::creating(function ($model) {
            if (empty($model->staff_id)) {
                $model->staff_id = static::generateStaffId();
            }
        });
    }

    public static function generateStaffId(): string
    {
        $last = static::where('staff_id', 'like', 'STAFF-%')
            ->orderByRaw('LENGTH(staff_id) DESC, staff_id DESC')
            ->value('staff_id');

        if ($last) {
            $sequence = (int) Str::afterLast($last, '-') + 1;
        } else {
            $sequence = 1;
        }

        return sprintf('STAFF-%04d', $sequence);
    }
}
