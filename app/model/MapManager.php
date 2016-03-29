<?php 
namespace App\Model;

class MapManager extends TableManager {

    private $connection;
    protected $tableName = 'pzrs_maps';

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
    return $this->findBy(array('mapName' => $name));
    }

/*
    public function editPost($id, $title, $teaser,$active){
    $this->findBy(array('id' => $id))->update(array("title" => $title, 'teaser' => $teaser, 'active' => $active));
    }
*/
    public function editPost($id, $mapName, $mapImg, $active){
    $this->findBy(array('id' => $id))->update(array(
            'mapName'     => $mapName,
            'mapImg'     => $mapImg,
            'active' => $active));
    }

    public function delImg($id, $img){
    $this->findBy(array('id' => $id))->update(array('mapImg' => null));
    }

    public function updateImg($id, $old, $new){
    $this->findBy(array('id' => $id))->update(array('mapImg' => $new));
    }

    public function setActive($id, $active){
    $this->findBy(array("id" => $id))->update(array("active" => $active));
    }   
    
    public function addItem($mapName, $mapImg, $active){
      $query = $this->findAll()->insert(array(
            'mapName'     => $mapName,
            'mapImg'     => $mapImg,
            'active' => $active));
      return true;
    }

    public function removeItem($id){
    $this->findBy(array('id' => $id))->delete();
    }

    

}