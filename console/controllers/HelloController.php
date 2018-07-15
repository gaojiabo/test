<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/27
 * Time: 14:34
 */

namespace console\controllers;


use common\models\Post;
use yii\console\Controller;

class HelloController extends Controller
{
    public $rev;
    /*
     * 带选项的方法
     */
//    public function options(){
//        //parent::options();
//        return ['rev'];
//    }

    public function actionAliases(){
        return ['r'=>'rev'];
    }

    public function actionIndex(){
        if($this->rev == 1){
            echo strrev('Hello world!')."\n";
        }else{
            echo "Hello world!"."\n";
            echo \Yii::getVersion();
        }
    }
    public function actionIndexs(){
        echo "Hello World! \n";
    }

    public function actionList(){
        $post = Post::find()->all();
        foreach ($post as $item){
            echo $item['id']."====".$item['title']."\n";
        }
    }
    /*
     * 以下是带参数的方法
     */
    public function actionTest($name,$job)
    {
        echo "You name is ".$name." and job is ".$job;
    }
    /*
     * 以下是带数组参数的方法
     */
    public function actionArr(array $arr)
    {
        print_r($arr);
    }
}