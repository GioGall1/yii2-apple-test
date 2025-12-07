<?php

namespace backend\services;

use backend\models\Apple;

class AppleFactory
{
    private const COLORS = ['green', 'red', 'yellow'];

    public function create(): Apple
    {
        $apple = new Apple();
        $apple->color = self::COLORS[array_rand(self::COLORS)];
        $apple->appeared_at = time() - rand(1000, 100000);
        $apple->fell_at = null;
        $apple->status = Apple::STATUS_ON_TREE;
        $apple->eaten_percent = 0;

        return $apple;
    }
}
