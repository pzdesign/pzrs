<?php 
namespace App\Model;

class PostsManager extends TableManager {

    private $connection;
    public function __construct(\Nette\Database\Context $connection) {
	parent::__construct($connection);
	$this->tableName = "pzrs_posts";
    }

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
/*
    public function editPost($id, $title, $teaser,$active){
    $this->findBy(array('id' => $id))->update(array("title" => $title, 'teaser' => $teaser, 'active' => $active));
    }
*/
    public function editPost($id, $title, $teaser, $body, $slug, $active, $edited_at){
    $this->findBy(array('id' => $id))->update(array(
        'title'  => $title,
        'teaser' => $teaser,
        'body'   => $body,
        'active' => $active,
        'slug'   => $slug,
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
    
    public function addItem($title, $teaser, $body, $active, $slug, $created_at,$img){
    $this->findAll()->insert(array(
        'title'     => $title,
        'teaser'    => $teaser,
        'body'      => $body,
        'active'    => $active,
        'slug'      => $slug,
        'created_at' => $created_at,
        'img'       => $img));
    }

    public function removeItem($id){
    $this->findBy(array('id' => $id))->delete();
    }

    

}