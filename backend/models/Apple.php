<?php

namespace backend\models;

use yii\db\ActiveRecord;

class Apple extends ActiveRecord
{
    public const STATUS_ON_TREE = 0;
    public const STATUS_ON_GROUND = 1;
    public const STATUS_ROTTEN = 2;

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

    public function fallFromTree(): void
    {
        $this->status = self::STATUS_ON_GROUND;
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
        return $this->status === self::STATUS_ON_TREE;
    }

    public function isOnGround(): bool
    {
        return $this->status === self::STATUS_ON_GROUND;
    }

    public function isRottenStatus(): bool
    {
        return $this->isRotten();
    }

    public function markRottenIfExpired(): void
    {
        if ($this->status !== self::STATUS_ON_GROUND || !$this->fell_at) {
            return;
        }

        if ((time() - $this->fell_at) >= self::ROTTEN_TIMEOUT) {
            $this->status = self::STATUS_ROTTEN;
        }
    }

    public function isRotten(): bool
    {
        return $this->status === self::STATUS_ROTTEN
            || ($this->status === self::STATUS_ON_GROUND && $this->fell_at && (time() - $this->fell_at) >= self::ROTTEN_TIMEOUT);
    }

    public function getStatusLabel(): string
    {
        if ($this->isRotten()) {
            return 'Испорчено';
        }

        return [
            self::STATUS_ON_TREE => 'На дереве',
            self::STATUS_ON_GROUND => 'На земле',
            self::STATUS_ROTTEN => 'Испорчено',
        ][$this->status] ?? 'Неизвестно';
    }
}
