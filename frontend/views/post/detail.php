<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\HtmlPurifier;
/* @var $this yii\web\View */
/* @var $searchModel common\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <ol class="breadcrumb">
                <li><a href="<?=Yii::$app->homeUrl?>">首页</a></li>
                <li><a href="<?=Yii::$app->urlManager->createUrl(['post/index'])?>">文章列表</a></li>
                <li><?=$model->title;?></li>
            </ol>
            <div class="post">
                <div class="title">
                    <h2>
                        <a href="<?=$model->url;?>">
                            <?=Html::encode($model->title);?>
                        </a>
                    </h2>
                </div>
                <div class="author">
                    <span class="glyphicon glyphicon-time" aria-hidden="true"><em><?=date('Y-m-d H:i:s',$model->create_time)?></em></span>
                    <span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <span class="glyphicon glyphicon-user" aria-hidden="true"><em><?=Html::encode($model->author->nickname)?></em></span>
                </div>
                <hr/>
                <div class="content">
                    <?=HTMLPurifier::process($model->content)?>
                </div>
            </div>
            <hr/>
            <div class="nav">
                <span class="glyphicon glyphion-tag"></span>
                <?=implode(',',$model->tagLinks)?>
                <br>
                <?=Html::a("评论({$model->commentCount})",$model->url."#comments");?>
                最后修改于<?=date('Y-m-d H:i:s',$model->update_time)?>
            </div>
            <div id="comments">
                <?php if($added) {?>
                    <br>
                    <div class="alert alert-warning alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                        <h4>谢谢您的回复，我们会尽快审核后发布出来！</h4>

                        <p><?= nl2br($commentModel->content);?></p>
                        <span class="glyphicon glyphicon-time" aria-hidden="true"></span><em><?= date('Y-m-d H:i:s',$model->create_time)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";?></em>
                        <span class="glyphicon glyphicon-user" aria-hidden="true"></span><em><?= Html::encode($model->author->nickname);?></em>
                    </div>
                <?php }?>
                <?php if($model->commentCount > 0):?>
                    <?=$this->render('_comment',[
                            'post'=>$model,
                             'comments' => $model->activeComments,
                    ])?>
                <?php endif;?>
                <h5>发表评论</h5>
                <?php
                    $postComment = new \common\models\Comment();
                    echo $this->render('_guestform',[
                            'id'=>$model->id,
                            'commentModel'=>$commentModel,
                    ]);
                ?>
            </div>
        </div>
        <div class="col-md-3">
            <div class="searchbox">
                <ul class="list-group">
                    <li class="list-group-item">
                        <span class="glyphicon glyphicon-search"></span>查找文章
                    </li>
                    <li class="list-group-item">
                        <form class="form-inline" action="index.php?r=post/index" method="get">
                            <div class="form-group">
                                <input type="text" class="form-control" id="w0input" placeholder="按标题搜索" name="PostFrontSearch[title]">
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


