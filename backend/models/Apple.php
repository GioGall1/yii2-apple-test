<?php

namespace backend\models;

use yii\db\ActiveRecord;
use backend\enums\AppleStatus;
use backend\enums\AppleColor;

class Apple extends ActiveRecord
{

    private const ROTTEN_TIMEOUT = 5 * 3600;

    public static function tableName()
    {
        return '{{%apple}}';
    }

    public function rules()
    {
        return [
            [['color', 'appeared_at'], 'required'],
            [['appeared_at', 'fell_at', 'status', 'eaten_percent'], 'integer'],
            [['color'], 'string', 'max' => 16],
        ];
    }

    public function setStatusEnum(AppleStatus $status): void
    {
        $this->status = $status->value;
    }

    public function getStatusEnum(): AppleStatus
    {
        return AppleStatus::from((int)$this->status);
    }

     public function getColorEnum(): AppleColor
    {
        return AppleColor::from($this->color);
    }

    public function setColorEnum(AppleColor $color): void
    {
        $this->color = $color->value;
    }


    public function fallFromTree(): void
    {
        $this->setStatusEnum(AppleStatus::ON_GROUND);
        $this->fell_at = time();
    }

    public function eatPart(int $percent): void
    {
        $this->eaten_percent += $percent;
    }

    public function remainingPercent(): int
    {
        return max(0, 100 - (int)$this->eaten_percent);
    }

    public function isOnTree(): bool
    {
        return $this->getStatusEnum() === AppleStatus::ON_TREE;
    }

    public function isOnGround(): bool
    {
        return $this->getStatusEnum() === AppleStatus::ON_GROUND;
    }

    public function markRottenIfExpired(): void
    {
        if ($this->fell_at === null) {
            return;
        }

        if ($this->getStatusEnum() === AppleStatus::ROTTEN) {
            return;
        }

        if ((time() - (int)$this->fell_at) >= self::ROTTEN_TIMEOUT) {
            $this->setStatusEnum(AppleStatus::ROTTEN);
        }
    }
    
    public function isRottenStatus(): bool
    {
        return $this->isRotten();
    }
    
    public function isRotten(): bool
    {
        if ($this->getStatusEnum() === AppleStatus::ROTTEN) {
            return true;
        }

            return $this->fell_at
            && (time() - $this->fell_at) >= self::ROTTEN_TIMEOUT;
    }

    public function getStatusLabel(): string
    {
        if ($this->isRotten()) {
            return 'Испорчено';
        }

        return $this->getStatusEnum()->label();
    }

}
