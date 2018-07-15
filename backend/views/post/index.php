<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '文章列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Post', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute' => 'id',
                'contentOptions' => ['width'=>'30px'],
            ],
            'title',
            //'content:ntext',
            'tags:ntext',
            //'status',
            [
                'attribute' => 'status',
                'value' => 'status0.name',
                'contentOptions'=>function($model){
                    return $model->status !== 1 ? ['class'=>'bg-danger'] : [];
                }
            ],
            //'create_time:datetime',
            [
                'attribute' => 'create_time',
                'format' => ['date','php:Y-m-d H:i:s'],
            ],
            //'update_time:datetime',
            //'author_id',
            [
                'attribute' => 'author_id',
                'value' => 'author.nickname',
            ],
//或者下面这种形式也可以查出作者昵称
//            [
//                'attribute' => 'author_id',
//                'value' => function($searchModel){
//                    return $searchModel->author->nickname;
//                },
//            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
