<?php

namespace App\Models;

use App\Traits\HasFileNumber;
use Illuminate\Database\Eloquent\Model;

class PatientFile extends Model
{
    use HasFileNumber;

    protected $fillable = [
        'file_number',
        'name',
        'email',
        'phone',
        'address',
        'type',
    ];

    public function patients()
    {
        return $this->hasMany(Patient::class, 'file_id');
    }
}
