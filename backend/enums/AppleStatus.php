<?php

namespace backend\enums;

enum AppleStatus: int
{
    case ON_TREE   = 0;
    case ON_GROUND = 1;
    case ROTTEN    = 2;

    public function label(): string
    {
        return match ($this) {
            self::ON_TREE   => 'На дереве',
            self::ON_GROUND => 'На земле',
            self::ROTTEN    => 'Испорчено',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::ON_TREE   => 'secondary',
            self::ON_GROUND => 'info',
            self::ROTTEN    => 'danger',
        };
    }

    public function isRotten(): bool
    {
        return $this === self::ROTTEN;
    }
}