<?php 
namespace App\Model;

class ResultsManager extends TableManager {

    private $connection;
    protected $tableName = 'pzrs_matches';

    public function getPostsLimited($limit, $offset) {
        return $this->findAll()->limit($limit, $offset);
    }


    public function getAllPosts() {
        return $this->findAll();
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
    public function editPost($id, 
        $teamA, $teamALogo, 
        $teamB,$teamBLogo,
        $map1, $map2, $map3,  
        $resultAMap1, $resultAMap2, $resultAMap3, 
        $resultBMap1, $resultBMap2, $resultBMap3, 
        $resultA, $resultB, 
        $edited_at)
    {

        $this->findBy(array('id' => $id))->update(array(
        'teamA'  => $teamA,
        'teamALogo' => $teamALogo,
        'teamB'   => $teamB,
        'teamBLogo' => $teamBLogo,
        'map1'     => $map1,
        'map2'     => $map2,        
        'map3'     => $map3,        
        'resultAMap1'     => $resultAMap1,        
        'resultAMap2'     => $resultAMap2,        
        'resultAMap3'     => $resultAMap3,        
        'resultBMap1'     => $resultBMap1,        
        'resultBMap2'     => $resultBMap2,        
        'resultBMap3'     => $resultBMap3,        
        'resultA'     => $resultA,        
        'resultB'     => $resultB,        
        'edited_at' => $edited_at));

    }

    public function delImg($id, $img){
    $this->findBy(array('id' => $id))->update(array('img' => null));
    }

    public function updateImg($id, $old, $new){
    $this->findBy(array('id' => $id))->update(array('img' => $new));
    }

    public function setActive($id, $active){
    $this->findBy(array("id" => $id))->update(array("active" => $active));
    }   
 
    public function setCanBeEdited($id){
    $this->findBy(array("id" => $id))->update(array("canbeedited" => '0'));
    }  

    public function addItem(
        $teamA, $teamALogo, 
        $teamB, $teamBLogo, 
        $map1, $map2, $map3,  
        $resultAMap1, $resultAMap2, $resultAMap3, 
        $resultBMap1, $resultBMap2, $resultBMap3,  
        $resultA, $resultB, $win, 
        $created_at, $canBeEdited)
    {
            $query = $this->findAll()->insert(array(
            'teamA'     => $teamA,
            'teamALogo'     => $teamALogo,
            'teamB'     => $teamB,            
            'teamBLogo'     => $teamBLogo,
            'map1'     => $map1,
            'map2'     => $map2,
            'map3'     => $map3,
            'resultAMap1'     => $resultAMap1,        
            'resultAMap2'     => $resultAMap2,        
            'resultAMap3'     => $resultAMap3,        
            'resultBMap1'     => $resultBMap1,        
            'resultBMap2'     => $resultBMap2,        
            'resultBMap3'     => $resultBMap3,             
            'resultA'     => $resultA,             
            'resultB'     => $resultB,             
            'win'     => $win,             
            'created_at' => $created_at,
            'canBeEdited' => $canBeEdited));
      return true;
    }

    public function removeItem($id){
    $this->findBy(array('id' => $id))->delete();
    }

    

}