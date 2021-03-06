<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Post */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '文章列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'content:ntext',
            'tags:ntext',
            //'status',
            [
                'label' => '状态',
                'value' => function($model){
                    return $model->status0->name;
                }
            ],
            //'create_time:datetime',
            [
                'attribute' => 'create_time',
                'value' => date('Y-m-d H:i:s',$model->create_time),
            ],
            //'update_time:datetime',
            [
                'attribute' => 'update_time',
                'value' => date('Y-m-d H:i:s',$model->update_time),
            ],
            //'author_id',
            [
                //'label' => '作者',
                'attribute' => 'author_id',
                'value' => function($model){
                    return $model->author->nickname;
                }
            ],
        ],
    ]) ?>

</div>
