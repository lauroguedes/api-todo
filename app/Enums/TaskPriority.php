<?php

namespace App\Enums;

enum TaskPriority: int
{
    case HIGH = 1;
    case MEDIUM = 2;
    case LOW = 3;

    public static function fromLabel(string $label): ?self
    {
        return match (mb_strtolower($label)) {
            'low' => self::LOW,
            'medium' => self::MEDIUM,
            'high' => self::HIGH,
            default => null,
        };
    }

    /**
     * @throws \Exception
     */
    public function label(): string
    {
        return match ($this) {
            self::LOW => 'low',
            self::HIGH => 'high',
            self::MEDIUM => 'medium',
            default => throw new \Exception('Unexpected match value'),
        };
    }
}
