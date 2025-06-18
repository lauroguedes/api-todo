<?php

namespace App\Enums;

enum TaskPriority: int
{
    case LOW = 1;
    case MEDIUM = 2;
    case HIGH = 3;

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
            self::LOW => 'Low',
            self::HIGH => 'High',
            self::MEDIUM => 'Medium',
            default => throw new \Exception('Unexpected match value'),
        };
    }
}
