<?php

namespace backend\repositories;

use backend\models\Apple;
use RuntimeException;
use yii\web\NotFoundHttpException;

class AppleRepository
{
    /**
     * @return Apple[]
     */
    public function findAll(): array
    {
        return Apple::find()
            ->orderBy(['id' => SORT_DESC])
            ->all();
    }

    public function get(int $id): Apple
    {
        $apple = Apple::findOne($id);

        if (!$apple) {
            throw new NotFoundHttpException("Яблоко с идентификатором {$id} не найдено");
        }

        return $apple;
    }

    public function save(Apple $apple): void
    {
        if (!$apple->save()) {
            $errors = $apple->getErrorSummary(true);
            $message = $errors ? implode('; ', $errors) : 'Ошибка при сохранении яблока';
            throw new RuntimeException($message);
        }
    }

    public function delete(Apple $apple): void
    {
        if ($apple->delete() === false) {
            throw new RuntimeException('Ошибка при удалении яблока');
        }
    }
}
