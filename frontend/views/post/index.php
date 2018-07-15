<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <ol class="breadcrumb">
                <li><a href="<?=Yii::$app->homeUrl?>">首页</a></li>
                <li>文章列表</li>
            </ol>
            <?=\yii\widgets\ListView::widget([
                'id'=>'postList',
                'dataProvider'=>$dataProvider,
                'itemView'=>'_listview', //子视图，显示一篇文章的标题等内容
                'layout'=>'{items} {pager}',
                'pager'=>[
                        'maxButtonCount'=>10,
                        'nextPageLabel'=>Yii::t('yii','下一页'),
                        'prevPageLabel'=>Yii::t('yii','上一页'),
                ],
            ])?>
        </div>
        <div class="col-md-3">
            <div class="searchbox">
                <ul class="list-group">
                    <li class="list-group-item">
                        <span class="glyphicon glyphicon-search"></span>查找文章
                    </li>
                    <?php

                        $count = Yii::$app->cache->get('postCount');
                        $dep = new \yii\caching\DbDependency(['sql'=>'select count(id) from post']);//缓存依赖
                        if(!$count){
                            $count = \common\models\Post::find()->count();
                            Yii::$app->cache->set('postCount',$count,60,$dep);//六十秒后过期
                        }

                    ?>
<!--                    (--><?//=Html::encode(\common\models\Post::find()->count())?><!--)-->
                    (<?=Html::encode($count);?>)
                    <li class="list-group-item">
                        <form class="form-inline" action="index.php?r=post/index" method="get">
                            <div class="form-group">
                                <input type="text" class="form-control" id="w0input" placeholder="按标题搜索" name="PostSearch[title]">
                            </div>
                            <button type="submit" class="btn btn-default">搜索</button>
                        </form>
                    </li>
                </ul>
            </div>
            <div class="tagcloudbox">
                <ul class="list-group">
                    <li class="list-group-item">
                        <span class="glyphicon glyphicon-tags"></span>标签云
                    </li>

                    <li class="list-group-item">
<!--                        --><?//=\frontend\components\TagCloudWidget::widget(['tags'=>$tags]);?>
                        <?php
                        //使用片段缓存
                            $dependency = new  \yii\caching\DbDependency(['sql'=>'select count(id) from post']);
                            if($this->beginCache('cache',['duration'=>600],['dependency'=>$dependency])){
                                echo \frontend\components\TagCloudWidget::widget(['tags'=>$tags]);
                                $this->endCache();
                            }
                        ?>
                    </li>
                </ul>
            </div>
            <div class="searchbox">
                <ul class="list-group">
                    <li class="list-group-item">
                        <span class="glyphicon glyphicon-comment"></span>最新回复
                    </li>
                    <li class="list-group-item">搜索框</li>
                </ul>
            </div>
        </div>
    </div>
</div>


