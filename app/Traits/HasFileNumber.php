<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasFileNumber
{
    public static function bootHasFileNumber(): void
    {
        static::creating(function ($model) {
            if (empty($model->file_number)) {
                $model->file_number = static::generateFileNumber();
            }
        });
    }

    public static function generateFileNumber(): string
    {
        $prefix = 'FAC';
        $year = now()->year;
        $last = static::whereYear('created_at', $year)->max('file_number');

        if ($last) {
            $sequence = (int) Str::afterLast($last, '-') + 1;
        } else {
            $sequence = 1;
        }

        return sprintf('%s-%s-%05d', $prefix, $year, $sequence);
    }
}
