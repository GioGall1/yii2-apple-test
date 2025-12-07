<?php

namespace backend\services;

use backend\models\Apple;
use backend\repositories\AppleRepository;
use backend\services\AppleFactory;
use RuntimeException;
use yii\web\BadRequestHttpException;

class AppleService
{
    private AppleRepository $repository;
    private AppleFactory $factory;

    public function __construct(AppleRepository $repository, AppleFactory $factory)
    {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    public function fall(int $appleId): void
    {
        $apple = $this->repository->get($appleId);

        if (!$apple->isOnTree()) {
            throw new BadRequestHttpException('Яблоко уже упало');
        }

        $apple->fallFromTree();
        $this->repository->save($apple);
    }

    public function eat(int $appleId, int $percent): void
    {
        if ($percent <= 0) {
            throw new BadRequestHttpException('Процент нужно указать больше 0');
        }

        $apple = $this->repository->get($appleId);
        $apple->markRottenIfExpired();

        if ($apple->isOnTree()) {
            throw new BadRequestHttpException('Яблоко нельзя съесть — оно висит на дереве');
        }

        if ($apple->isRotten()) {
            $this->repository->save($apple);
            throw new BadRequestHttpException('Яблоко испортилось — есть нельзя');
        }

        if ($percent > $apple->remainingPercent()) {
            throw new BadRequestHttpException('Нельзя съесть больше, чем осталось');
        }

        $apple->eatPart($percent);

        if ($apple->remainingPercent() === 0) {
            $this->repository->delete($apple);
            return;
        }

        $this->repository->save($apple);
    }

    public function generate(int $count): void
    {
        if ($count <= 0) {
            throw new RuntimeException('Количество должно быть больше нуля');
        }

        for ($i = 0; $i < $count; $i++) {
            $apple = $this->factory->create();
            $this->repository->save($apple);
        }
    }
}
