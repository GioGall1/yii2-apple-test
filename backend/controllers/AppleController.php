<?php

namespace backend\controllers;

use backend\models\Apple;
use backend\models\AppleForm;
use backend\repositories\AppleRepository;
use backend\services\AppleService;
use backend\enums\AppleStatus;
use Throwable;
use Yii;
use yii\web\Controller;

class AppleController extends Controller
{
    private AppleService $service;
    private AppleRepository $repository;

    public function __construct($id, $module, AppleService $service, AppleRepository $repository, $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->service = $service;
        $this->repository = $repository;
    }

    public function actionIndex()
    {
        $apples = $this->repository->findAll();
        $form = new AppleForm();

        return $this->render('index', compact('apples', 'form'));
    }

    public function actionFall($id)
    {
        try {
            $this->service->fall((int)$id);
        } catch (Throwable $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
        }

        return $this->redirect(['index']);
    }

    public function actionEat($id)
    {
        $form = new AppleForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->eat((int)$id, $form->percent);
                Yii::$app->session->setFlash('success', 'Яблоко откусили');
            } catch (Throwable $exception) {
                Yii::$app->session->setFlash('error', $exception->getMessage());
            }
        } else {
            $errors = $form->getErrorSummary(true);
            Yii::$app->session->setFlash('error', $errors ? implode('; ', $errors) : 'Некорректные данные формы');
        }

        return $this->redirect(['index']);
    }

    public function actionGenerate()
    {
        try {
            $count = random_int(3, 8);
            $this->service->generate($count);
            Yii::$app->session->setFlash('success', "Создано {$count} яблок");
        } catch (Throwable $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
        }

        return $this->redirect(['index']);
    }

    public function actionSimulateRot($id)
    {
        $apple = $this->repository->get($id);

        if ($apple->fell_at === null) {
            Yii::$app->session->setFlash('error', 'Яблоко ещё не падало — симуляция невозможна.');
            return $this->redirect(['index']);
        }

        $apple->fell_at = $apple->fell_at - (5 * 3600);
        $apple->setStatusEnum(AppleStatus::ROTTEN);
        $this->repository->save($apple);

        Yii::$app->session->setFlash('success', 'Прошло 5 часов. Яблоко сгнило.');
        return $this->redirect(['index']);
    }
}
