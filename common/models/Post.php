<?php

namespace common\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property string $tags
 * @property int $status
 * @property int $create_time
 * @property int $update_time
 * @property int $author_id
 *
 * @property Comment[] $comments
 * @property Adminuser $author
 * @property Poststatus $status0
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'content', 'status', 'author_id'], 'required'],
            [['content', 'tags'], 'string'],
            [['status', 'create_time', 'update_time', 'author_id'], 'integer'],
            [['title'], 'string', 'max' => 128],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Adminuser::className(), 'targetAttribute' => ['author_id' => 'id']],
            [['status'], 'exist', 'skipOnError' => true, 'targetClass' => Poststatus::className(), 'targetAttribute' => ['status' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'content' => '内容',
            'tags' => '标签',
            'status' => '状态',
            'create_time' => '创建时间',
            'update_time' => '修改时间',
            'author_id' => '作者',
        ];
    }
//    public function init(){
//        //Yii::error('I am init!','info');
//        Yii::info('I am afterfind ssss ggggg!','info');
//    }
    /*
     * 自动更新文章的添加或修改时间
     */
    public function beforeSave($insert){
        if(parent::beforeSave($insert)){
            if($insert){
                $this->create_time = time();
                $this->update_time = time();
            }else{
                $this->update_time = time();
            }
            return true;
        }
        return false;

    }

//    public function afterFind()
//    {
//            //echo "<script>alert(111111);</script>";
//            Yii::warning('I am warning !!!!!','info');
//
//    }
    /**
     * @return \yii\db\ActiveQuery
     */
    
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['post_id' => 'id']);
    }
    //已审核通过的
    public function getActiveComments()
    {
        return $this->hasMany(Comment::className(), ['post_id' => 'id'])->where('status=:status',[':status'=>2])->orderBy('id DESC');
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Adminuser::className(), ['id' => 'author_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()
    {
        return $this->hasOne(Poststatus::className(), ['id' => 'status']);
    }
    /*
     * 获取连接
     */
    public function getUrl(){
        return Yii::$app->urlManager->createUrl(['post/detail','id'=>$this->id,'title'=>$this->title]);
    }
    /*
     * 截取中文字符
     */
    public function getBeginning($length=288){
        $tmpStr = strip_tags($this->content);
        $len = mb_strlen($tmpStr);
        $tmpStr = mb_substr($tmpStr,0,$length,'utf-8');
        return $tmpStr.($len > $length ? '...' : '');
    }
    /*
     * 标签连接
     */
    public function getTagLinks(){
        $links = [];
        foreach (Tag::string2array($this->tags) as $tag){
            $links[] = Html::a(Html::encode($tag),['post/index','PostFrontSearch[tags]'=>$tag]);
        }
        return $links;
    }
    /*
     * 获取文章评论个数
     */
    public function getCommentCount(){
        return Comment::find()->where(['post_id'=>$this->id,'status'=>2])->count();
    }
}
