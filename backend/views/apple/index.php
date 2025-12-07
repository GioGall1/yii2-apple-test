<?php

use backend\models\Apple;
use backend\models\AppleForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $apples Apple[] */
/* @var $form AppleForm */

$this->title = 'Яблоки';
?>

<div class="apple-index">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0"><?= Html::encode($this->title) ?></h1>
        <?= Html::a('Сгенерировать яблоки', ['generate'], [
            'class' => 'btn btn-success',
            'data-method' => 'post',
        ]) ?>
    </div>

    <?php foreach (Yii::$app->session->getAllFlashes() as $type => $message): ?>
        <div class="alert alert-<?= $type === 'error' ? 'danger' : 'success' ?>">
            <?= Html::encode($message) ?>
        </div>
    <?php endforeach; ?>

    <?php if (!$apples): ?>
        <p class="text-muted">Пока нет яблок. Нажмите «Сгенерировать яблоки», чтобы добавить их.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Статус</th>
                    <th>Цвет</th>
                    <th>Съедено</th>
                    <th>Появилось</th>
                    <th>Упало</th>
                    <th class="text-end">Действия</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($apples as $apple): ?>
                    <?php
                    $statusClass = [
                        Apple::STATUS_ON_TREE => 'secondary',
                        Apple::STATUS_ON_GROUND => 'info',
                        Apple::STATUS_ROTTEN => 'danger',
                    ][$apple->status] ?? 'secondary';
                    $availablePercent = $apple->remainingPercent();
                    ?>
                    <tr>
                        <td><?= Html::encode($apple->id) ?></td>
                        <td>
                            <span class="badge bg-<?= $statusClass ?>">
                                <?= Html::encode($apple->getStatusLabel()) ?>
                            </span>
                        </td>
                        <td><?= Html::encode($apple->color) ?></td>
                        <td>
                            <div class="progress" style="height: 18px;">
                                <div class="progress-bar" role="progressbar"
                                     style="width: <?= (int)$apple->eaten_percent ?>%;"
                                     aria-valuenow="<?= (int)$apple->eaten_percent ?>" aria-valuemin="0" aria-valuemax="100">
                                    <?= (int)$apple->eaten_percent ?>%
                                </div>
                            </div>
                        </td>
                        <td><?= Yii::$app->formatter->asDatetime($apple->appeared_at) ?></td>
                        <td><?= $apple->fell_at ? Yii::$app->formatter->asDatetime($apple->fell_at) : '—' ?></td>
                        <td class="text-end">
                            <div class="d-flex gap-2 justify-content-end">
                                <?php if ($apple->isOnTree()): ?>
                                    <?= Html::a('Уронить', ['fall', 'id' => $apple->id], [
                                        'class' => 'btn btn-warning btn-sm',
                                        'data-method' => 'post',
                                    ]) ?>
                                <?php endif; ?>

                                <?php if (!$apple->isOnTree() && !$apple->isRottenStatus()): ?>
                                    <?php $rowForm = new AppleForm(); ?>
                                    <?php $activeForm = ActiveForm::begin([
                                        'action' => ['eat', 'id' => $apple->id],
                                        'method' => 'post',
                                        'options' => ['class' => 'd-flex align-items-center gap-2'],
                                        'fieldConfig' => [
                                            'template' => '{input}',
                                            'options' => ['class' => 'mb-0'],
                                        ],
                                    ]); ?>
                                    <?= $activeForm->field($rowForm, 'percent')->input('number', [
                                        'min' => 1,
                                        'max' => $availablePercent,
                                        'value' => min(25, $availablePercent),
                                        'class' => 'form-control form-control-sm',
                                        'style' => 'width:90px',
                                        'aria-label' => 'Процент',
                                    ]) ?>
                                    <?= Html::submitButton('Съесть', ['class' => 'btn btn-primary btn-sm']) ?>
                                    <?php ActiveForm::end(); ?>
                                <?php else: ?>
                                    <span class="text-muted small">Есть нельзя</span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
