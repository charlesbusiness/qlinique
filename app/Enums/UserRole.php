<?php

namespace App\Enums;

enum UserRole: string
{
    case SuperAdmin = 'super_admin';
    case Matron = 'matron';
    case Doctor = 'doctor';
    case Nurse = 'nurse';
    case Receptionist = 'receptionist';
    case Accountant = 'accountant';
}
