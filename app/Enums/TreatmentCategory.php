<?php

namespace App\Enums;

enum TreatmentCategory: string
{
    case Checkup = 'checkup';
    case Treatment = 'treatment';
    case Emergency = 'emergency';
}
