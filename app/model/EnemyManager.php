<?php 
namespace App\Model;

class EnemyManager extends TableManager {

    private $connection;
    protected $tableName = 'pzrs_enemy';

    public function getPostsLimited($limit, $offset) {
        return $this->findAll()->limit($limit, $offset);
    }


    public function getAllPosts() {
        return $this->findAll();
    }   

    public function getByName($name) {
    return $this->findAll()->fetchPairs($name);
    }  


    public function getPostsCount(){
	return $this->findAll()->count();
    }

    public function getVisiblePostsCount(){
    return $this->findAllVisible()->count();
    }

    public function getById($id){
    return $this->findBy(array('id' => $id));
    }

    public function findByName($name){
    return $this->findBy(array('teamA' => $name));
    }

/*
    public function editPost($id, $title, $teaser,$active){
    $this->findBy(array('id' => $id))->update(array("title" => $title, 'teaser' => $teaser, 'active' => $active));
    }
*/
    public function editPost($id, $teamA, $teamALogo, $created_at){
    $this->findBy(array('id' => $id))->update(array(
            'teamA'     => $teamA,
            'teamALogo'     => $teamALogo,
            'created_at' => $created_at));
    }

    public function delImg($id, $img){
    $this->findBy(array('id' => $id))->update(array('teamALogo' => null));
    }

    public function updateImg($id, $old, $new){
    $this->findBy(array('id' => $id))->update(array('teamALogo' => $new));
    }

    public function setActive($id, $active){
    $this->findBy(array("id" => $id))->update(array("active" => $active));
    }   
    
    public function addItem($teamA, $teamALogo, $created_at){
      $query = $this->findAll()->insert(array(
            'teamA'     => $teamA,
            'teamALogo'     => $teamALogo,
            'created_at' => $created_at));
      return true;
    }

    public function removeItem($id){
    $this->findBy(array('id' => $id))->delete();
    }

    

}