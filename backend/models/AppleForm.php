<?php

namespace backend\models;

use yii\base\Model;

class AppleForm extends Model
{
    public $percent;

    public function rules(): array
    {
        return [
            ['percent', 'required'],
            ['percent', 'integer', 'min' => 1, 'max' => 100],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'percent' => 'Съесть, %',
        ];
    }
}
