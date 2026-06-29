<?php

namespace App\Enums;

enum AccountType: string
{
    case Individual = 'individual';
    case Family = 'family';
    case Corporate = 'corporate';
}
