<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'tags')->textarea(['rows' => 6]) ?>

<!--    --><?//= $form->field($model, 'status')->textInput() ?>
    <?php
        //find()
        $psModel = \common\models\Poststatus::find()->all();
        $allStatus = \yii\helpers\ArrayHelper::map($psModel,'id','name');
        //print_r($allStatus);
    ?>
    <?= $form->field($model, 'status')->dropDownList($allStatus,['prompt'=>'请选择']) ?>

<!--    --><?//= $form->field($model, 'create_time')->textInput() ?>

<!--    --><?//= $form->field($model, 'update_time')->textInput() ?>

<!--    --><?//= $form->field($model, 'author_id')->textInput() ?>
    <?= $form->field($model, 'author_id')->dropDownList(\common\models\Adminuser::find()->select('nickname,id')->indexBy('id')->column(),['prompt'=>'请选择']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
