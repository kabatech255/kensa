<?php
namespace MyApp\Model;

class C_category Extends \MyApp\Model {
  // public function create($values){
  //   global $AES_KEY;
  //   $stmt = $this->db->prepare("insert into m_user (id,name,duRank,depId,area,block,post,pass) values (:id,:name,:duRank,:depId,:area,:block,:post,HEX(AES_ENCRYPT(:password,'{$AES_KEY}')))");
  //   $stmt->bindValue(':id',$values['id']);
  //   $stmt->bindValue(':name',$values['name']);
  //   $stmt->bindValue(':duRank',$values['duRank']);
  //   $stmt->bindValue(':depId',$values['depId']);
  //   $stmt->bindValue(':area',$values['area']);
  //   $stmt->bindValue(':block',$values['block']);
  //   $stmt->bindValue(':post',$values['post']);
  //   $stmt->bindValue(':password',$values['password']);
  //   $stmt->execute();
  //
  //   if($res === false){
  //     throw new \MyApp\Exception\DuplicateId();
  //   }
  // }

  public function selectCategory($value){
    $stmt = $this->db->prepare("select * from m_c_cate where id = :id");
    $stmt->bindValue(':id',$value);
    $stmt->execute();
    $stmt->setFetchMode(\PDO::FETCH_CLASS,'stdClass');
    $category = $stmt->fetch();
    return $category;
  }

  public function findAll(){
    $stmt = $this->db->prepare("select * from m_c_cate order by id");
    $stmt->execute();
    $stmt->setFetchMode(\PDO::FETCH_CLASS, 'stdClass');
    return $stmt->fetchAll();
  }


}

?>
