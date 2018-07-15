<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Comments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            //'content:ntext',
            [
                'attribute'=>'content',
//下面三种写法都可以
//                'value'=>'beginning'
                  'value'=>function($model){
                        return $model->beginning;
                  }
//                'value' => function($model){
//                    $tmp = strip_tags($model->content);
//                    $len = mb_strlen($tmp);
//                    return mb_substr($tmp,0,20,'utf-8').(($len > 20) ? '...' : '');
//                }
            ],
            [
                'attribute'=>'status',
                'value' => 'status0.name',
                'filter' => \common\models\Poststatus::find()->select('name,id')->indexBy('id')->orderBy('position')->column(),
            ],
            //'create_time:datetime',
            [
                'attribute'=>'create_time',
                'format' => ['date','php:Y-m-d H:i:s'],
            ],
            //'userid',
            [
                'attribute'=>'user.username',
                'label'=>'作者',
                'value' => 'user.username',
            ],
            //'email:email',
            //'url:url',
            'post.title',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {approve}',
                'buttons' => [
                        'approve' => function($url,$model,$key){
                            $options = [
                                'title' => Yii::t('yii','审核'),
                                'aria-label'=>Yii::t('yii','审核'),
                                'data-confirm'=>Yii::t('yii','你确定通过审核吗？'),
                                'data-method'=>'post',
                                'data-pjax'=>'0',
                            ];
                            return Html::a('<span class="glyphicon glyphicon-check"></span>',$url,$options);
                        }
                ],
            ],
        ],
    ]); ?>
</div>
