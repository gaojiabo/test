<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/28
 * Time: 9:20
 */

namespace console\controllers;

use common\models\Comment;
use yii\console\Controller;
class SmsController extends Controller{
    public function actionSend(){
        $commentCount = Comment::find()->where(['remind'=>0,'status'=>1])->count();
        if($commentCount > 0){
            $content = '有'.$commentCount.'条未审核的新评论';
            $res = $this->vendorSmsService($content);
            if($res['status'] == 'success'){
                Comment::updateAll(['remind'=>1]);//通知完毕后全部改状态为已通知
                echo '['.date("Y-m-d H:i:s",$res['dt']).'] '.$content.'['.$res['length'].']'."\r\n";//记录日志
            }
            return 0;
        }
    }
    protected function vendorSmsService($content)
    {
        $result=array("status"=>"success","dt"=>time(),"length"=>43);  //模拟数据
        return $result;

    }
}