<?php

namespace MyApp\Controller;
/*------------------ページング機能のための各変数、プロパティ、setValues-----------------------
1.(setValues)totalArticles = 全レコードの件数
  ===>$this->masterModel->CountRecords($_SESSION['masterName'])
2.$this->limit = 1ページに表示させるレコードの数
3.(setValues)totalPages = 全ページ数
  ===>ceil(totalArticles / $this->limit)
4.$page = 遷移したいページ
5.$this->offset =　$pageページ目は、{from}件目から{to}件目までを表示するので、最初のoffset件分のレコードは取得しない
  ===> ( $page - 1 ) * $this->limit
6.(setValues)from = $this->offset + 1
7.(setValues)to = $this->limit + $this->offset
-----------------------------------------------------------------------------------------*/


/*------------------マスターテーブルのための各変数、プロパティ、setValues-----------------------
1.$this->masterNames = 連想配列（$key=マスター名の日本語表記)。$valueは、foreachで回して$keyと適合する6のテーブル名を代入していく
2.$tableList　= 配列（$value = object）⇒　$tableList[$i]->Tables_in_sampleがテーブル名
  ===>1と2で作った配列をマスター選択用のドロップダウンリストに使う
3.$_SESSION['masterName'] = $this->masterNamesのkeyを指定し、任意のマスターテーブル名を格納する
4.(setValues)master = $_SESSION['masterName']で指定したテーブルの全レコード(findAll)
  ⇒ param1...$_SESSION['masterName'], param2...$this->limit, param3...$this->offset

-----------------------------------------------------------------------------------------*/



class Master extends \MyApp\Controller{
  protected $limit;
  protected $offset;
  protected $masterModel;
  protected $masterNames = [
    '検査カテゴリーマスター'=>'',
    '指摘内容マスター'=>'',
    '設問番号マスター'=>'',
    '失点マスター'=>'',
    '期限ルールマスター'=>'',
    '部署マスター'=>'',
    '本部マスター'=>'',
    '分類マスター'=>'',
    '商品マスター'=>'',
    '支社マスター'=>'',
    '店舗マスター'=>'',
    'ユーザーマスター'=>'',
  ];

  public function __construct(){
    $this->limit = 30;
    $this->masterModel = new \MyApp\Model\Masters();
    $tableList = $this->masterModel->getTableList();
    $i = 0;
    foreach($this->masterNames as $key=>$value){
      //
      if(strpos($tableList[$i]->Tables_in_sample,'m_') !== 0){
        continue;
      }
      $this->masterNames[$key] = $tableList[$i]->Tables_in_sample;
      $i++;
      if($i > count($tableList)){
        break;
      }
    }
    // $this->masterNames は 連想配列（'支社マスター'=>'m_master',...）
    $this->setValues('masterNames',$this->masterNames);
  }

  public function run(){

    if(!$this->isLoggedIn()){
      header('location: ' . SITE_URL . '/login.php');
      exit;
    }
    if($_SESSION['me']->post !== '開発者'){
      header('location: ' . SITE_URL );
      exit;
    }
    if($_SERVER['REQUEST_METHOD']==='POST'){
      $this->postProcess();
    }

    $_SESSION['masterName'] = !isset($_SESSION['masterName']) ? $this->getValues()->masterNames['検査カテゴリーマスター'] : $_SESSION['masterName'];
    $page = isset($_GET['p']) && preg_match('/^[1-9][0-9]*$/', $_GET['p']) && $_SERVER['REQUEST_METHOD'] === 'GET' ? $_GET['p'] : 1 ;
    $this->setValues('page',$page);
    $this->offset = ($page - 1) * $this->limit;
    $this->setValues('master',$this->masterModel->findAll($_SESSION['masterName'],$this->limit,$this->offset));
    $this->setValues('columns',$this->masterModel->findColumns($_SESSION['masterName']));
    $this->setValues('totalArticles',$this->masterModel->CountRecords($_SESSION['masterName']));
    $this->setValues('totalPages',ceil($this->getValues()->totalArticles / $this->limit));
    $this->setValues('from',$this->offset + 1);
    $to = $this->offset + $this->limit ;
    if($to > $this->getValues()->totalArticles){
      $to = $this->getValues()->totalArticles;
    }
    $this->setValues('to',$to);
  }

/*-------------------------------postProcess--------------------------------*/
  protected function postProcess(){
    if(!isset($_POST['token']) || $_POST['token']!==$_SESSION['token']){
      echo 'InvalidToken!';
      exit;
    }
    $_SESSION['masterName'] = h($_POST['masterName']);
    $this->setValues('page',1);
    $this->setValues('columns',$this->masterModel->findColumns($_SESSION['masterName']));
    $this->setValues('master',$this->masterModel->findAll($_SESSION['masterName'],$this->limit,$this->offset));
    $primaryKey = $this->getPrimaryKeyName();
    // 修正ボタンを押した場合
    foreach($this->getValues()->master as $record){
      if(isset($_POST["modify-{$record->$primaryKey}"])){
        $_SESSION['re_id'] = $record->$primaryKey;
        header('location: ' . SITE_URL . '/mastermodify.php');
        exit;
      }
    }
    // 削除ボタンを押した場合
    if(isset($_POST['delete'])){
      foreach($this->getValues()->master as $record){
        if(isset($_POST["del-{$record->$primaryKey}"])){
          $id = $record->$primaryKey;
          $this->masterModel->delete($_SESSION['masterName'],$primaryKey,$id);
        }
      }
      header('location: ' . SITE_URL . '/master.php');
      exit;
    }
  }

  public function selectedMaster($value){
    return $value === $_SESSION['masterName'] ? ' selected' : '' ;
  }
  public function getPrimaryKeyName(){
    return $this->getValues()->columns[0]->Field;
  }
  public function getPrevHidden(){
    return $this->getValues()->page == 1 ? 'hidden' : '';
  }
  public function getNextHidden(){
    return $this->getValues()->totalPages == $this->getValues()->page ? 'hidden' : '';
  }
  public function getCurrent($value){
    return $value == $this->getValues()->page ? ' current' : '';
  }

}
 ?>
