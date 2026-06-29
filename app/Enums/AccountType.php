<?php

namespace App\Enums;

enum AccountType: string
{
    case Individual = 'individual';
    case Family = 'family';
    case Corporate = 'corporate';

    public function label(): string
    {
        return match ($this) {
            self::Individual => 'Individual',
            self::Family => 'Family',
            self::Corporate => 'Corporate',
        };
    }

    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }
        return $options;
    }
}
