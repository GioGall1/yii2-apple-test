<?php

namespace backend\enums;

enum AppleColor: string
{
    case GREEN  = 'green';
    case RED    = 'red';
    case YELLOW = 'yellow';

    public function label(): string
    {
        return match ($this) {
            self::GREEN  => 'Зелёное',
            self::RED    => 'Красное',
            self::YELLOW => 'Жёлтое',
        };
    }

    public static function random(): self
    {
        $all = self::cases();
        return $all[array_rand($all)];
    }
}